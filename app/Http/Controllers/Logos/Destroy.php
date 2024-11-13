<?php

namespace App\Http\Controllers\Logos;

use App\Events\LogoDeleted;
use App\Http\Controllers\Controller;
use App\Models\Logo;

class Destroy extends Controller
{
  public function __invoke(Logo $logo)
  {
    $logo->delete();

    broadcast(new LogoDeleted($logo->id));

    return redirect()->back()->with('notify', [
      'type' => 'success',
      'title' => 'Logo Deleted',
      'message' => 'The logo and its files were successfully deleted.'
    ]);
  }
}
