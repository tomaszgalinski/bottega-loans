<?php

namespace Loans\LoanProviding\Domain\LoanRequestVerifier;

use Loans\LoanProviding\Domain\LoanRequestVerifer;
use Money\Money;

class OrX implements LoanRequestVerifer
{
    /**
     * @var LoanRequestVerifer[]
     */
    private $verifiers;

    /**
     * AndX constructor.
     * @param LoanRequestVerifer[] $verifiers
     */
    public function __construct(array $verifiers)
    {
        $this->verifiers = $verifiers;
    }

    public function isOk(Money $money, \DateTimeImmutable $dateTo): bool
    {
        foreach ($this->verifiers as $verifer) {
            if ($verifer->isOk($money, $dateTo)) {
                return true;
            }
        }

        return false;
    }
}