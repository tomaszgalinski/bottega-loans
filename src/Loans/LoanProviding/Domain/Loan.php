<?php

namespace Loans\LoanProviding\Domain;

use Loans\LoanProviding\Domain\Event\LoanCancelled;
use Loans\LoanProviding\Domain\Event\LoanCreated;
use Loans\LoanProviding\Domain\Event\LoanPaidOff;
use Money\Money;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Ramsey\Uuid\UuidInterface;

class Loan extends AggregateRoot
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var UuidInterface
     */
    private $customerId;

    /**
     * @var LoanStatus
     */
    private $status;

    /**
     * @var Money
     */
    private $totalAmount;

    /**
     * @var Money
     */
    private $remainingAmount;

    /**
     * @var int
     */
    private $actions;

    /**
     * @var string
     */
    private $cancellationReason;

    /**
     * @var \DateTimeImmutable
     */
    private $dateTo;

    public static function init(UuidInterface $loanId, UuidInterface $customerId, Money $totalAmount, \DateTimeImmutable $dateTo)
    {
        $loan = new self();

        $loan->recordThat(
            LoanCreated::create($loanId, $customerId, $totalAmount, $dateTo)
        );

        return $loan;
    }

    public function payoff(Money $money)
    {
//        if (!$this->canUseService($money)) {
//
//        }
//        return $this->balance->greaterThan($money); // Prepaid
//        return true; // Postpaid
//        return przez_miesiac_mozesz_naparzac; // Time

        if ($this->status != LoanStatus::ACTIVE()) {
            throw new \LogicException();
        }

        // case nadplaty?
        // exception, saga...

        if ($money->lessThan($this->remainingAmount)) {
            $this->recordThat(
                LoanPaidOff::create($this->id, $money)
            );
        } elseif ($money->equals($this->remainingAmount)) {
            $this->recordThat(
                LoanFullyPaid::create($this->id, $this->remainingAmount)
            );
        } else {
            $this->recordThat(
                LoanOverPaid::create($this->id, $money->subtract($this->remainingAmount))
            );
        }
    }

    public function cancel(string $reason)
    {
        if (!$this->canBeCancelled()) {
            throw new \LogicException();
        }

        $this->recordThat(
            LoanCancelled::create($this->id, $reason)
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return UuidInterface
     */
    public function getCustomerId(): UuidInterface
    {
        return $this->customerId;
    }

    /**
     * @return Money
     */
    public function getTotalAmount(): Money
    {
        return $this->totalAmount;
    }

    /**
     * @return Money
     */
    public function getRemainingAmount(): Money
    {
        return $this->remainingAmount;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateTo(): \DateTimeImmutable
    {
        return $this->dateTo;
    }

    /**
     * @return LoanStatus
     */
    public function getStatus(): LoanStatus
    {
        return $this->status;
    }

    protected function aggregateId(): string
    {
        return (string)$this->id;
    }

    protected function apply(AggregateChanged $event): void
    {
        switch (get_class($event)) {
            case LoanCreated::class:
                /**
                 * @var $event LoanCreated
                 */
                $this->id = $event->id();
                $this->customerId = $event->customerId();
                $this->totalAmount = $event->totalAmount();
                $this->remainingAmount = $this->totalAmount;
                $this->dateTo = $event->dateTo();
                $this->status = LoanStatus::ACTIVE();
                $this->actions = 0;
                $this->cancellationReason = null;

                // odsetki?

                break;
            case LoanPaidOff::class:
                /**
                 * @var $event LoanPaidOff
                 */
                $this->remainingAmount = $this->remainingAmount->subtract($event->money());
                $this->actions += 1;

                break;
            case LoanCancelled::class:
                $this->status = LoanStatus::CANCELLED();
                $this->cancellationReason = $event->reason();
                $this->actions += 1;

                break;
        }
    }
    
    private function canBeCancelled(): bool
    {
        return $this->status == LoanStatus::ACTIVE();
    }
}