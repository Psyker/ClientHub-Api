<?php

namespace App\GraphQL\Resolver\Query;

use App\Repository\ClientRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Output\ConnectionBuilder;

class ClientsResolver implements ResolverInterface
{
    private $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Argument $args): Connection
    {
        $clients = $this->repository->findAll();
        $connection = ConnectionBuilder::connectionFromArray($clients, $args);
        $connection->totalCount = \count($clients);

        return $connection;
    }
}
