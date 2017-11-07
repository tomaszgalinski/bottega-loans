<?php

namespace Loans\LoanProviding\Application;

use Loans\LoanProviding\Application\Command\CancelLoan;
use Loans\LoanProviding\Application\Command\LoanRepay;
use Loans\LoanProviding\Application\Command\LoanRequest;
use Loans\LoanProviding\Domain\Loan;
use Loans\LoanProviding\Domain\LoanRepository;

class LoanProvidingService
{
    /**
     * @var LoanRepository
     */
    private $repository;

    /**
     * LoanProvidingService constructor.
     * @param LoanRepository $repository
     */
    public function __construct(LoanRepository $repository)
    {
        $this->repository = $repository;
    }

    public function requestForLoan(LoanRequest $loanRequest): ?Loan
    {
        $loan = Loan::init(
            $loanRequest->loanId(),
            $loanRequest->customerId(),
            $loanRequest->money(),
            $loanRequest->dateTo()
        );

        $this->repository->save($loan);

        return $loan;
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