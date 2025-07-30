<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ServeWithLocalIP extends Command
{
    protected $signature = 'serve:local';
    protected $description = 'Run the Laravel development server on the local network IP.';

    public function handle()
    {
        $localIP = $this->getLocalIp();

        if (!$localIP) {
            $this->error("Could not determine local IP address. Please ensure you're connected to a network.");
            return 1;
        }

        $port = 7777;
        $this->info("Starting server on: http://{$localIP}:{$port}");
        $this->info("Press CTRL+C to stop the server.");

        passthru("php artisan serve --host={$localIP} --port={$port}");

        return 0;
    }

    private function getLocalIp(): ?string
    {
        // On Windows, running 'ipconfig' and parsing the output is the most reliable method.
        $process = new Process(['ipconfig']);
        $process->run();

        if (!$process->isSuccessful()) {
            return null;
        }

        $output = $process->getOutput();
        $wifiIp = null;
        $ethernetIp = null;

        $lines = explode("\n", $output);
        $adapterContext = '';

        foreach ($lines as $line) {
            // Check for adapter names
            if (preg_match('/^(Wireless LAN adapter Wi-Fi|Ethernet adapter Ethernet)/', $line, $matches)) {
                $adapterContext = trim($matches[0]);
            }

            // Look for the IPv4 Address line within the current adapter's context
            if (strpos($line, 'IPv4 Address') !== false) {
                $ip = trim(substr($line, strpos($line, ':') + 1));

                if (strpos($adapterContext, 'Wi-Fi') !== false) {
                    $wifiIp = $ip;
                    break; // Prioritize Wi-Fi
                }

                if (strpos($adapterContext, 'Ethernet') !== false && !$ethernetIp) {
                    $ethernetIp = $ip;
                }
            }
        }

        return $wifiIp ?: $ethernetIp;
    }
}
