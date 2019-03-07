<?php

namespace App\GraphQL\Resolver\Query;

use App\Repository\ClientRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Output\ConnectionBuilder;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ClientsResolver implements ResolverInterface
{
    /**
     * @var ClientRepository
     */
    private $repository;
    /**
     * @var TokenStorageInterface
     */

    private $tokenStorage;

    public function __construct(ClientRepository $repository, TokenStorageInterface $tokenStorage)
    {
        $this->repository = $repository;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(Argument $args): Connection
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $clients = $this->repository->findBy(['user' => $user]);
        $connection = ConnectionBuilder::connectionFromArray($clients, $args);
        $connection->totalCount = \count($clients);

        return $connection;
    }
}
