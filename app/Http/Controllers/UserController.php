<?php

namespace App\Http\Controllers;

use App\Http\Responses\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'first_name' => 'required|string|min:2|max:70',
            'last_name' => 'required|string|min:2|max:70',
            'email' => 'required|email:rfc,dns|unique:users,email|max:64',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|numeric',
        ]);

        // We can use DTO here depending on our approach
        $data = $request->only(['first_name', 'last_name', 'email', 'password', 'phone']);
        $user = $this->userService->register($data);

        return response()->json(new UserResource($user), Response::HTTP_CREATED);
    }

    public function signIn(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email:rfc,dns|exists:users,email',
            'password' => 'required',
        ]);

        try {
            $response = $this->userService->signIn($request->only('email', 'password'));

            return response()->json($response);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    public function recoverPassword(Request $request): JsonResponse
    {
        try {
            $this->validate($request,
                ['email' => 'required|email:rfc,dns|exists:users,email'],
            );
            $response = $this->userService->recoverPassword($request->only('email'));

            return response()->json($response);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Password reset email was sent to the registered email address.']);
        }
    }
}
