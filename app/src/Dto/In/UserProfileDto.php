<?php

namespace App\Dto\In;

final class UserProfileDto
{
    /**
     * User alias
     * @var string
     */
    public $alias;
    /**
     * User birth date ("Y-m-d" format)
     * @var string
     */
    public $birthDate;
}