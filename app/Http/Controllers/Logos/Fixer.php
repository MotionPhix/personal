<?php

namespace App\Http\Controllers\Logos;

use App\Http\Controllers\Controller;
use App\Mail\LogoUploadMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Propaganistas\LaravelPhone\PhoneNumber;

class Fixer extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|max:255|min:4',
      'email' => 'required|email:rfc,dns',
      'description' => 'required|min:50',
      'logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
      'phone' => 'required|phone:INTERNATIONAL,MW',
      'company' => 'nullable',
    ], [
      'name.required' => 'Enter your full name',
      'name.min' => 'Your full name cannot be that shot',

      // Custom messages for email field
      'email.required' => 'Provide your email address',
      'email.email' => 'Enter a valid email address',

      'phone.required' => 'Provide your phone number.',
      'phone.phone' => 'That ' . $request->phone . ' is an invalid phone number',

      // Custom messages for description field
      'description.required' => 'Provide a description of your request',
      'description.min' => 'Please be verbose. Be as clear as you possibly can',

      // Custom messages for logo file upload
      'logo.required' => 'You must upload a logo file',
      'logo.image' => 'The file must be a valid image (jpg, jpeg, or png)',
      'logo.mimes' => 'The logo must be a file of type: jpg, jpeg, png',
      'logo.max' => 'The logo size must not exceed 2MB',
    ]);

    $logoPath = $request->file('logo')->store('temp');

    $phone = new PhoneNumber($request->phone);

    Mail::to('aishootsmo@gmail.com')->send(new LogoUploadMail(
      $validated['name'],
      $validated['email'],
      $validated['description'],
      storage_path('app/' . $logoPath),
      $phone->formatInternational(),
      $validated['company'] ?? null
    ));

    Storage::delete($logoPath);

    return back()->with('notify', [
      'type' => 'success',
      'message' => 'Logo uploaded! I will get in touch with you soon.'
    ]);
  }
}
