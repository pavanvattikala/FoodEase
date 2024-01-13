<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ServeWithLocalIP extends Command
{
    protected $signature = 'serve:local';
    protected $description = 'Run the Laravel development server with a static local IP';

    public function handle()
    {
        $localIP = "192.168.29.57";

        $this->info("Running 'php artisan serve' with local Wi-Fi IP: $localIP");

        // Run the artisan serve command
        exec("php artisan serve --host=$localIP --port=80");
    }
}
