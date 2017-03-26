<?php
/**
 * Created by PhpStorm.
 * User: tomaszchmielewski
 * Date: 27/12/16
 * Time: 17:16
 */

namespace Queue\MessageHandler;

use Psr\Log\LoggerAwareInterface;
use Queue\Message\MessageInterface;

/**
 * Interfejs do obsługi wiadomości z QUEUE
 * Interface MessageHandlerInterface
 * @package Queue\MessageHandler
 */
interface MessageHandlerInterface extends LoggerAwareInterface
{
    /**
     * @param MessageInterface $message
     * @return mixed
     */
    public function handle(MessageInterface $message);
}