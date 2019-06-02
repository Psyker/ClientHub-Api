<?php


namespace App\GraphQL\Mutation\Referrer;


use App\Entity\Referrer;
use App\Repository\ReferrerRepository;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateReferrerMutation implements MutationInterface
{
    /**
     * @var ReferrerRepository
     */
    private $referrerRepository;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        ReferrerRepository $referrerRepository,
        EntityManagerInterface $manager,
        ValidatorInterface $validator
    ) {
        $this->referrerRepository = $referrerRepository;
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function __invoke(Argument $args)
    {
        [$firstname, $lastname, $job, $email] = [
            $args->offsetGet('firstname'),
            $args->offsetGet('lastname'),
            $args->offsetGet('job'),
            $args->offsetGet('email')
        ];

        $referrer = new Referrer();
        // TODO: IMPLEMENT VOTER HERE

        if ($this->referrerRepository->findOneByEmail($email)) {
            throw new UserError('A referrer has been already created with this email');
        }

        $referrer
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setJob($job)
            ->setEmail($email);
        $this->manager->persist($referrer);

        $errors = $this->validator->validate($referrer);
        if ($errors->count() > 0) {
            throw new UserError($errors);
        }
        $this->manager->flush();

        return compact('referrer');
    }
}
