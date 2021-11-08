<?php
namespace Clickonmedia\LaravelTus;

// use TusPhp\Cache;
use TusPhp\Cache\AbstractCache;
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
        $result = DB::table('tus_cache')->where('key', $key)->first();
        //TODO add check for null return
        if (!isset($result->value)){
            return null;
        }
        $contents = $result->value;

        $contents = json_decode($contents, true);

        if ($withExpired) {
            return $contents;
        }

        if ( ! $contents) {
            return null;
        }

        // $isExpired = Carbon::parse($contents['expires_at'])->lt(Carbon::now());
        $isExpired = null;

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

        $status = DB::table('tus_cache')->insert([
            'key'   => $this->getPrefix() . $key,
            'value' => json_encode($contents)
        ]);

        // TODO if successfull return OK
        //return 'OK' === $status->getPayload();
        return 'OK';
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
        return DB::table('tus_cache')->where('key', '=', $key)->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function keys() : array
    {
        // return $this->redis->keys($this->getPrefix() . '*');
        $results = DB::table('tus_cache')->get()->pluck('value')->toArray();
        return $results;

    }
}