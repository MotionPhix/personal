<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DownloadController extends Controller
{

  /**
   * Display the all available logos.
   */
  public function index(): Response
  {
    // Fetch all logos and append media URLs
    $logoFiles = Logo::all()->map(function ($logo) {
      return [
        'lid' => $logo->lid,
        'brand' => $logo->brand,
        'poster_url' => $logo->getFirstMediaUrl('posters', 'thumb'), // Poster thumbnail
        'file_url' => $logo->getFirstMediaUrl('files'), // Actual file to download
      ];
    });

    return Inertia::render('Downloads/Index', [
      'logoFiles' => $logoFiles,
    ]);
  }

  /**
   * Display the all available logos in the admin.
   */
  public function listing(): Response
  {
    // Fetch all logos and append media URLs
    $logoFiles = Logo::all()->map(function ($logo) {
      return [
        'lid' => $logo->lid,
        'brand' => $logo->brand,
        'poster_url' => $logo->getFirstMediaUrl('posters'),
        'file_url' => $logo->getFirstMediaUrl('logos'),
      ];
    });

    return Inertia::render('Admin/Downloads/Index', [
      'logoFiles' => $logoFiles,
    ]);
  }

  /**
   * Upload logo.
   */
  public function create()
  {

    return Inertia::render('Admin/Downloads/Form');
  }

  /**
   * Download the selected logo.
   */
  public function store(Request $request)
  {
    $request->validate([
      'file_path' => 'required|file|mimes:svg,ai,cdr,pdf|max:2048',
      'poster' => 'required|image|max:1024',
      'brand' => 'required|string'
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
      'title' => 'New upload',
      'message' => 'Logo uploaded successfully.'
    ]);
  }

  /**
   * Download the selected logo.
   */
  public function show(Logo $logo)
  {
    // Find the downloadable file
    $mediaItem = $logo->getFirstMedia('logos');

    if (!$mediaItem) {

      return redirect()->back()->with('notify', [
        'type' => 'danger',
        'title' => 'Wrong path',
        'message' => 'Logo file link is broken.'
      ]);
    }

    return response()->download($mediaItem->getPath(), $mediaItem->file_name);
  }

  /**
   * Download the selected logo.
   */
  public function destroy(Logo $logo)
  {
    // This will delete the logo along with all associated media files
    $logo->delete();

    return redirect()->back()->with('notify', [
      'type' => 'success',
      'title' => 'Logo Deleted',
      'message' => 'The logo and its files were successfully deleted.'
    ]);
  }
}
