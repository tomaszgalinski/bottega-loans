<?php

namespace Loans\LoanProviding\Domain\Event;

use Money\Currency;
use Money\Money;
use Prooph\EventSourcing\AggregateChanged;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class LoanPaidOff extends AggregateChanged
{
    public static function create
    (
        UuidInterface $id,
        Money $money
    )
    {
        return static::occur(
            (string)$id,
            [
                'amount' => $money->getAmount(),
                'currency' => $money->getCurrency()->getCode(),
            ]
        );
    }

    public function id()
    {
        return Uuid::fromString($this->aggregateId());
    }

    public function money(): Money
    {
        return new Money(
            $this->payload['amount'],
            new Currency($this->payload['currency'])
        );
    }
}