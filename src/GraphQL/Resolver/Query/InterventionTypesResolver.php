<?php

namespace App\GraphQL\Resolver\Query;

use App\Repository\InterventionTypeRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\ConnectionBuilder;

class InterventionTypesResolver implements ResolverInterface
{
    /**
     * @var InterventionTypeRepository
     */
    private $typeRepository;

    public function __construct(InterventionTypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }

    public function __invoke(Argument $args)
    {
        $types = $this->typeRepository->findAll();
        $connection = ConnectionBuilder::connectionFromArray($types, $args);
        $connection->totalCount = \count($types);

        return $connection;
    }
}