<?php

namespace Saas\Project\Dependencies\Interfaces;

interface LogInterface
{
    public function setPrefix(string $prefix): LogInterface;

    public function alert(string $message, array $context = []): LogInterface;

    public function debug(string $message, array $context = []): LogInterface;

    public function emergency(string $message, array $context = []): LogInterface;

    public function error(string $message, array $context = []): LogInterface;

    public function info(string $message, array $context = []): LogInterface;

    public function notice(string $message, array $context = []): LogInterface;

    public function warning(string $message, array $context = []): LogInterface;
}
