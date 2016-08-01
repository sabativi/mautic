<?php

namespace Mautic\PageBundle\Service\Rabbitmq;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use Mautic\CoreBundle\Helper\TrackingPixelHelper;
use Symfony\Component\HttpFoundation\Response;
use PhpAmqpLib\Message\AMQPMessage;
use Mautic\PageBundle\Model\PageModel;
use Monolog\Logger;

class Consumer implements ConsumerInterface
{
   private $model;
   private $requestStack;

   public function __construct(PageModel $model)
   {
      $this->model = $model;
      echo "Consumer is listening!" . PHP_EOL;
   }

   public function execute(AMQPMessage $msg)
   {
     echo "Begin processing " . PHP_EOL;
      $message = unserialize($msg->body);
      $request = $message['request'];
      $this->model->hitPage(null, $request);
      echo "End processing " . PHP_EOL;
      echo TrackingPixelHelper::getResponse($request);
      return true;
   }
}