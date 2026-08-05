<?php

namespace App\Livewire\Media;

use App\Models\Media;
use Livewire\Component;

class MediaPicker extends Component
{
    public bool $open = false;

    public string $tab = 'library';

    public ?int $selectedMediaId = null;

    public function open()
    {
        $this->open = true;
    }

    public function close()
    {
        $this->open = false;
    }

    public function showLibrary()
    {
        $this->tab = 'library';
    }

    public function showUpload()
    {
        $this->tab = 'upload';
    }

    protected $listeners = [

        'media-selected' => 'mediaSelected',

    ];

    public function mediaSelected($id)
    {
        $this->selectedMediaId = $id;
    }

    public function getSelectedMediaProperty()
    {
        if (!$this->selectedMediaId) {
            return null;
        }

        return Media::find($this->selectedMediaId);
    }

    public function render()
    {
        return view('livewire.media.media-picker');
    }
}
