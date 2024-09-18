<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ImageOrUrl implements ValidationRule
{
  /**
   * Run the validation rule.
   *
   * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void
  {
    // Check if the value is a valid URL
    if (! is_string($value) && ! filter_var($value, FILTER_VALIDATE_URL)) {
      $fail('This is an invalid image URL. Can\'t reach the image');
    }

    // Check if the value is an uploaded file
    if ($value instanceof UploadedFile) {
      if (!$value->isValid()) {
        $fail('The uploaded file is invalid.');
        return; // Exit after failing
      }

      if (!in_array($value->getClientOriginalExtension(), ['jpeg', 'png', 'jpg', 'gif', 'svg'])) {
        $fail('The uploaded file must be a valid image.');
        return; // Exit after failing
      }

      if ($value->getSize() > 2048 * 1024) {
        $fail('The image file is bigger than what\'s allowed (2MB).');
        return; // Exit after failing
      }
    }
  }
}
