<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

class SetMediaDirectoryPermissions
{
  /**
   * Create the event listener.
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   *
   * @param  \Spatie\MediaLibrary\Events\MediaHasBeenAdded  $event
   * @return void
   */
  public function handle(MediaHasBeenAddedEvent $event): void
  {
    // Get the path of the uploaded media item's directory
    $path = dirname($event->media->getPath());

    // Check if the path exists and set the folder permissions to 0755
    if (File::exists($path)) {
      chmod($path, 0755);
    }
  }
}
