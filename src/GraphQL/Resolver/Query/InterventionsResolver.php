<?php

namespace App\GraphQL\Resolver\Query;


use App\Repository\InterventionRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Output\ConnectionBuilder;

class InterventionsResolver implements ResolverInterface
{
    /**
     * @var InterventionRepository
     */
    private $interventionRepository;

    public function __construct(InterventionRepository $interventionRepository)
    {
        $this->interventionRepository = $interventionRepository;
    }

    public function __invoke(Argument $args): Connection
    {
        $client = $args->offsetGet('clientId');
        $interventions = $this->interventionRepository->findBy([
            'client' => $client
        ]);
        $connection = ConnectionBuilder::connectionFromArray($interventions, $args);
        $connection->totalCount = \count($interventions);

        return $connection;
    }
}