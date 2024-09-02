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

      notify()->success(
        'You are successfully subscribed.',
        'Welcome'
      );

      return redirect()->back();
    } else {

      return redirect()->route('home');
    }

  }
}
