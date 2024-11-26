<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller as Controller;

class LoginController extends Controller
{
  protected $redirectAfterLogout = '/login';

  public function showLoginForm()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      return redirect()->intended('/home');
    }
    // Session::flush();
    Session::forget('errors');
    return redirect('/login')->withErrors(['error' => 'Invalid credentials. Please try again.']);
  }

  /**
   * Logout, Clear Session, and Return.
   *
   * @return void
   */
  public function logout()
  {
    // $user = Auth::user();
    // Log::info('User Logged Out. ', [$user]);
    Auth::logout();
    Session::flush();

    return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
  }
}
