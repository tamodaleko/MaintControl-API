<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordResetTokenRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\UpdateAvatarRequest;
use App\Http\Requests\User\UpdateContactInformationRequest;
use App\Http\Requests\User\UpdateDisplayInformationRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserLargeResourceCollection;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserResourceCollection;
use App\Models\PasswordReset;
use App\Models\Secure\SecureAnswer;
use App\Models\User\UserInvite;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => [
                'showByInviteCode',
                'updateByInviteCode'
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $area_id = ($request->has('area_id') && is_numeric($request->area_id)) ? $request->area_id : null;

        return $this->wrapResponse(
            new UserLargeResourceCollection(
                $request->user()->getAvailableUsers($area_id)
            )
        );
    }

    /**
     * Display the current user information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function info(Request $request)
    {
        return $this->wrapResponse(
            new UserResource($request->user())
        );
    }

    /**
     * Display the user resource by invite code.
     *
     * @param string $invite_code
     * @return \Illuminate\Http\Response
     */
    public function showByInviteCode($invite_code)
    {
        $user = UserInvite::getInvitedUserByCode($invite_code);

        if (!$user) {
            return $this->errorResponse('User not found.');
        }

        return $this->wrapResponse(new UserResource($user));
    }

    /**
     * Update the user resource by invite code.
     *
     * @param \App\Http\Requests\User\UpdateUserRequest $request
     * @param string $invite_code
     * @return \Illuminate\Http\Response
     */
    public function updateByInviteCode(UpdateUserRequest $request, $invite_code)
    {
        $invite = UserInvite::getByCode($invite_code);

        if (!$invite) {
            return $this->errorResponse('Invite not found.');
        }

        if ($invite->used) {
            return $this->errorResponse('Invite already used.');
        }
        
        $user = $invite->invitedUser;

        if (!$user) {
            return $this->errorResponse('User not found.');
        }

        $validated = $request->validated();
        $user->fill($validated);

        if (!$user->save()) {
            return $this->errorResponse('User could not be updated.', 500);
        }

        $invite->used = true;
        $invite->save();

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

        return $this->wrapResponse(new UserResource($user));
    }

    /**
     * Update user's display information.
     *
     * @param \App\Http\Requests\User\UpdateDisplayInformationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function displayInformation(UpdateDisplayInformationRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        $user->fill($validated);

        if (!$user->save()) {
            return $this->errorResponse('User\'s display information could not be updated.', 500);
        }

        return $this->wrapResponse(new UserResource($user));
    }

    /**
     * Update user's contact information.
     *
     * @param \App\Http\Requests\User\UpdateContactInformationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function contactInformation(UpdateContactInformationRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        $user->profile->fill($validated);

        if (!$user->profile->save()) {
            return $this->errorResponse('User\'s contact information could not be updated.', 500);
        }

        return $this->wrapResponse(new UserResource($user));
    }

    /**
     * Update user's avatar.
     *
     * @param \App\Http\Requests\User\UpdateAvatarRequest $request
     * @return \Illuminate\Http\Response
     */
    public function avatar(UpdateAvatarRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        $user->fill($validated);

        if (!$user->save()) {
            return $this->errorResponse('User\'s avatar could not be updated.', 500);
        }

        return $this->wrapResponse(new UserResource($user));
    }

    /**
     * Update user's password.
     *
     * @param \App\Http\Requests\User\UpdatePasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function password(UpdatePasswordRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        if (!password_verify($request->old_password, $user->password)) {
            return $this->errorResponse('User\'s old password is invalid.', 422);
        }

        $user->password = bcrypt($request->new_password);

        if (!$user->save()) {
            return $this->errorResponse('User\'s password could not be updated.', 500);
        }

        return $this->successResponse('User\'s password has been updated successfully.');
    }

    /**
     * Reset user's password.
     *
     * @param \App\Http\Requests\User\ResetPasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function passwordReset(ResetPasswordRequest $request)
    {
        $user = $request->user();

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
     * @param \App\Http\Requests\User\PasswordResetTokenRequest $request
     * @return \Illuminate\Http\Response
     */
    public function passwordResetToken(PasswordResetTokenRequest $request)
    {
        $user = $request->user();

        if (!SecureAnswer::verify($user->id, $request->secure_question_id, $request->secure_answer)) {
            return $this->errorResponse('User\'s secure answer is invalid.', 422);
        }

        return $this->wrapResponse([
            'token' => PasswordReset::generateToken($user)
        ]);
    }
}
