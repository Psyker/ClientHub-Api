<?php

namespace App\GraphQL\Resolver\Query;

use App\Repository\BusinessSegmentRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class BusinessSegmentsResolver implements ResolverInterface
{
    /**
     * @var BusinessSegmentRepository
     */
    private $repository;

    public function __construct(BusinessSegmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Argument $args)
    {
        return $this->repository->findAll();
    }
}