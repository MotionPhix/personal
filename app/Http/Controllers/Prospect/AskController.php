<?php

namespace App\Http\Controllers\Prospect;

use App\Http\Controllers\Controller;
use App\Mail\ContactMe;
use App\Mail\FeedbackMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Propaganistas\LaravelPhone\PhoneNumber;

class AskController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $request->validate([
      'name' => 'required',
      'email' => 'required|email:rfc,dns',
      'phone' => 'required|phone:AUTO',
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

    try {
      // Send email to yourself
      Mail::to('hello@ultrashots.net', 'Kingsley')->send(new ContactMe(
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['user_query'],
        $data['company']
      ));

      // Send confirmation email to the prospect/client
      Mail::to($data['email'], $data['name'])->send(new FeedbackMail($data));

      return back()->with('success', 'Your message was successfully delivered. Thank you for reaching out!');

    } catch (\Exception $e) {
      Log::error('Contact form submission failed', [
        'error' => $e->getMessage(),
        'data' => $data
      ]);

      return back()->withErrors([
        'form' => 'Sorry, there was an error sending your message. Please try again or contact me directly.'
      ]);
    }
  }
}
