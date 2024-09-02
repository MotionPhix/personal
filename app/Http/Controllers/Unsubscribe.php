<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;

class Unsubscribe extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $request->validate([
      'email' => 'required|email'
    ]);

    $subscriber = Subscriber::where('email', $request->email)->first();

    if ($subscriber) {
      $subscriber->update(['subscribed' => false]);

      return notify()->success(
        'You have unsubscribed successfully',
        'Thank you'
      );
    }

    return notify()->error(
      'We couldn\'t find the email you provided',
      'Something went wrong'
    );
  }
}
