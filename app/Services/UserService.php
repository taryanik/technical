<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        return $this->userRepository->register($data);
    }

    public function signIn(array $credentials): array
    {
        if (! $token = Auth::attempt($credentials)) {
            throw new Exception('The provided credentials are incorrect.');
        }

        return ['token' => $token];
    }

    public function recoverPassword(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user) {
            throw new Exception('We can’t find a user with that email address.');
        }

        $user = $this->userRepository->update(
            $user,
            [
                'remember_token' => Str::random(60),
                'token_expires_at' => Carbon::now()->addSeconds(config('app.reset_password.token_expiration_in_sec')),
            ],
        );

        Mail::raw("Use this link to reset your password: $user->remember_token", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset');
        });

        return ['message' => 'Password reset email was sent to the registered email address.'];
    }
}