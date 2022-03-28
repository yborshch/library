<?php


namespace App\Sockets\Pusher;


use ZMQ;
use ZMQContext;

class Pusher extends BasePusher
{
    /**
     * @param array $data
     * @throws \ZMQSocketException
     */
    public static function sendDataToServer(array $data)
    {
        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect(env('PUSHER_DSN'));

        $socket->send(json_encode($data));
    }

    public function broadcast(string $jsonData)
    {
        $entryData = json_decode($jsonData, true);
        $subscribedTopics = $this->getSubscribedTopics();

        if (isset($subscribedTopics[$entryData['book_topic']])) {
            $topic = $subscribedTopics[$entryData['book_topic']];
            $topic->broadcast($entryData);
        }
    }
}
