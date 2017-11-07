<?php

namespace Loans\LoanProviding\Domain\LoanRequestVerifier;

use Loans\LoanProviding\Domain\LoanRequestVerifer;
use Money\Money;

class LoanPeriodLessThan implements LoanRequestVerifer
{
    /**
     * @var \DateInterval
     */
    private $interval;

    /**
     * LoanPeriodLessThan constructor.
     * @param \DateInterval $interval
     */
    public function __construct(\DateInterval $interval)
    {
        $this->interval = $interval;
    }

    public function isOk(Money $money, \DateTimeImmutable $dateTo): bool
    {
        return $dateTo < (new \DateTime())->add($this->interval);
    }
}