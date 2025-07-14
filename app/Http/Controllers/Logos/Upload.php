<?php

namespace App\Http\Controllers\Logos;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class Upload extends Controller
{
  public function __invoke()
  {

    return Inertia::render('downloads/FixLogoForm');

  }
}
