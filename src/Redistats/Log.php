<?php

namespace Redistats;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Formatter\LineFormatter;

class Log {

    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;

    /**
     * Magic static call
     *
     * @return void
     */
    public static function __callStatic($name, $arguments) {
        if (!empty($arguments)) $message = array_shift($arguments);
        $name = strtoupper($name);
        $level = $arguments[0];

        $output = "[%datetime%][%extra%] %channel%.%level_name%: %message% \n";
        $formatter = new LineFormatter($output);
        
        $logPath = __DIR__ . '/../../log/';
        if (!file_exists($logPath)) mkdir($logPath);

        $file = new StreamHandler($logPath . 'monitoring.log');
        $file->setFormatter($formatter);
        
        $console = new StreamHandler('php://stdout');
        $console->setFormatter($formatter);

        $log = new Logger($name);
        $log->pushHandler($file);
        $log->pushHandler($console);
        $log->pushProcessor(new MemoryUsageProcessor());

        $log->addRecord($level, $message);
    }
}