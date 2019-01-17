<?php

namespace App\GraphQL\Resolver\Query;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class ClientResolver implements ResolverInterface
{
    private $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Argument $args): ?Client
    {
        $search = $args->offsetGet('search');
        [$field, $value] = [$search['field'], $search['value']];

        return $this->repository->findOneBy([$field => $value]);
    }
}
