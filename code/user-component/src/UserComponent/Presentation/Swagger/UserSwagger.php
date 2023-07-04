<?php

declare(strict_types=1);

namespace App\UserComponent\Presentation\Swagger;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\UserComponent\Presentation\Controller\EditUserController;
use App\UserComponent\Presentation\Controller\GetAuthenticatedUserController;
use App\UserComponent\Presentation\Controller\LoginController;
use App\UserComponent\Presentation\Controller\RefreshTokenController;
use App\UserComponent\Presentation\Controller\RegisterController;
use App\UserComponent\Presentation\Controller\RequestPasswordResetEmailController;
use App\UserComponent\Presentation\Controller\ResetPasswordController;
use App\UserComponent\Presentation\Controller\ValidateResetPasswordTokenController;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'User',
    operations: [
        new Get(
            uriTemplate: '/user',
            status: 200,
            controller: GetAuthenticatedUserController::class,
            openapiContext: [
                'summary' => 'Retrieves the authenticated user',
                'description' => 'Retrieves the authenticated user',
            ],
            normalizationContext: [
                'groups' => ['read'],
            ],
            read: false
        ),
        new Post(
            uriTemplate: '/user/validate-reset-password-token',
            status: 200,
            controller: ValidateResetPasswordTokenController::class,
            openapiContext: [
                'summary' => 'Checks if the reset password token is valid',
                'description' => 'Checks if the reset password token is valid',
            ],
            normalizationContext: [
                'groups' => ['read'],
            ],
            denormalizationContext: [
                'groups' => ['reset-password-token'],
            ],
            read: false
        ),
        new Post(
            uriTemplate: '/user',
            status: 201,
            controller: RegisterController::class,
            openapiContext: [
                'summary' => 'Registers a user',
                'description' => 'Registers a user',
            ],
            normalizationContext: [
                'groups' => ['jwt'],
            ],
            denormalizationContext: [
                'groups' => ['register'],
            ],
        ),
        new Post(
            uriTemplate: '/user/login',
            status: 201,
            controller: LoginController::class,
            openapiContext: [
                'summary' => 'Logs in a user',
                'description' => 'Logs in a user',
            ],
            normalizationContext: [
                'groups' => ['jwt'],
            ],
            denormalizationContext: [
                'groups' => ['login'],
            ],
        ),
        new Post(
            uriTemplate: '/user/refresh-token',
            status: 201,
            controller: RefreshTokenController::class,
            openapiContext: [
                'summary' => 'Refreshes a user token',
                'description' => 'Refreshes a user token',
            ],
            normalizationContext: [
                'groups' => ['jwt'],
            ],
            denormalizationContext: [
                'groups' => ['refresh-token'],
            ],
        ),
        new Post(
            uriTemplate: '/user/request-reset-email',
            status: 201,
            controller: RequestPasswordResetEmailController::class,
            openapiContext: [
                'summary' => 'Requests an email to reset the password',
                'description' => 'Requests an email to reset the password',
            ],
            normalizationContext: [
                'groups' => ['read'],
            ],
            denormalizationContext: [
                'groups' => ['request-reset-email'],
            ],
        ),
        new Post(
            uriTemplate: '/user/reset-password',
            status: 201,
            controller: ResetPasswordController::class,
            openapiContext: [
                'summary' => 'Resets the password',
                'description' => 'Resets the password',
            ],
            normalizationContext: [
                'groups' => ['read'],
            ],
            denormalizationContext: [
                'groups' => ['reset-password'],
            ],
        ),
        new Put(
            uriTemplate: '/user',
            status: 201,
            controller: EditUserController::class,
            openapiContext: [
                'summary' => 'Edits the user',
                'description' => 'Edits the user',
            ],
            normalizationContext: [
                'groups' => ['read'],
            ],
            denormalizationContext: [
                'groups' => ['update'],
            ],
            read: false
        ),
    ]
)]
class UserSwagger
{

    #[ApiProperty(
        description: 'Identifier',
        identifier: true,
        example: 1,
    )]
    public ?string $id = null;

    #[Groups(
        ['read','jwt']
    )]
    public ?string $plainId = null;

    #[ApiProperty(
        description: 'Email',
        example: 'test@example.com',
    )]
    #[Groups(
        ['read','register','login','request-reset-email','update','jwt']
    )]
    #[Assert\Type(
        type: 'string',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    #[Assert\Length(
        max: 256,
        maxMessage: 'Email cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank(
        message: 'Email cannot be blank',
        groups: ['register','login','request-reset-email']
    )]
    #[Assert\NotNull(groups: ['register','login','request-reset-email'])]
    public ?string $email = null;

    #[ApiProperty(
        description: 'Name',
        example: 'John',
    )]
    #[Groups(
        ['read','register','update']
    )]
    #[Assert\Type(
        type: 'string',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Name cannot be longer than {{ limit }} characters',
    )]
    #[Assert\NotBlank(
        message: 'Name cannot be blank',
        groups: ['register']
    )]
    #[Assert\NotNull(groups: ['register'])]
    public ?string $name = null;

    #[ApiProperty(
        description: 'Last name',
        example: 'Doe',
    )]
    #[Groups(
        ['read','register','update']
    )]
    #[Assert\Type(
        type: 'string',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Last name cannot be longer than {{ limit }} characters',
    )]
    #[Assert\NotBlank(
        message: 'Last name cannot be blank',
        groups: ['register']
    )]
    #[Assert\NotNull(groups: ['register'])]
    public ?string $lastName = null;

    #[ApiProperty(
        description: 'Password',
        example: 'StrongPassword',
    )]
    #[Groups(
        ['register','login','reset-password','update']
    )]
    #[Assert\Type(
        type: 'string',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    #[Assert\Length(
        max: 256,
        maxMessage: 'Password cannot be longer than {{ limit }} characters',
    )]
    #[Assert\NotBlank(
        message: 'Password cannot be blank',
        groups: ['register','login','reset-password']
    )]
    #[Assert\NotNull(groups: ['register','login','reset-password'])]
    public ?string $password;

    #[Groups(
        ['jwt']
    )]
    public string $accessToken;

    #[ApiProperty(
        description: 'Refresh Token',
        example: 'asdfjkaljgsfnmoisfndodsa',
    )]
    #[Groups(
        ['jwt','refresh-token']
    )]
    #[Assert\NotBlank(
        message: 'Password cannot be blank',
        groups: ['refresh-token']
    )]
    #[Assert\NotNull(groups: ['refresh-token'])]
    public string $refreshToken;

    #[ApiProperty(
        description: 'Reset Password Token',
        example: 'asdfjkaljgsfnmoisfndodsa',
    )]
    #[Groups(
        ['reset-password-token','reset-password']
    )]
    #[Assert\NotBlank(
        message: 'Reset Password Token cannot be blank',
        groups: ['reset-password-token', 'reset-password']
    )]
    #[Assert\NotNull(groups: ['reset-password-token', 'reset-password'])]
    public string $resetPasswordToken;

    #[Groups(
        ['read']
    )]
    public ?bool $resetPasswordEmailSent = null;

    #[Groups(
        ['read']
    )]
    public ?bool $validResetPasswordToken = null;

    #[Groups(
        ['read']
    )]
    public ?bool $passwordReset = null;

    #[Groups(
        ['read','register','update']
    )]
    #[Assert\Type(
        type: 'bool',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    public bool $newsletter = false;

    #[Groups(
        ['read','register']
    )]
    #[Assert\Type(
        type: 'bool',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    public bool $termsAccepted = false;

}