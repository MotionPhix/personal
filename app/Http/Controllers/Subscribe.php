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

      return redirect()->back()->with('notify', [
        'type' => 'success',
        'title' => 'Thank you!',
        'message' => 'Please check your inbox to confirm subscription'
      ]);

    };

    session()->flash('notify', [
      'type' => 'danger',
      'title' => 'Mail delivery failure',
      'message' => 'Please check if you entered the correct email'
    ]);

    return redirect()->back();
  }
}
