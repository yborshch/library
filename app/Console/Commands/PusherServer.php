<?php

namespace App\Console\Commands;

use App\Sockets\Pusher\Pusher;
use Illuminate\Console\Command;
use Ratchet\Server\IoServer;
use React\EventLoop\Factory as ReactLoop;
use React\ZMQ\Context as ReactContext;
use React\Socket\Server as ReactServer;
use Ratchet\Http\HttpServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use ZMQ;
use ZMQSocketException;

class PusherServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pusher:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for run pusher serve';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws ZMQSocketException
     */
    public function handle(): int
    {
        $loop   = ReactLoop::create();
        $pusher = new Pusher;

        $context = new ReactContext($loop);
        $pull = $context->getSocket(ZMQ::SOCKET_PULL);
        $pull->bind(env('PUSHER_DSN'));
        $pull->on('message', array($pusher, 'onBlogEntry'));

        $webSock = new ReactServer('0.0.0.0:8080', $loop);
        new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $pusher
                    )
                )
            ),
            $webSock
        );

        $this->info('Run handle');
        $loop->run();

        return self::SUCCESS;
    }
}
