<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;

class Confirm extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke($token, $email)
  {

    $subscriber_data = Subscriber::where('token', $token)->where('email', $email)->first();

    if ($subscriber_data) {
      $subscriber_data->token = '';
      $subscriber_data->subscribed = true;
      $subscriber_data->update();

      return redirect()->back()->with('notify', [
        'type' => 'info',
        'title' => 'Welcome to Ultrashots',
        'message' => 'You are successfully subscribed.',
      ]);

    }

    return redirect()->route('home')->with('notify', [
      'type' => 'warning',
      'title' => 'Invalid subscriber data',
      'message' => 'Either the link has expired or you it has been modified',
    ]);

  }
}
