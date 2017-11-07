<?php

namespace Loans\LoanProviding\Application;

use Loans\LoanProviding\Application\Command\CancelLoan;
use Loans\LoanProviding\Application\Command\LoanRepay;
use Loans\LoanProviding\Application\Command\LoanRequest;

class LoanProvidingService
{
    public function requestForLoan(LoanRequest $loanRequest): ?Loan
    {

    }

    public function repayLoan(LoanRepay $loanRepay): void
    {

    }

//    public function repayLoanWithLoan()
//    {
//
//    }

    public function cancelLoan(CancelLoan $cancelLoan): void
    {

    }
}

// zamrozenie trafi do innego serwisu / kontekstu