<?php

namespace App\Livewire\Builder;

use Livewire\Component;

class Toolbar extends Component
{
    public $device = 'desktop';

    public function render()
    {
        return view('livewire.builder.toolbar');
    }
}