<?php

namespace App\Livewire\Builder;

use Livewire\Component;

class PropertiesPanel extends Component
{
    public $section;

    public function mount($section = null)
    {
        $this->section = $section;
    }

    public function render()
    {
        return view('livewire.builder.properties-panel');
    }
}