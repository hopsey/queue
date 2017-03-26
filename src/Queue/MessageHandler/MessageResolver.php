<?php
/**
 * Created by PhpStorm.
 * User: tomaszchmielewski
 * Date: 27/12/16
 * Time: 17:17
 */

namespace Queue\MessageHandler;


use Psr\Log\LoggerAwareTrait;
use Queue\Message\MessageInterface;

/**
 * Klasa przechowywuje HANDLERy dla QUEUEs
 * Class MessageHandlerStorage
 * @package Application\Service\Queue\MessageHandler
 */
class MessageResolver
{

    private $handlers = [];

    /**
     * Zwraca handler
     * @param MessageInterface $message
     * @return MessageHandlerInterface
     */
    public function getHandlerForMessage(MessageInterface $message): MessageHandlerInterface
    {
        if (!array_key_exists($handlerName = get_class($message), $this->handlers)) {
            throw new \DomainException("Handler " . $handlerName . " not registered");
        }

        return $this->handlers[$handlerName];
    }

    /**
     * Rejestracja handlera
     * @param $messageClass
     * @param MessageHandlerInterface $handler
     */
    public function registerHandler($messageClass, MessageHandlerInterface $handler)
    {
        $this->handlers[$messageClass] = $handler;
    }

    /**
     * Egzekucja zadania
     * @param MessageInterface $message
     * @return mixed
     */
    public function handleMessage(MessageInterface $message)
    {
        $handler = $this->getHandlerForMessage($message);
        return $handler->handle($message);
    }
}