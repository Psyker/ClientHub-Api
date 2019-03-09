<?php

namespace App\GraphQL\Mutation\Client;

use App\Repository\ClientRepository;
use App\Security\Voter\ClientVoter;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Relay\Node\GlobalId;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DeleteClientMutation implements MutationInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    public function __construct(
        EntityManagerInterface $manager,
        AuthorizationCheckerInterface $checker,
        ClientRepository $clientRepository
    ) {
        $this->manager = $manager;
        $this->checker = $checker;
        $this->clientRepository = $clientRepository;
    }

    public function __invoke(Argument $argument)
    {
        $globalId = $argument->offsetGet('id');
        $id = GlobalId::fromGlobalId($globalId)['id'];
        if ($client = $this->clientRepository->find($id)) {
            $deletedClientId = $globalId;
            if (!$this->checker->isGranted(ClientVoter::EDIT, $client)) {
                throw new UserError('You are not allowed to do this.');
            }
            $this->manager->remove($client);
            $this->manager->flush();
        } else {
            throw new UserError("The client with the id $id does not exist.");
        }

        return compact('deletedClientId');
    }

}