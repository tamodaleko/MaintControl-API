<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserPreferenceResource;
use App\Models\User\UserPreference;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Show user's system preferences.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if (!$request->user()->preference) {
            return $this->errorResponse('User has no system preferences created.');
        }

        return $this->wrapResponse(
            new UserPreferenceResource($request->user()->preference)
        );
    }

    /**
     * Update user's system preferences.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(UserPreference::$rules);
        $validated['user_id'] = $request->user()->id;

        $preference = $request->user()->preference ?: new UserPreference;
        $preference->fill($validated);

        if (!$preference->save()) {
            return $this->errorResponse('User\'s system preferences could not be updated.', 500);
        }

        return $this->wrapResponse(new UserPreferenceResource($preference));
    }
}
