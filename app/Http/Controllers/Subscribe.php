<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationMail;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class Subscribe extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $request->validate([
      'email' => 'required|email:rfc,dns|unique:subscribers|max:30'
    ], [
      'email.required' => 'Enter your email address to subscribe.',
      'email.email' => 'That looks like an invalid email address.',
      'email.unique' => 'Looks like you are already subscribed.'
    ]);

    $token = hash('sha256', time());

    $subscriber = Subscriber::create([
      'email' => $request->email,
      'token' => $token
    ]);

    // Send email
    if (Mail::to($subscriber->email)->send(new ConfirmationMail($token, $subscriber))) {

      notify()->success(
        'You are successfully subscribed.',
        'Welcome'
      );

      return redirect()->back();

    };

    notify()->success(
      'Please check your inbox to confirm subscription',
      'Thanks'
    );

    return redirect()->back();
  }
}
