<?php

namespace App\Enums\Tasks;

use App\Enums\Traits\NamesTrait;

enum Status: int
{
    use NamesTrait;

    case PENDING = 0;
    case COMPLETED = 1;

}
