<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install App';

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
     * @return mixed
     */
    public function handle()
    {
        $lockFile = install_path('install.lock');
        if (file_exists($lockFile)) {
            $this->warn('Already Installed!');
            exit;
        }

        $envSampleFile = base_path('.env.example');
        $envFile = base_path('.env');
        if (!file_exists($envSampleFile)) {
            $this->warn('[.env.example] file missing,Please make sure the project complete!');
            exit;
        }
        $_search = [
            'APP_KEY=',
        ];
        $_replace = [
            'APP_KEY=' . str_random(32),
        ];
        $envExample = file_get_contents($envSampleFile);
        $env = str_replace($_search, $_replace, $envExample);
        if (file_exists($envFile)) {
            if ($this->confirm('Already have [.env] ,overwrite?')) {
                @unlink($envFile);
                file_put_contents($envFile, $env);
            }
        } else {
            file_put_contents($envFile, $env);
        }

        $this->call('config:cache');

        $sqlFile = install_path('data/database.sqlite');
        $sqlSampleFile = install_path('data/database.sample.sqlite');
        if (!file_exists($sqlSampleFile)) {
            $this->warn('[database.sample.sqlite] file missing,Please make sure the project complete!');
            exit;
        }
        if (!file_exists($sqlFile)) {
            $this->warn('Database not found,Creating...');
            copy($sqlSampleFile, $sqlFile);
        }
        $this->call('jwt:secret');
        $this->call('migrate');
        $this->call('db:seed');
        file_put_contents($lockFile, '');
        $this->call('config:cache');
        $this->info('Install Complete');
    }
}
