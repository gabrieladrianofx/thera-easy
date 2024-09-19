<?php

namespace App\Enums;

enum CivilStatus: string
{
    case Single    = 'single';
    case Married   = 'married';
    case Separated = 'separated';
    case Divorced  = 'divorced';
    case widowed   = 'widowed';
}
