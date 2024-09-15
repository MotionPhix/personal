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

      return redirect()->route('home')->with('notify', [
        'type' => 'success',
        'title' => 'Subscription cancelled',
        'message' => 'You have unsubscribed successfully',
      ]);
    }

    return redirect()->back()->with('notify', [
      'type' => 'danger',
      'title' => 'Something went wrong',
      'message' => 'We couldn\'t find the email you provided',
    ]);
  }
}
