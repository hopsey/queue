<?php
/**
 * Created by PhpStorm.
 * User: tomaszchmielewski
 * Date: 29/12/16
 * Time: 10:02
 */

namespace Queue\Publisher;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Queue\Message\MessageInterface;

class RabbitMqPublisher implements PublisherInterface
{

    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var string
     */
    private $channelName;

    /**
     * RabbitMqPublisher constructor.
     * @param AMQPStreamConnection $connection
     * @param $channelName
     */
    public function __construct(AMQPStreamConnection $connection, $channelName)
    {
        $this->connection = $connection;
        $this->channelName = $channelName;
    }

    public function publish(MessageInterface $message)
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($this->channelName, false, false, false, false);

        $msg = new AMQPMessage(serialize($message));
        $channel->basic_publish($msg, '', $this->channelName);

        $channel->close();
        $this->connection->close();
    }
}