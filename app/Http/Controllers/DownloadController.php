<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{

  /**
   * Display the all available logos.
   */
  public function index(): Response
  {
    $downloads = Logo::all();

    return Inertia::render('Downloads/Index', [
      'downloads' => $downloads,
    ]);
  }

  /**
   * Download the selected logo.
   */
  public function show(Logo $logo): StreamedResponse
  {
//    return Storage::download($logo->file_path, $logo->name . '.jpg');
    return Storage::disk('public')->download($logo->file_path, $logo->name . '.jpg');
  }
}
