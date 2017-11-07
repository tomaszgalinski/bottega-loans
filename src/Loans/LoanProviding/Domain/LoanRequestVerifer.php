<?php

namespace Loans\LoanProviding\Domain;

use Money\Money;

interface LoanRequestVerifer
{
    public function isOk(Money $money, \DateTimeImmutable $dateTo): bool;
}