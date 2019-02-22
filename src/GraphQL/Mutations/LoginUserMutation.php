<?php

namespace App\GraphQL\Mutations;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginUserMutation implements MutationInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        UserRepository $repository,
        JWTTokenManagerInterface $tokenManager
    ) {

        $this->encoder = $encoder;
        $this->repository = $repository;
        $this->tokenManager = $tokenManager;
    }

    public function __invoke(Argument $argument)
    {
        [$username, $password] = [$argument->offsetGet('username'), $argument->offsetGet('password')];
        if ($viewer = $this->repository->findOneBy(compact($username))) {
            if ($this->encoder->isPasswordValid($viewer, $password)) {
                $token = $this->tokenManager->create($viewer);

                return compact($token);
            }
            throw new UserError('bad credentials.');
        }
        throw new UserError('bad credentials.');
    }
}
