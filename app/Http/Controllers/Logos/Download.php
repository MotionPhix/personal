<?php

namespace App\Http\Controllers\Logos;

use App\Http\Controllers\Controller;
use App\Models\Logo;

class Download extends Controller
{
  public function __invoke(Logo $logo)
  {
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
}
