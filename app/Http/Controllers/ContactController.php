<?php

namespace App\Http\Controllers;

use App\Mail\ContactMe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ContactController extends Controller
{

  /**
   * Display the contact form.
   */
  public function index()
  {
    return Inertia::render('Contact/Index');
  }

  public function send(Request $request)
  {
    $request->validate([
      'name' => 'required|string',
      'email' => 'required|email',
      'phone' => 'required',
      // 'message' => 'required|string',
    ]);

    $data = [
      'name' => $request->name,
      'email' => $request->email,
      'phone' => $request->phone,
    ];

    // Send email
    if (Mail::to('kwikdev@ultrashots.net', 'Kingsley')->send(new ContactMe($data))) {

      session()->flash('notify', [
        'type' => 'success',
        'title' => 'Thank you',
        'message' => 'Your message was successfully delivered.'
      ]);

      return redirect()->back();

    };

    session()->flash('notify', [
      'type' => 'danger',
      'title' => 'Oops!',
      'message' => 'Message could not be sent.'
    ]);

    return redirect()->back();
  }
}
