<?php

namespace Saas\Project\Dependencies\Cache;

interface CacheInterface
{
    public function get(string $key);
    public function set(string $key, $value, int $ttl = null);
    public function delete(string $key);
}