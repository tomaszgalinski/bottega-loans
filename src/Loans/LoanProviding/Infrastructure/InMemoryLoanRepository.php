<?php

namespace Loans\LoanProviding\Infrastructure;

use Loans\LoanProviding\Domain\Loan;
use Loans\LoanProviding\Domain\LoanRepository;
use Ramsey\Uuid\UuidInterface;

class InMemoryLoanRepository implements LoanRepository
{
    private $loans = [];

    public function get(UuidInterface $id): Loan
    {
        if (!array_key_exists((string)$id, $this->loans)) {
            throw new \InvalidArgumentException();
        }

        return $this->loans[(string)$id];
    }

    public function save(Loan $loan): void
    {
        $this->loans[(string)$loan->getId()] = $loan;
    }
}