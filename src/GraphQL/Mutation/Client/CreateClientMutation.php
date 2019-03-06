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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateClientMutation implements MutationInterface
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
    /**
     * @var TokenStorageInterface
     */
    private $storage;

    public function __construct(
        ClientRepository $clientRepository,
        AuthorizationCheckerInterface $checker,
        ValidatorInterface $validator,
        EntityManagerInterface $manager,
        TokenStorageInterface $storage
    ) {
        $this->clientRepository = $clientRepository;
        $this->checker = $checker;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->storage = $storage;
    }

    public function __invoke(Argument $argument)
    {
        [$name, $address, $zipCode] = [
            $argument->offsetGet('name'),
            $argument->offsetGet('address'),
            $argument->offsetGet('zip_code')
        ];

        /** @var UserInterface $user */
        $user = $this->storage->getToken()->getUser();

        /** @var Client $client */
        $client = new Client();

        if (!$this->checker->isGranted(ClientVoter::CREATE, $client)) {
            throw new UserError('You are not allowed to do this.');
        }

        if (!$this->clientRepository->findOneByName($name)) {
            $slugify = new Slugify();
            $client
                ->setName($name)
                ->setAddress($address)
                ->setZipCode($zipCode)
                ->setSlug($slugify->slugify($name))
                ->setUser($user);
            $this->manager->persist($client);
        } else {
            throw new UserError("A client with the name $name already exist");
        }

        $errors = $this->validator->validate($client);
        if ($errors->count() > 0) {
            throw new UserError($errors);
        }

        $this->manager->flush();

        return compact('client');
    }
}