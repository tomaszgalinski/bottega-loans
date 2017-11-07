<?php

namespace Loans\LoanProviding\Application\Command;

use Money\Currency;
use Money\Money;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class LoanRepay extends Command
{
    use PayloadTrait;

    /**
     * LoanRequest constructor.
     */
    public function __construct(UuidInterface $loanId, Money $money)
    {
        $this->init();
        $this->setPayload([
            'loan_id' => (string)$loanId,
            'amount' => $money->getAmount(),
            'currency' => $money->getCurrency()->getCode(),
        ]);
    }

    public function loanId(): UuidInterface
    {
        return Uuid::fromString($this->payload['loan_id']);
    }

    public function money(): Money
    {
        return new Money(
            $this->payload['amount'],
            new Currency($this->payload['currency'])
        );
    }
}