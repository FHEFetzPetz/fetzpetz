<?php


class Logger extends Handler
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

    private function isMatchingLevel(string $level) {
        $logLevel = $this->logLevels[$level] ?? -1;
        $maximumLevel = $this->logLevels[$this->getLogConfig()['level']] ?? -1;

        return $logLevel <= $maximumLevel;
    }

    private function getMessagePrefix(string $level): string {
        return '[' . (new DateTime())->format('d.m.Y H:i:s') . '] [' . $level . ']';
    }

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