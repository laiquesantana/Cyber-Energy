<?php

namespace Saas\Project\Dependencies\Adapters\Logger;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Saas\Project\Dependencies\Adapters\Logger\Processors\ContextProcessor;
use Saas\Project\Dependencies\Adapters\Logger\Processors\DatetimeProcessor;
use Saas\Project\Dependencies\Interfaces\LogInterface;

class MonologLogAdapter implements LogInterface
{
    private Logger $log;
    private string $prefix;

    public function __construct(string $prefix = null)
    {
        $handler = new StreamHandler("php://stdout");
        $this->prefix = $prefix ?? '';
        $handler->setFormatter(new JsonFormatter(JsonFormatter::BATCH_MODE_JSON, true, true));
        $this->log = (new Logger('api_consult'))
            ->pushHandler($handler)
            ->pushProcessor(new DatetimeProcessor())
            ->pushProcessor(new ContextProcessor());
    }

    public function setPrefix(string $prefix): LogInterface
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function alert(string $message, array $context = []): LogInterface
    {
        $this->call('alert', sprintf('%s %s', $this->prefix, $message), $context);
        return $this;
    }

    public function debug(string $message, array $context = []): LogInterface
    {
        $this->call('debug', sprintf('%s %s', $this->prefix, $message), $context);
        return $this;
    }

    public function emergency(string $message, array $context = []): LogInterface
    {
        $this->call('emergency', sprintf('%s %s', $this->prefix, $message), $context);
        return $this;
    }

    public function error(string $message, array $context = []): LogInterface
    {
        $this->call('error', sprintf('%s %s', $this->prefix, $message), $context);
        return $this;
    }

    public function info(string $message, array $context = []): LogInterface
    {
        $this->call('info', sprintf('%s %s', $this->prefix, $message), $context);
        return $this;
    }

    public function notice(string $message, array $context = []): LogInterface
    {
        $this->call('notice', sprintf('%s %s', $this->prefix, $message), $context);
        return $this;
    }

    public function warning(string $message, array $context = []): LogInterface
    {
        $this->call('warning', sprintf('%s %s', $this->prefix, $message), $context);
        return $this;
    }

    public function call(string $level, string $message, array $context): void
    {
        if (array_key_exists('exception', $context) && $context['exception']) {
            $exception = $context['exception'];
            $context['exception'] = get_class($exception);
            $context['message'] = $exception->getMessage();
            if ($exception->getPrevious()) {
                $context['previous'] = [
                    'exception' => get_class($exception->getPrevious()),
                    'message' => $exception->getPrevious()->getMessage()
                ];
            }
        }
        $this->log->$level($message, $context);
    }
}
