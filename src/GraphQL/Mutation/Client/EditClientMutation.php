<?php

namespace App\GraphQL\Mutation\Client;


use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Security\Voter\ClientVoter;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditClientMutation implements MutationInterface
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(
        ClientRepository $clientRepository,
        AuthorizationCheckerInterface $checker,
        ValidatorInterface $validator,
        EntityManagerInterface $manager
    ) {
        $this->clientRepository = $clientRepository;
        $this->checker = $checker;
        $this->validator = $validator;
        $this->manager = $manager;
    }

    public function __invoke(Argument $argument)
    {
        [$name, $address, $zipCode, $clientSlug] = [
            $argument->offsetGet('name'),
            $argument->offsetGet('address'),
            $argument->offsetGet('zip_code'),
            $argument->offsetGet('client')
        ];

        /** @var Client $client */
        if ($client = $this->clientRepository->findOneBySlug($clientSlug)) {
            if (!$this->checker->isGranted(ClientVoter::EDIT, $client)) {
                throw new UserError('You are not allowed to do this.');
            }
            $slugify = new Slugify();
            $client
                ->setName($name)
                ->setAddress($address)
                ->setZipCode($zipCode)
                ->setSlug($slugify->slugify($name));
        } else {
            throw new UserError("The client with the slug $clientSlug does not exist");
        }

        $errors = $this->validator->validate($client);
        if ($errors->count() > 0) {
            throw new UserError($errors);
        }
        $this->manager->flush();

        return compact('client');
    }

}