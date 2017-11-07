<?php

namespace Loans\LoanProviding\Domain\LoanRequestVerifier;

use Loans\LoanProviding\Domain\LoanRequestVerifer;
use Money\Money;

class Always implements LoanRequestVerifer
{
    public function isOk(Money $money, \DateTimeImmutable $dateTo): bool
    {
        return true;
    }
}