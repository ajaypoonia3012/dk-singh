<?php

namespace App\Livewire\Media;

use App\Models\Media;
use App\Services\Media\MediaUploadService;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class MediaUpload extends Component
{
    use WithFileUploads;

    public array $upload = [];

    public function updatedUpload(MediaUploadService $service)
    {
        foreach ($this->upload as $file) {

            $uploaded = $service->upload($file);

            [$width, $height] = getimagesize(
                Storage::disk('public')->path($uploaded->path)
            );

            $media = Media::create([

                'name' => pathinfo(
                    $uploaded->originalName,
                    PATHINFO_FILENAME
                ),

                'file_name' => basename($uploaded->path),

                'disk' => 'public',

                'folder' => 'media/originals',

                'media_category_id' => null,

                'path' => $uploaded->path,

                'mime_type' => $uploaded->mimeType,

                'size' => $uploaded->size,

                'width' => $width,

                'height' => $height,

                'type' => 'image',

                'active' => true,

                'featured' => false,

            ]);

            $this->dispatch(
                'media-created',
                id: $media->id
            );
        }

        $this->reset('upload');
    }

    public function render()
    {
        return view('livewire.media.media-upload');
    }
}
