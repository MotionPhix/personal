<?php

namespace App\Http\Controllers\Logos;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Inertia\Inertia;

class Index extends Controller
{
  /**
   * List available logos.
   */
  public function __invoke()
  {
    $logoFiles = Logo::all()->map(function ($logo) {
      return [
        'lid' => $logo->lid,
        'brand' => $logo->brand,
        'poster_url' => $logo->getFirstMediaUrl('posters', 'thumb'),
        'file_url' => $logo->getFirstMediaUrl('files'),
      ];
    });

    return Inertia::render('Downloads/Index', [
      'logoFiles' => $logoFiles,
    ]);

  }
}
