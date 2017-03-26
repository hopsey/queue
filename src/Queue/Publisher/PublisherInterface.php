<?php
/**
 * Created by PhpStorm.
 * User: tomaszchmielewski
 * Date: 27/12/16
 * Time: 17:14
 */

namespace Queue\Publisher;

use Psr\Log\LoggerAwareInterface;
use Queue\Message\MessageInterface;

/**
 * Interfejs do publikowania wiadomosci
 * Interface PublisherInterface
 * @package Queue\Publisher
 */
interface PublisherInterface
{
    public function publish(MessageInterface $message);
}