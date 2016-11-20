<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;

class Authenticate {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth) {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $token = $request->header('token');

        if ($request != NULL) {

            if (filter_var($token, FILTER_SANITIZE_SPECIAL_CHARS) != "") {
                   
                if (DB::table('product_users')
                        ->where('remember_token', $token)->first()) {

                    $tokenStatus = $this->updateToken($token, null);
                    if (true === $tokenStatus) {
                        $data = DB::table('product_users')->
                                select('id', 'birthdate', 'sex','geo_location')->
                                where('remember_token', $token)->
                                where('active', '1')->first();
                        $request->attributes->add(['data' => $data]);
                        return $next($request);
                    }
                }
            }
        }
        return response()->json(['token' => 'Access token invalid'], 601);
    }

    public function updateToken($token = null, $email = null) {

        if ($token != null) {
            $getDate = DB::table('product_users')
                    ->where('remember_token', $token)
                    ->pluck('updated_at');
            if (time() - (strtotime($getDate)) < getenv('TOKEN_DEAD_TIME')) {
                DB::table('product_users')
                        ->where('remember_token', $token)
                        ->update(array('updated_at' => date("Y-m-d H:i:s")));
                return true;
            } else {
                DB::table('product_users')
                        ->where('remember_token', $token)
                        ->update(array('remember_token' => NULL));
                return false;
            }
        } else {
            $saltToken = getenv('TOKEN_SALT');
            $token = hash('sha256', $saltToken . time());
            DB::table('product_users')
                    ->where('email', $email)
                    ->update(['remember_token' => $token,
                        'updated_at' => date("Y-m-d H:i:s")]);
            return $token;
        }
    }

}
