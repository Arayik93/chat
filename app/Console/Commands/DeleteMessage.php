<?php

namespace App\Console\Commands;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle()
    {
        Message::where('created_at', '<', Carbon::yesterday())->delete();
    }
}
