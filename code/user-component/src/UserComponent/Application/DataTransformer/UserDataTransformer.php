<?php

declare(strict_types=1);

namespace App\UserComponent\Application\DataTransformer;

use App\UserComponent\Domain\Entity\User;
use App\UserComponent\Presentation\Swagger\UserSwagger;

class UserDataTransformer
{
    public function writeAuthTokens(User $user, UserSwagger $userSwagger): void
    {
        $userSwagger->id = $user->getId();
        $userSwagger->plainId = $user->getId();
        $userSwagger->email = $user->getEmail();
        $userSwagger->accessToken = $user->getAccessToken();
        $userSwagger->refreshToken = $user->getRefreshToken();
    }

    public function writeUserSwagger(User $user, UserSwagger $userSwagger): void
    {
        $userSwagger->plainId = $user->getId();
        $userSwagger->email = $user->getEmail();
        $userSwagger->name = $user->getName();
        $userSwagger->lastName = $user->getLastName();
        $userSwagger->newsletter = $user->isNewsletter();
        $userSwagger->refreshToken = $user->getRefreshToken();
        $userSwagger->trialUsed = $user->isTrialUsed();
    }

}