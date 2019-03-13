<?php

namespace App\GraphQL\Resolver\Query;

use App\Entity\Client;
use App\Repository\InterventionRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\ConnectionBuilder;

class InterventionsResolver implements ResolverInterface
{
    /**
     * @var InterventionRepository
     */
    private $repository;

    public function __construct(InterventionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Client $client, Argument $args)
    {
        $orderBy = $args->offsetGet('orderBy');
        $interventions = $this->repository->findBy(
            ['client' => $client],
            [$orderBy['field'] => $orderBy['direction']]
        );
        $connection = ConnectionBuilder::connectionFromArray($interventions, $args);
        $connection->totalCount = \count($interventions);

        return $connection;
    }
}