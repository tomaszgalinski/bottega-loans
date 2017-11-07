<?php

use Loans\LoanProviding\Domain\Event\LoanCreated;
use Loans\LoanProviding\Domain\Event\LoanPaidOff;
use Loans\LoanProviding\Domain\LoanStatus;

require_once 'setup.php';

 $projection = $projectionManager->createProjection('read_loans');

 $projection
     ->fromAll()
     ->whenAny(function($state, $event) use ($connection) {
         switch (get_class($event)) {
             case LoanCreated::class:
                 /**
                  * @var $event LoanCreated
                  */
                 $stmt = $connection->prepare(
                     'INSERT INTO read_loans 
                               (id, amount, remaining, currency, status, created, operations)
                               VALUES (:id, :amount, :remaining, :currency, :status, :created, :operations)'
                 );

                 $stmt->execute([
                     ':id' => (string)$event->id(),
                     ':amount' => $event->totalAmount()->getAmount(),
                     ':remaining' => $event->totalAmount()->getAmount(),
                     ':currency' => $event->totalAmount()->getCurrency()->getCode(),
                     ':status' => LoanStatus::ACTIVE,
                     ':created' => $event->createdAt()->format('Y-m-d H:i:s'),
                     ':operations' => 0,
                 ]);

                 break;
             case LoanPaidOff::class:
                 /**
                  * @var $event LoanPaidOff
                  */
                 $stmt = $connection->prepare(
                     'UPDATE read_loans 
                               SET 
                                 remaining = remaining - :amount,
                                 updated = :updated,
                                 operations = operations + 1
                               WHERE id = :id'
                 );

                 $stmt->execute([
                     ':id' => (string)$event->id(),
                     ':amount' => $event->money()->getAmount(),
                     ':updated' => $event->createdAt()->format('Y-m-d H:i:s'),
                 ]);

                 break;
         }
     })
     ->run(false);