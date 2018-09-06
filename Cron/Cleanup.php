<?php
declare(strict_types=1);

/**
 * @author Tjitse (Vendic)
 * Created on 06-09-18 11:14
 */

namespace Vendic\CleanCrons\Cron;

use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;

class Cleanup
{
    /**
     * @var ResourceConnection
     */
    protected $connection;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        ResourceConnection $connection,
        LoggerInterface $logger
    ) {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function execute()
    {
        $connection = $this->connection->getConnection();
        $sql = "DELETE FROM cron_schedule WHERE  scheduled_at < Date_sub(Now(), interval 1 hour);";

        try {
            $connection->query($sql);
            $this->logger->info('Database table cron_schedule cleaned');
        } catch (\Zend_Db_Statement_Exception $exception) {
            $this->logger->critical(sprintf('Cron cleanup error: %s', $exception->getMessage()));
        }

        return $this;
    }
}
