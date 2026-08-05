<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\WebsiteSection;

class SectionsPanel extends Component
{
    public $sections;

    public $selected;

    public function mount($sections = null, $selected = null)
    {
        $this->sections = $sections ?? WebsiteSection::orderBy('sort_order')->get();
        $this->selected = $selected;
    }

    public function select($id)
    {
        $this->dispatch('sectionSelected', id: $id);
    }

    public function render()
    {
        return view('livewire.builder.sections-panel');
    }
}