<?php

namespace TusPhp\Cache;

use Carbon\Carbon;
use TusPhp\Config;
// use Predis\Client as RedisClient;
use Illuminate\Support\Facades\DB;

class EloquentStore extends AbstractCache
{
    /** @var RedisClient */
    protected $redis;

    /**
     * EloquentStore constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $options = empty($options) ? Config::get('redis') : $options;

        // $this->redis = new RedisClient($options);
    }

    /**
     * Get redis.
     *
     * @return RedisClient
     */
    // public function getRedis() : RedisClient
    // {
    //     return $this->redis;
    // }

    /**
     * {@inheritDoc}
     */
    public function get(string $key, bool $withExpired = false)
    {
        $prefix = $this->getPrefix();

        if (false === strpos($key, $prefix)) {
            $key = $prefix . $key;
        }

        //$contents = $this->redis->get($key);
        $contents = DB::table('tus_cache_table')->where('key', $key)->first();
        $cotents->value;

        $contents = json_decode($contents, true);

        if ($withExpired) {
            return $contents;
        }

        if ( ! $contents) {
            return null;
        }

        $isExpired = Carbon::parse($contents['expires_at'])->lt(Carbon::now());

        return $isExpired ? null : $contents;
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $key, $value)
    {
        $contents = $this->get($key) ?? [];

        if (\is_array($value)) {
            $contents = $value + $contents;
        } else {
            $contents[] = $value;
        }

        $status = DB::table('tus_cache_table')->insert([
            'key'   => $this->getPrefix() . $key,
            'value' => json_encode($contents)
        ]);

        return 'OK' === $status->getPayload();
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $key) : bool
    {
        $prefix = $this->getPrefix();

        if (false === strpos($key, $prefix)) {
            $key = $prefix . $key;
        }

        // return $this->redis->del([$key]) > 0;
        return DB::table('tus_cache_table')->where('key', '=', $key)->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function keys() : array
    {
        // return $this->redis->keys($this->getPrefix() . '*');
        return DB::table('tus_cache_table')->get();
    }
}