<?php

namespace Loans\LoanProviding\Domain\LoanRequestVerifier;

use Loans\LoanProviding\Domain\LoanRequestVerifer;
use Money\Money;

class RequestMoneyLessThan implements LoanRequestVerifer
{
    /**
     * @var \Money\Money
     */
    private $threshold;

    /**
     * RequestMoneyLessThan constructor.
     * @param \Money\Money $threshold
     */
    public function __construct(\Money\Money $threshold)
    {
        $this->threshold = $threshold;
    }

    public function isOk(Money $money, \DateTimeImmutable $dateTo): bool
    {
        return $money->lessThan($this->threshold);
    }
}