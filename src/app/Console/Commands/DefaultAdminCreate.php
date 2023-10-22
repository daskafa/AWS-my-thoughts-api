<?php

namespace App\Console\Commands;

use App\Constants\CommonConstants;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;

class DefaultAdminCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:default-admin-create {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a default admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (is_null($this->option('email'))) {
            $this->info('Please provide an email using the --email option.');

            return;
        }

        if (is_null($this->option('password'))) {
            $this->info('Please provide a password using the --password option.');

            return;
        }

        try {
            User::create([
                'role' => CommonConstants::DEFAULT_ADMIN_ROLE,
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => $this->option('email'),
                'password' => $this->option('password'),
            ]);

            $this->info('Default admin user created successfully.');
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
