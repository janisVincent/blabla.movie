<?php

namespace App\Dto\Out;

use Symfony\Component\Validator\Constraints as Assert;

final class UserDto
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var UserProfileDto
     */
    public $profile;

    public function __construct(string $email, ?UserProfileDto $profile)
    {
        $this->email = $email;
        $this->profile = $profile;
    }
}
