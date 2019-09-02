<?php

namespace App\Dto\Out;

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

    public function __construct(string $alias, string $birthDate)
    {
        $this->alias = $alias;
        $this->birthDate = $birthDate;
    }
}