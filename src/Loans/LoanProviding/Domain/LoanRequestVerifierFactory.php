<?php

namespace Loans\LoanProviding\Domain;

use Loans\LoanProviding\Domain\LoanRequestVerifier\Always;
use Loans\LoanProviding\Domain\LoanRequestVerifier\AndX;
use Loans\LoanProviding\Domain\LoanRequestVerifier\LoanPeriodLessThan;
use Loans\LoanProviding\Domain\LoanRequestVerifier\Never;
use Loans\LoanProviding\Domain\LoanRequestVerifier\RequestMoneyLessThan;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

class LoanRequestVerifierFactory
{
    public function buildFor(UuidInterface $customerId)
    {
//        if (is_weekend) {
//            return new Never();
//        }

        return new AndX([
            new RequestMoneyLessThan(Money::EUR(10000)),
            new LoanPeriodLessThan(new \DateInterval('P1M'))
        ]);

        //return new Always();
    }
}