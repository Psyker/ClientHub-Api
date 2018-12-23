<?php

namespace App\GraphQL\Resolver;

use App\Repository\ClientRepository;
use App\Repository\InterventionRepository;
use App\Repository\ReferrerRepository;
use App\Repository\UserRepository;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Node\GlobalId;

class GlobalIdResolver implements ResolverInterface
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var ReferrerRepository
     */
    private $referrerRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var InterventionRepository
     */
    private $interventionRepository;

    public function __construct(
        ClientRepository $clientRepository,
        ReferrerRepository $referrerRepository,
        UserRepository $userRepository,
        InterventionRepository $interventionRepository
    ) {
        $this->clientRepository = $clientRepository;
        $this->referrerRepository = $referrerRepository;
        $this->userRepository = $userRepository;
        $this->interventionRepository = $interventionRepository;
    }

    public function __invoke(string $globalId)
    {
        $decodedGlobalId = GlobalId::fromGlobalId($globalId);
        [$type, $id] = [$decodedGlobalId['type'], $decodedGlobalId['id']];

        switch ($type) {
            case 'User' :
                return $this->userRepository->find($id);
                break;
            case 'Intervention' :
                return $this->interventionRepository->find($id);
                break;
            case 'Referrer' :
                return $this->referrerRepository->find($id);
                break;
            case 'Client' :
                return $this->clientRepository->find($id);
                break;
            default:
                throw new UserError("Couldn't resolve node.");
        }
    }
}
