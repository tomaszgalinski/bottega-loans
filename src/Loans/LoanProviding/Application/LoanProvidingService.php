<?php

namespace Loans\LoanProviding\Application;

use Loans\LoanProviding\Application\Command\CancelLoan;
use Loans\LoanProviding\Application\Command\LoanRepay;
use Loans\LoanProviding\Application\Command\LoanRequest;
use Loans\LoanProviding\Domain\Loan;
use Loans\LoanProviding\Domain\LoanRepository;
use Loans\LoanProviding\Domain\LoanRequestVerifierFactory;
use Prooph\ServiceBus\EventBus;

class LoanProvidingService
{
    /**
     * @var LoanRepository
     */
    private $repository;

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @var LoanRequestVerifierFactory
     */
    private $verifierFactory;

    /**
     * LoanProvidingService constructor.
     * @param LoanRepository $repository
     * @param EventBus $eventBus
     * @param LoanRequestVerifierFactory $verifierFactory
     */
    public function __construct(LoanRepository $repository, EventBus $eventBus, LoanRequestVerifierFactory $verifierFactory)
    {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
        $this->verifierFactory = $verifierFactory;
    }

    public function requestForLoan(LoanRequest $loanRequest): ?Loan
    {
        $loanRequestVerifier = $this->verifierFactory->buildFor($loanRequest->customerId());

        if ($loanRequestVerifier->isOk(/*$customer, */$loanRequest->money(), $loanRequest->dateTo())) {
            $loan = Loan::init(
                $loanRequest->loanId(),
                $loanRequest->customerId(),
                $loanRequest->money(),
                $loanRequest->dateTo()
            );

            $this->repository->save($loan);

            return $loan;
        }

        // @todo
        $this->eventBus->dispatch(
            LoanRequestRejected::create(
                $loanRequest->loanId(),
                $loanRequest->customerId()
            )
        );
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