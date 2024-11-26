<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;

class AuthController extends BaseController
{
  /**
   * Register api
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function register(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email:rfc,dns',
      'password' => 'required',
      'c_password' => 'required|same:password',
    ]);

    if ($validator->fails()) {
      return $this->sendError('Validation Error.', $validator->errors(), 422);
    }

    $input = $request->all();
    // $input['password'] = bcrypt($input['password']);

    $input['password'] = Hash::make($input['password']);
    $user = User::create($input);
    $success['token'] = $user->createToken(config('app.name'))->plainTextToken;
    $success['name'] = $user->name;

    return $this->sendResponse($success, 'User register successfully.', 201);
  }

  /**
   * Login api
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email:rfc,dns',
      'password' => 'required',
    ]);

    if ($validator->fails()) {
      return $this->sendError('Validation Error.', $validator->errors(), 422);
    }

    // token login with api guard:
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      return $this->sendError('The provided credentials are incorrect.', [], 401);
    }

    $success['token'] = $user->createToken(config('app.name'))->plainTextToken;
    $success['name'] = $user->name;

    return $this->sendResponse($success, 'User login successfully.');
  }

  /**
   * Logout api
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout(Request $request)
  {

    // try {
    if ($user = $request->user()) {
      $user->currentAccessToken()->delete();
      // revoke all tokens
      // $user->tokens()->delete();
      return response()->json(
        [
          'status' => 'success',
          'message' => 'User logged out successfully'
        ]
      );
    } else {
      return $this->sendError('User not logged in or token mismatch.', [], 401);
    }
  }
}
