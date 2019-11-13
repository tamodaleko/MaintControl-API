<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Resources\Admin\UserResource;
use App\Http\Resources\Admin\UserResourceCollection;
use App\Models\PasswordReset;
use App\Models\User\User;
use App\Models\User\UserInvite;
use App\Models\User\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->wrapResponse(
            new UserResourceCollection($request->user()->company->activeUsers)
        );
    }

    /**
     * Get archived items.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function archived(Request $request)
    {
        return $this->wrapResponse(
            new UserResourceCollection($request->user()->company->archivedUsers)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(User::$rules);
        $validated['company_id'] = $request->user()->company_id;

        $user = User::create($validated);

        if (!$user) {
            return $this->errorResponse('User could not be created.', 500);
        }

        if ($request->invite) {
            $invite = UserInvite::where('code', $request->invite)->first();

            if ($invite) {
                $invite->invited_user_id = $user->id;
                $invite->save();
            }
        } elseif ($request->password) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        $user->profile()->create([
            'user_id' => $user->id,
            'email' => $request->email,
            'phone' => $request->phone,
            'fax' => $request->fax,
            'office' => $request->office,
            'skype' => $request->skype
        ]);

        $user->syncRoles($request->roles);

        $user->attachWorkgroups($request->workgroups);
        $user->attachAreas($request->areas);

        return $this->wrapResponse(new UserResource($user));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->wrapResponse(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Admin\UpdateUserRequest $request
     * @param \App\Models\User\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $validated['company_id'] = $request->user()->company_id;

        $user->fill($validated);

        if (!$user->save()) {
            return $this->errorResponse('User could not be updated.', 500);
        }

        if (!is_null($request->password)) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        $user->profile->updateInformation(
            $request->email, 
            $request->phone, 
            $request->fax, 
            $request->office, 
            $request->skype
        );

        if (!is_null($request->roles)) {
            $user->syncRoles($request->roles);
        }

        $user->attachWorkgroups($request->workgroups);
        $user->attachAreas($request->areas);

        return $this->wrapResponse(new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if (!$user->delete()) {
            return $this->errorResponse('User could not be deleted.', 500);
        }

        return $this->successResponse('User has been deleted successfully.');
    }

    /**
     * Generate the invite code.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function inviteCode(Request $request)
    {
        do {
            $code = Str::random();
            $invite = UserInvite::where('code', $code)->first();
        } while (!empty($invite));

        UserInvite::create([
            'user_id' => $request->user()->id,
            'code' => $code
        ]);

        return $this->wrapResponse([
            'code' => $code
        ]);
    }

    /**
     * Change status.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, User $user)
    {
        if (is_null($request->status)) {
            return $this->errorResponse('Status field is missing from the request.', 500);
        }

        if (!is_numeric($request->status) || !in_array($request->status, User::$statusOptions)) {
            return $this->errorResponse('Status field is invalid.', 500);
        }

        $user->status = (int) $request->status;

        if (!$user->save()) {
            return $this->errorResponse('Status could not be changed.', 500);
        }

        return $this->wrapResponse(new UserResource($user));
    }

    /**
     * Reset user's password.
     *
     * @param \App\Http\Requests\User\ResetPasswordRequest $request
     * @param \App\Models\User\User $user
     * @return \Illuminate\Http\Response
     */
    public function passwordReset(ResetPasswordRequest $request, User $user)
    {
        $passwordReset = PasswordReset::where('token', $request->token)->first();

        if (!$passwordReset || $passwordReset->used) {
            return $this->errorResponse('Password reset token is invalid.', 422);
        }

        $user->password = bcrypt($request->password);

        if (!$user->save()) {
            return $this->errorResponse('User\'s password could not be updated.', 500);
        }

        $passwordReset->used = 1;
        $passwordReset->save();

        return $this->successResponse('User\'s password has been updated successfully.');
    }

    /**
     * Generate password reset token.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User\User $user
     * @return \Illuminate\Http\Response
     */
    public function passwordResetToken(Request $request, User $user)
    {
        return $this->wrapResponse([
            'token' => PasswordReset::generateToken($user)
        ]);
    }
}
