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
    ->route(LoanRequestAccepted::class)
    ->to(function(LoanRequestAccepted $event) use ($commandBus) {
        $commandBus->dispatch(
            new LoanRequest(...)
        )
    });
$eventRouter
    ->route(LoanRequestRejected::class)
    ->to(function(LoanRequestRejected $event) use ($commandBus) {
        $commandBus->dispatch(
            new SentSorryEmail(...)
        )
    });
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

$factory = new \Loans\LoanProviding\Domain\LoanRequestVerifierFactory();
$service = new LoanProvidingService($eventBasedLoanRepo, $eventBus, $factory);

$commandRouter
    ->route(LoanRequest::class)
    ->to(function (LoanRequest $command) use ($service) {
        $service->requestForLoan($command);
    });
// ->to(new LoanRequestHandler($service)); // __invoke(LoanRequest $command)

// $idGenerator->generate(); // AutoIncrGenerator, UUIDGenerator
// php -r 'var_dump(bcadd(time() << 32, rand(0, bcsub(bcpow(2, 32), 1))) >> 32);'
$loanId = Uuid::uuid4();
$customerId = Uuid::uuid4();
$totalAmount = Money::EUR(1000);
$dateTo = new \DateTimeImmutable('+25 days');

$commandBus->dispatch(new LoanRequest($loanId, $customerId, $totalAmount, $dateTo));

$loan = $eventBasedLoanRepo->get($loanId);
$loan->payoff(Money::EUR(10));
$loan->payoff(Money::EUR(10));
$loan->payoff(Money::EUR(10));
$eventBasedLoanRepo->save($loan);

// Concurrency scenario
exit;

$loanA = $eventBasedLoanRepo->get(Uuid::fromString('5d4d49ff-1e76-4573-8d05-e755532eeb46'));
$loanB = clone $loanA;

$loanA->payoff(Money::EUR(20));
$eventBasedLoanRepo->save($loanA);

$loanB->cancel('test');
$eventBasedLoanRepo->save($loanB);