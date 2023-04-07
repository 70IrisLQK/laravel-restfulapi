<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listUser = User::all();

        return $this->showAll($listUser, 200, 'OK', 'Get users successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'max:255', 'string'],
            'email' => ['required', 'max:255', 'email', 'unique:users,email', 'string'],
            'password' => ['required', 'max:255', 'min:6', 'string'],
            'confirm_password' => ['required', 'max:255', 'min:6', 'same:password', 'string'],
        ];

        $request->validate($rules);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationToken();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);

        return $this->showOne($user, 201, 'OK', 'Create user successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user, 200, 'OK', 'Get user by id successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['max:255', 'string'],
            'email' => ['max:255', 'email', 'unique:users,email', 'string'],
            'password' => ['max:255', 'min:6', 'string'],
            'confirm_password' => ['max:255', 'min:6', 'same:password', 'string'],
            'admin' => ['in:' . User::ADMIN_USER . ',' . User::REGULAR_USER],
        ];

        $request->validate($rules);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationToken();

            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return $this->errorResponse(409, 'error', 'Only verified users can modify the admin field.');
            }

            $user->status = $request->admin;
        }
        if (!$user->isDirty()) {
            return $this->errorResponse(422, 'error', 'You need to specify a difference value to update.');
        }

        $user->save();

        return $this->showOne($user, 200, 'OK', 'Update user by id successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne($user, 200, 'OK', 'Delete user by id successfully.');
    }
}