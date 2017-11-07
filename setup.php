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
    'dbname' => 'workshop_5',
    'user' => 'root',
    'password' => 'password',
    'host' => '54.77.15.1',
    'port' => 3306,
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

$eventBus = new \Prooph\ServiceBus\EventBus();
$eventRouter = new \Prooph\ServiceBus\Plugin\Router\EventRouter();
$eventPublisher = new \Prooph\EventStoreBusBridge\EventPublisher($eventBus);

// Setup repozytorium agregatów

$eventStore = new MySqlEventStore(
    new \Prooph\Common\Messaging\FQCNMessageFactory(),
    $connection->getWrappedConnection(),
    new \Prooph\EventStore\Pdo\PersistenceStrategy\MySqlSingleStreamStrategy()
);
//$eventStore = new

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