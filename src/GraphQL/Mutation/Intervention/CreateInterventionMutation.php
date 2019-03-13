<?php

namespace App\GraphQL\Mutation\Intervention;


use App\Entity\Intervention;
use App\Entity\InterventionType;
use App\Repository\ClientRepository;
use App\Repository\InterventionRepository;
use App\Repository\InterventionTypeRepository;
use App\Security\Voter\InterventionVoter;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use GraphQL\Error\UserError;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateInterventionMutation implements MutationInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;
    /**
     * @var InterventionRepository
     */
    private $interventionRepository;
    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var InterventionTypeRepository
     */
    private $typeRepository;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(
        AuthorizationCheckerInterface $checker,
        InterventionRepository $interventionRepository,
        ClientRepository $clientRepository,
        InterventionTypeRepository $typeRepository,
        ValidatorInterface $validator,
        EntityManagerInterface $manager
    ) {
        $this->checker = $checker;
        $this->interventionRepository = $interventionRepository;
        $this->clientRepository = $clientRepository;
        $this->typeRepository = $typeRepository;
        $this->validator = $validator;
        $this->manager = $manager;
    }

    public function __invoke(Argument $args)
    {
        [$client, $startAt, $endAt, $inProgress, $type] = [
            $args->offsetGet('client'),
            $args->offsetGet('startAt'),
            $args->offsetGet('endAt'),
            $args->offsetGet('inProgress'),
            $args->offsetGet('type')
        ];

        $intervention = new Intervention();
        if (!$this->checker->isGranted(InterventionVoter::CREATE, $intervention)) {
            throw new UserError('You are not allowed to do this.');
        }
        $client = $this->clientRepository->find($client);

        if (!$this->interventionRepository->findOneBy([
            'client' => $client,
            'createdAt' => new \DateTime($startAt),
            'endAt' => new \DateTime($endAt)
        ])) {
            throw new UserError('An intervention is already planned for this client, at this time.');
        }

        try {
            /** @var InterventionType $type */
            $type = $this->typeRepository->findOneBy(['name' => $type]);
        } catch (\Exception $exception) {
            throw new UserError('The client does not exist.');
        }

        $intervention
            ->setClient($client)
            ->setStartAt($startAt)
            ->setEndAt($endAt)
            ->setType($type)
            ->setInProgress($inProgress);

        $errors = $this->validator->validate($intervention);
        if ($errors->count() > 0) {
            throw new UserError($errors);
        }
        $this->manager->flush();

        return compact('intervention');
    }
}
