<?php
/**
 * Created by PhpStorm.
 * User: tomaszchmielewski
 * Date: 29/12/16
 * Time: 10:59
 */

namespace Queue;


use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Queue\Consumer\ConsumerInterface;
use Queue\Message\MessageInterface;
use Queue\Publisher\PublisherInterface;

/**
 * Class Queue
 * @package Queue
 */
class Queue implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var ConsumerInterface
     */
    private $consumer;

    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * Queue constructor.
     * @param ConsumerInterface $consumer
     * @param PublisherInterface $publisher
     */
    public function __construct(ConsumerInterface $consumer, PublisherInterface $publisher, LoggerInterface $logger)
    {
        $this->consumer = $consumer;
        $this->publisher = $publisher;
        $this->logger = $logger;
    }

    /**
     * RUNS consumer loop protects error triggering breaks
     */
    public function runConsumerLoop()
    {
        $this->logger->info("Starting consumer loop");

        while (true) {
            try {
                $this->logger->info("Entering the loop");
                $this->consumer->runConsumingLoop();
            } catch (\Throwable $throwable) {
                $message = $throwable->getMessage()
                    . "\n\n" . $throwable->getTraceAsString();
                $this->logger->error($message);
            }
        }
    }

    /**
     * @param MessageInterface $message
     */
    public function publishMessage(MessageInterface $message)
    {
        $this->publisher->publish($message);
    }
}