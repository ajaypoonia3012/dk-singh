<?php

namespace App\Livewire\Builder;

use Livewire\Component;

class PreviewCanvas extends Component
{
    public $section;

    public $device = 'desktop';

    public function mount($section = null, $device = 'desktop')
    {
        $this->section = $section;
        $this->device = $device;
    }

    public function render()
    {
        return view('livewire.builder.preview-canvas');
    }
}