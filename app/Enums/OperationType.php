<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class OperationType extends Enum
{
    const INSERT = 1;

    const UPDATE = 2;

    const DELETE = 3;
}
