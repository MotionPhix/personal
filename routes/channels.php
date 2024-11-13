<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('logos', function() {
  \Log::info('Authorization succeeded');
  return true;
});
