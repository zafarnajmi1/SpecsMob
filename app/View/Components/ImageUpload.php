<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Storage;

class ImageUpload extends Component
{
    public $fieldName;
    public $existingImage;
    public $resolvedImage;

    public function __construct($fieldName, $existingImage = null)
    {
        
    $this->fieldName = $fieldName;
    $this->existingImage = $existingImage;

    if ($existingImage && Storage::disk('public')->exists($existingImage)) {
        $this->resolvedImage = asset('storage/' . ltrim($existingImage, '/'));
    } else {
        $this->resolvedImage = null;
    }
    }

    public function render()
    {
        return view('components.image-upload');
    }
}
