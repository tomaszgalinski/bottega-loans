<?php

namespace Loans\LoanProviding\Domain;

use MyCLabs\Enum\Enum;

class LoanStatus extends Enum
{
    const ACTIVE = 1;
    const CANCELLED = 2;
    const PAIDOFF = 3;
}