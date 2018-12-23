<?php

namespace App\GraphQL\Resolver;

use App\Entity\Client;
use App\Entity\Intervention;
use App\Entity\Referrer;
use App\Entity\User;
use GraphQL\Error\UserError;
use GraphQL\Type\Definition\Type;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Resolver\TypeResolver;

class NodeTypeResolver implements ResolverInterface
{
    /**
     * @var TypeResolver
     */
    private $resolver;

    public function __construct(TypeResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function __invoke($node): Type
    {
        if ($node instanceof User) {
            return $this->resolver->resolve('User');
        }

        if ($node instanceof Client) {
            return $this->resolver->resolve('Client');
        }

        if ($node instanceof Referrer) {
            return $this->resolver->resolve('Referrer');
        }

        if ($node instanceof Intervention) {
            return $this->resolver->resolve('Intervention');
        }

        throw new UserError('Unknown type.');
    }
}