<?php

namespace App\Http\Controllers;

use App\Mail\ContactMe;
use App\Mail\FeedbackMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Propaganistas\LaravelPhone\PhoneNumber;

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
      'name' => 'required',
      'email' => 'required|email:rfc,dns',
      'phone' => 'required|phone:INTERNATIONAL,MW',
      'message' => 'required|not_equal',
      'company' => 'nullable',
    ], [
      'name.required' => 'Enter your full name.',

      'email.required' => 'Enter your email address.',
      'email.email' => 'You have entered an invalid email address.',

      'phone.required' => 'Provide your phone number.',
      'phone.phone' => 'That ' . $request->phone . ' is an invalid phone number',

      'message.required' => 'Enter your message so I can help accordingly.',
      'message.not_equal' => 'Provide some context I can use to help you',
    ]);

    $phone = new PhoneNumber($request->phone);

    $data = [
      'name' => $request->name,
      'email' => $request->email,
      'phone' => $phone->formatInternational(),
      'user_query' => $request->message,
      'company' => $request->company,
    ];

    // Send email
    if (Mail::to('kwikdev@ultrashots.net', 'Kingsley')->send(new ContactMe(
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['user_query'],
        $data['company']
      ))) {

      session()->flash('notify', [
        'type' => 'success',
        'title' => 'Thank you',
        'message' => 'Your message was successfully delivered.'
      ]);

      Mail::to($data['email'], 'Kingsley')->send(new FeedbackMail($data));

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
