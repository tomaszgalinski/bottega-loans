<?php

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Loans\LoanProviding\Domain\Loan;
use Loans\LoanProviding\Infrastructure\EventBasedLoanRepository;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventStore\Pdo\MySqlEventStore;

require_once __DIR__ . '/vendor/autoload.php';

$config = new Configuration();

$connectionParams = [
    'dbname' => 'getresponse_loans',
    'user' => 'root',
    'password' => 'root',
    'host' => '127.0.0.1',
    'port' => 32768,
    'driver' => 'pdo_mysql',
];

$connection = DriverManager::getConnection($connectionParams, $config);

// Setup tabel
// Normalnie do migracji z tym...
try {
    $file = __DIR__ . '/vendor/prooph/pdo-event-store/scripts/mysql/01_event_streams_table.sql';
    $stmt = $connection->prepare(\file_get_contents($file));
    $stmt->execute();
} catch (\Exception $e) { }

try {
    $file = __DIR__ . '/vendor/prooph/pdo-event-store/scripts/mysql/02_projections_table.sql';
    $stmt = $connection->prepare(\file_get_contents($file));
    $stmt->execute();
} catch (\Exception $e) { }

// Setup event busa
$eventBus = new \Prooph\ServiceBus\EventBus();
$eventRouter = new \Prooph\ServiceBus\Plugin\Router\EventRouter();
$eventPublisher = new \Prooph\EventStoreBusBridge\EventPublisher($eventBus);

$eventRouter->attachToMessageBus($eventBus);

// Setup command busa
$commandBus = new \Prooph\ServiceBus\CommandBus();
$commandRouter = new \Prooph\ServiceBus\Plugin\Router\CommandRouter();

$commandRouter->attachToMessageBus($commandBus);

// Setup repozytorium agregatów
$eventStore = new MySqlEventStore(
    new \Prooph\Common\Messaging\FQCNMessageFactory(),
    $connection->getWrappedConnection(),
    new \Prooph\EventStore\Pdo\PersistenceStrategy\MySqlSingleStreamStrategy()
);

$eventStore = new \Prooph\EventStore\ActionEventEmitterEventStore(
    $eventStore,
    new \Prooph\Common\Event\ProophActionEventEmitter()
);
$eventPublisher->attachToEventStore($eventStore);

$streamName = new \Prooph\EventStore\StreamName('event_stream');
$singleStream = new \Prooph\EventStore\Stream($streamName, new ArrayIterator());

if (!$eventStore->hasStream($streamName)) {
    $eventStore->create($singleStream);
}

$aggregateRepository = new \Prooph\EventSourcing\Aggregate\AggregateRepository(
    $eventStore,
    AggregateType::fromAggregateRootClass(Loan::class),
    new \Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator()
);

$eventBasedLoanRepo = new EventBasedLoanRepository($aggregateRepository);
$projectionManager = new \Prooph\EventStore\Pdo\Projection\MySqlProjectionManager(
    $eventStore,
    $connection->getWrappedConnection()
);



//