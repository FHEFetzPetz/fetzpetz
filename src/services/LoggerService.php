<?php

namespace App\FetzPetz\Services;

use App\FetzPetz\Core\Service;
use App\FetzPetz\Kernel;

class LoggerService extends Service
{

    private $logLevels = [
        'info' => 0,
        'debug' => 1
    ];

    public function __construct(Kernel $kernel)
    {
        parent::__construct($kernel);
    }

    private function getLogConfig() : array {
        return $this->kernel->getConfig()['log'];
    }

    /**
     * determines if the message level is "important"
     * enough for logging
     *
     * @param string $level
     * @return bool
     */
    private function isMatchingLevel(string $level) {
        $logLevel = $this->logLevels[$level] ?? -1;
        $maximumLevel = $this->logLevels[$this->getLogConfig()['level']] ?? -1;

        return $logLevel <= $maximumLevel;
    }

    /**
     * Returns message prefix with datetime and level
     *
     * @param string $level
     * @return string
     */
    private function getMessagePrefix(string $level): string {
        return '[' . (new \DateTime())->format('d.m.Y H:i:s') . '] [' . $level . ']';
    }

    /**
     * Logs a message in the log-file
     *
     * @param string $message
     * @param string $level
     * @param string|null $logFile
     */
    public function log(string $message, string $level = 'debug', string $logFile = null) {
        if(!$this->isMatchingLevel($level)) return;
        if($logFile == null) $logFile = $this->getLogConfig()['filename'];

        file_put_contents(
            $this->kernel->getAppDir() . '/' . $this->getLogConfig()['logDirectory'] . '/' . $logFile . '.log',
            $this->getMessagePrefix($level) . ' ' . $message . "\n",
            FILE_APPEND
        );
    }

}