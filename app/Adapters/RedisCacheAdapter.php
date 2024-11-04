<?php
namespace App\Adapters;

use Saas\Project\Dependencies\Cache\CacheInterface;
use Illuminate\Support\Facades\Redis;

class RedisCacheAdapter implements CacheInterface
{
    public function get(string $key)
    {
        $value = Redis::get($key);
        return $value ? unserialize($value) : null;
    }

    public function set(string $key, $value, int $ttl = null)
    {
        $value = serialize($value);
        if ($ttl) {
            Redis::setex($key, $ttl, $value);
        } else {
            Redis::set($key, $value);
        }
    }

    public function delete(string $key)
    {
        Redis::del($key);
    }
}
