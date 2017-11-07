<?php

use Loans\LoanProviding\Application\Command\LoanRequest;
use Loans\LoanProviding\Application\LoanProvidingService;
use Loans\LoanProviding\Domain\Event\LoanCreated;
use Loans\LoanProviding\Domain\Loan;
use Money\Money;
use Ramsey\Uuid\Uuid;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'setup.php';

$eventRouter
    ->route(LoanCreated::class)
    ->to(function(LoanCreated $event) {
        var_dump($event);
    });

/*
$eventRouter
    ->route(LoanOverPaid::class)
    ->to(function(LoanOverPaid $event) use ($commandBus) {
        $commandBus->dispatch(
            new SendMoneyBack($event->customerId(), $event->moneyToReturn())
        )
    });
$eventRouter
    ->route(LoanFullyPaid::class|MoneySentBack:class)
    ->to(function(LoanFullyPaid $event) use ($commandBus) {
        $commandBus->dispatch(
            new SendThankYou($event->customerId(), $event->moneyToReturn())
        )
    });
*/

$service = new LoanProvidingService($eventBasedLoanRepo);

$commandRouter
    ->route(LoanRequest::class)
    ->to(function (LoanRequest $command) use ($service) {
        $service->requestForLoan($command);
    });
// ->to(new LoanRequestHandler($service)); // __invoke(LoanRequest $command)

$loanId = Uuid::uuid4();
$customerId = Uuid::uuid4();
$totalAmount = Money::EUR(1000);
$dateTo = new \DateTimeImmutable('+7 days');

$commandBus->dispatch(new LoanRequest($loanId, $customerId, $totalAmount, $dateTo));
