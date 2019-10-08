<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminated\Console\Loggable;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;


abstract class LoggedCommand extends Command
{
    use Loggable;

    protected function getNotificationRecipients()
    {
        return config('command.recipients');
    }

    protected function enableNotificationDeduplication()
    {
        return true;
    }

    protected function getNotificationDeduplicationTime()
    {
        return 90;
    }

    protected function guzzleClient(array $options = []) {
        $log = $this->icLogger();
        $handler = HandlerStack::create();
        $middleware = iclogger_guzzle_middleware($log);
        $handler->push($middleware);

        $options['handler'] = $handler;

        return new Client($options);
    }


}
