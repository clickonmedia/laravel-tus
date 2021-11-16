<?php

namespace Clickonmedia\LaravelTus;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\File;

use Cache\EloquentStore;

class LaravelTusController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function get(Request $request) {
        return response()->json([
            'status'=>200,
            'type'=>'GET',
            'message'=>'OK: Successfully retrieved all Uploads',
            'data'=>'Data',
        ]);
    }

    public function uppy(Request $request) {
        return File::get(__DIR__ . '/../example/uppy/index.html');
    }
    
    public function tus(Request $request) {
        $server   = new \TusPhp\Tus\Server('file'); // Either redis, file or apcu. Leave empty for file based cache.
        $response = $server->serve();

        return $response->send();
        // exit(0); // Exit from current PHP process.

        // return response()->json([
        //     'status'=>200,
        //     'type'=>'GET',
        //     'message'=>'OK: Successfully retrieved all Uploads',
        //     'data'=>'Data',
        // ]);
    }
}
