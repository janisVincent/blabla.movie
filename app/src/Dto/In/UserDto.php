<?php

namespace App\Dto\In;

use Symfony\Component\Validator\Constraints as Assert;

final class UserDto
{
    /**
     * @var string
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotCompromisedPassword()
     */
    public $password;
}
