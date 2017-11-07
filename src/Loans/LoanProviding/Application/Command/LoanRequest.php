<?php

namespace Loans\LoanProviding\Application\Command;

use Money\Currency;
use Money\Money;
use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadTrait;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class LoanRequest extends Command
{
    use PayloadTrait;

    /**
     * LoanRequest constructor.
     */
    public function __construct(UuidInterface $customerId, Money $money, \DateTimeImmutable $dateTo)
    {
        $this->init();
        $this->setPayload([
            'customer_id' => (string)$customerId,
            'amount' => $money->getAmount(),
            'currency' => $money->getCurrency()->getCode(),
            'date_to' => $dateTo
        ]);
    }

    public function customerId(): UuidInterface
    {
        return Uuid::fromString($this->payload['customer_id']);
    }

    public function money(): Money
    {
        return new Money(
            $this->payload['amount'],
            new Currency($this->payload['currency'])
        );
    }

    // z dokladnoscia do serializacji...
    public function dateTo(): \DateTimeImmutable
    {
        return $this->payload['date_to'];
    }
}