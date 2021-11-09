<?php
namespace Clickonmedia\LaravelTus;

use TusPhp\Cache\AbstractCache;
use Carbon\Carbon;
use TusPhp\Config;
use Illuminate\Support\Facades\DB;

class EloquentStore extends AbstractCache
{
    /** @var EloquentClient */
    protected $eloquent;

    /**
     * EloquentStore constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $options = empty($options) ? Config::get('eloquent') : $options;

        // $this->eloquent = new DB::table('tus_cache');
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key, bool $withExpired = false)
    {
        $prefix = $this->getPrefix();

        if (false === strpos($key, $prefix)) {
            $key = $prefix . $key;
        }

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

        $status = DB::table('tus_cache')->updateOrInsert(
            ['key'   => $this->getPrefix() . $key],
            ['value' => json_encode($contents)]
        );

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

        return DB::table('tus_cache')->where('key', '=', $key)->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function keys() : array
    {

        $results = DB::table('tus_cache')->get()->pluck('value')->toArray();
        return $results;

    }
}