<?php

declare(strict_types=1);

namespace App\UserComponent\Application\Service;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Domain\Jwt\JwtServiceInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JwtService implements JwtServiceInterface
{


    public function generateAccessToken(User $user): string
    {
        $payload = [
            'sub' => $user->getId(),
            'type' => 'access',
            'iat' => time(),
            //time + 1 day
            'exp' => time() + 86400,
            'revoked' => false,
        ];

        return JWT::encode(
            payload: $payload,
            key: getenv('JWT_KEY'),
            alg: 'HS256'
        );
    }

    public function generateRefreshToken(User $user): string
    {
        $payload = [
            'sub' => $user->getId(),
            'type' => 'refresh',
            'iat' => time(),
            //time + 2 weeks
            'exp' => time() + 1209600,
            'revoked' => false,
        ];

        return JWT::encode(
            payload: $payload,
            key: getenv('JWT_KEY'),
            alg: 'HS256'
        );
    }

    public function generateResetPasswordToken(User $user): string
    {
        $payload = [
            'sub' => $user->getId(),
            'type' => 'reset-password',
            'iat' => time(),
            //time + 1 day
            'exp' => time() + 86400,
            'revoked' => false,
        ];

        return JWT::encode(
            payload: $payload,
            key: getenv('JWT_KEY'),
            alg: 'HS256'
        );
    }

    /**
     * @throws Exception
     */
    public function isExpired(string $jwt): bool
    {
        $decoded = $this->decode($jwt);
        return $decoded['exp'] < time();
    }

    /**
     * @throws Exception
     */
    public function getSubject(string $jwt): string
    {
        $decoded = $this->decode($jwt);
        return $decoded['sub'];
    }


    /**
     * @throws Exception
     */
    public function decode(string $jwt): array
    {
        return (array) JWT::decode(
            $jwt,
            new Key(getenv('JWT_KEY'),'HS256'),
        );

    }

}