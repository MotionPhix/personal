<?php

namespace App\Http\Controllers\Logos;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Inertia\Inertia;

class Listing extends Controller
{
  public function __invoke() {

    $logoFiles = Logo::all()->map(function ($logo) {
      return [
        'lid' => $logo->lid,
        'brand' => $logo->brand,
        'poster_url' => $logo->getFirstMediaUrl('posters'),
        'file_url' => $logo->getFirstMediaUrl('logos'),
      ];
    });

    return Inertia::render('admin/downloads/Index', [
      'logoFiles' => $logoFiles,
    ]);

  }
}
