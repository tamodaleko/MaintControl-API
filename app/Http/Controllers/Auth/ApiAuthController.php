<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\User\UserInitResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ApiAuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['handle']
        ]);
    }

    /**
     * @var \Illuminate\Http\Request $request
     */
    public function handle(Request $request)
    {
        $request->request->add([
            'grant_type' => 'password_override',
            'client_id' => 1,
            'client_secret' => $request->client_secret,
            'username' => $request->username,
            'password' => $request->password
        ]);

        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);
    }

    /**
     * User init.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function initiate(Request $request)
    {
        return $this->wrapResponse(
            new UserInitResource($request->user())
        );
    }
}
