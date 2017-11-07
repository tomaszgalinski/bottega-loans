<?php

use Loans\LoanProviding\Domain\Loan;
use Money\Money;
use Ramsey\Uuid\Uuid;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'setup.php';

$loanId = Uuid::uuid4();
$customerId = Uuid::uuid4();
$totalAmount = Money::EUR(1000);
$dateTo = new \DateTimeImmutable('+7 days');

$loan = $eventBasedLoanRepo->get(Uuid::fromString('4dbff8ee-d607-4bec-b329-45cc45d2e72c'));
//$loan = Loan::init($loanId, $customerId, $totalAmount, $dateTo);
//
//$loan->payoff(Money::EUR(10));
//$loan->payoff(Money::EUR(20));
//$loan->payoff(Money::EUR(30));
//
//$eventBasedLoanRepo->save($loan);

//$loan->cancel('bo tak');

//$eventBasedLoanRepo->save($loan);

var_dump($loan);