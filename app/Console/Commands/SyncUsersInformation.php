<?php

namespace App\Console\Commands;

use App\Jobs\Github\SyncUserInformation;
use App\Models\User;
use Illuminate\Console\Command;

class SyncUsersInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:users-information';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'github:users-information';

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
    public function handle(User $user)
    {
        $users = $user->all();

        foreach ($users as $user) {
            SyncUserInformation::dispatch($user->oauthProvider);
        }
    }
}
