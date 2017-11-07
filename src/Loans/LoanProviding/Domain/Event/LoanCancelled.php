<?php

namespace Loans\LoanProviding\Domain\Event;

use Money\Currency;
use Money\Money;
use Prooph\EventSourcing\AggregateChanged;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class LoanCancelled extends AggregateChanged
{
    public static function create
    (
        UuidInterface $id,
        string $reason
    )
    {
        return static::occur(
            (string)$id,
            [
                'reason' => $reason
            ]
        );
    }

    public function id()
    {
        return Uuid::fromString($this->aggregateId());
    }

    public function reason()
    {
        return $this->payload['reason'];
    }
}