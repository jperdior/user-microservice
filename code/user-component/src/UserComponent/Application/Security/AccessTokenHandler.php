<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Security;

use App\UserComponent\Application\Service\JwtService;
use App\UserComponent\Domain\Exception\InvalidAccessTokenException;
use App\UserComponent\Domain\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use App\UserComponent\Domain\Repository\UserRepositoryInterface;
use Exception;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private readonly JwtService $jwtService,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $identifier = $this->jwtService->getSubject($accessToken);
        $user = $this->userRepository->findById($identifier);
        if ($user === null) {
            throw new UserNotFoundException();
        }
        if ($user->getAccessToken() !== $accessToken) {
            throw new InvalidAccessTokenException();
        }
        return new UserBadge($identifier);
    }
}