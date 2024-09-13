<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use Illuminate\Http\Request;
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
   * Display the all available logos in the admin.
   */
  public function listing(): Response
  {
    $downloads = Logo::all();

    return Inertia::render('Admin/Downloads/Index', [
      'downloads' => $downloads,
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
    dd($request->all());
    // application/pdf, image/svg+xml
    // return Storage::download($logo->file_path, $logo->name . '.jpg');
    return Storage::disk('public')->download($logo->file_path, $logo->name . '.jpg');
  }

  /**
   * Download the selected logo.
   */
  public function show(Logo $logo): StreamedResponse
  {
    // application/pdf, image/svg+xml
    // return Storage::download($logo->file_path, $logo->name . '.jpg');
    return Storage::disk('public')->download($logo->file_path, $logo->name . '.jpg');
  }
}
