<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AppStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start task demo app';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Start task app for jabu');
        $this->info('--------------------------');

        $this->info('Runinng migrations and seed');
        $this->callSilently('migrate:fresh');
        $this->callSilently('db:seed');
        $this->call('tasks:process-tasks-counts');

        // SHow user account
        $this->newLine();
        $this->info('User account:');

        $user = User::first();
        $this->comment('Email: '. $user->email);
        $this->comment('Pass: givemeFive');


        return 0;
    }
}
