<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Str;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 't';

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

    public function handle()
    {
        dd(Str::plural('setting'));
        \DB::table('users')->insert([
            'name' => 'wangningkai',
            'email' => '1655586865@qq.com',
            'password' => bcrypt('123456'),
            'status' => 1
        ]);
    }
}
