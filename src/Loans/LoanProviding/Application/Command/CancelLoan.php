<?php

namespace Loans\LoanProviding\Application\Command;

use Money\Money;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CancelLoan extends Command
{
    use PayloadTrait;

    /**
     * LoanRequest constructor.
     *
     * + adminId ?
     */
    public function __construct(UuidInterface $loanId, string $reason)
    {
        $this->init();
        $this->setPayload([
            'loan_id' => (string)$loanId,
            'reason' => $reason,
        ]);
    }

    public function loanId(): UuidInterface
    {
        return Uuid::fromString($this->payload['loan_id']);
    }

    public function reason(): string
    {
        return $this->payload['reason'];
    }
}