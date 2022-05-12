<?php

namespace App\Console\Commands;

use App\Jobs\Watch\MessageType\Preparation;
use App\Jobs\Watch\WatchAuthorsJob;
use Illuminate\Console\Command;

class WatchAuthors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watch:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command run check from sites a new books. Authors saved in database';

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
     */
    public function handle(): int
    {
        dispatch((new WatchAuthorsJob(new Preparation()))->onQueue('watch'));

        return Command::SUCCESS;
    }
}
