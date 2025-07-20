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
    $logoFiles = Logo::get(['id', 'uuid', 'brand'])->map(function ($logo) {
      return [
        'uuid' => $logo->uuid,
        'brand' => $logo->brand,
        'poster_url' => $logo->getFirstMediaUrl('posters', 'thumb'),
      ];
    });

    return Inertia::render('downloads/Index', [
      'logoFiles' => $logoFiles,
    ]);

  }
}
