<?php

namespace App\Http\Controllers\Api;

// use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
  /**
   * success response method.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function sendResponse($result, $message, $code = 200): JsonResponse
  {
    $response = [
      'success' => true,
      'data'    => $result,
      'message' => $message,
    ];

    return response()->json($response, $code);
  }

  /**
   * return error response.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function sendError($error, $errorMessages = [], $code = 404): JsonResponse
  {
    $response = [
      'success' => false,
      'message' => $error,
    ];

    if (!empty($errorMessages)) {
      $response['data'] = $errorMessages;
    }

    return response()->json($response, $code);
  }
}
