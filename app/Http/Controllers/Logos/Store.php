<?php

namespace App\Http\Controllers\Logos;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Illuminate\Http\Request;

class Store extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $request->validate([
      'file_path' => 'required|file|mimes:svg,ai,cdr,pdf|max:2048',
      'poster' => 'required|image|max:1024',
      'brand' => 'required'
    ], [
      'file_path.required' => 'Please add a logo file to upload.',
      'file_path.file' => 'This is an invalid file.',
      'file_path.mimes' => 'Incorrect file format. Correct ones are .svg, .ai, .cdr, and .pdf.',
      'file_path.max' => 'The file is to large. Max size is 2MB.',

      'poster.required' => 'Please add a poster image.',
      'poster.image' => 'This is an invalid image.',
      'poster.max' => 'The poster is to large. Max size is 1MB.',

      'brand.required' => 'Please enter a brand name.'
    ]);

    $logo = new Logo();
    $logo->brand = $request->brand;
    $logo->save();

    // Add media for the poster
    if ($request->hasFile('poster')) {
      $logo->addMediaFromRequest('poster')->toMediaCollection('posters');
    }

    // Add media for the file path (actual logo)
    if ($request->hasFile('file_path')) {
      $logo->addMediaFromRequest('file_path')->toMediaCollection('logos');
    }

    return redirect()->route('auth.downloads.index')->with('notify', [
      'type' => 'success',
      'title' => 'New logo',
      'message' => 'Logo stored successfully.'
    ]);

  }
}
