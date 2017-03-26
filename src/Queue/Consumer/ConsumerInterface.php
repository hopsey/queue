<?php
/**
 * Created by PhpStorm.
 * User: tomaszchmielewski
 * Date: 27/12/16
 * Time: 17:14
 */

namespace Queue\Consumer;

use Psr\Log\LoggerAwareInterface;

interface ConsumerInterface extends LoggerAwareInterface
{
    public function runConsumingLoop();
}