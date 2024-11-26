<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectGuestsTo(function (Request $request) {
      if ($request->is('api/*')) {
        // return response()->json([
        //   'success' => false,
        //   'message' => "Unauthorized",
        // ], 401);
      } else {
        return route('login');
        // return view('errors.401')->render();
      }
    });
  })
  // ->withExceptions(function (Exceptions $exceptions) {
  //     //
  // })->create();
  ->withExceptions(function (Exceptions $exceptions) {
    // $exceptions->render(function (AuthenticationException $e/* , Request $request */) {
    //   // override default sanctum response by adding 401 status code
    //   return response()->json(['message' => $e->getMessage()], 401);
    // });
    $exceptions->render(function (AuthenticationException $e, Request $request) {
      if ($request->is('api/*')) {
        return response()->json([
          'message' => $e->getMessage(),
        ], 401);
      } else {
        // return route('login');
      }
    });
  })->create();
