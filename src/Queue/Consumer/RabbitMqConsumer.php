<?php
/**
 * Created by PhpStorm.
 * User: tomaszchmielewski
 * Date: 27/12/16
 * Time: 17:23
 */

namespace Queue\Consumer;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Log\LoggerAwareTrait;
use Queue\Message\MessageInterface;
use Queue\MessageHandler\MessageResolver;

class RabbitMqConsumer implements ConsumerInterface
{
    use LoggerAwareTrait;

    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var string
     */
    private $channelName;

    /**
     * @var MessageResolver
     */
    private $handlerBroker;

    public function __construct(AMQPStreamConnection $connection, MessageResolver $handlerBroker, $channelName)
    {
        $this->connection = $connection;
        $this->handlerBroker = $handlerBroker;
        $this->channelName = $channelName;
    }

    public function runConsumingLoop()
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($this->channelName, false, false, false, false);

        $callback = function($msg) {
            /** @var MessageInterface $message */
            $message = unserialize($msg->body);

            $this->logger->info("New message received [" . get_class($message) . "]");

            if (!$message instanceof MessageInterface) {
                throw new \UnexpectedValueException("Invalid message supplied");
            }

            try {
                $this->handlerBroker->handleMessage($message);
            } catch (\Throwable $throwable) {
                // Obsługa wyjątków powinna być piętro wyżej w Queue, ale PHP pierdzi gdy wyjątki
                // rzucane są z poziomu infinite loop. :(
                $this->logger->error($throwable->getMessage() . "\n\n" . $throwable->getTraceAsString());
            }
        };

        $channel->basic_consume($this->channelName, '', false, true, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();

        $this->connection->close();
    }
}