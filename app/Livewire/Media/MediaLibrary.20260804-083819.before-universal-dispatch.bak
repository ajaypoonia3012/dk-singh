<?php

namespace App\Livewire\Media;

use App\Models\Media;
use App\Models\MediaCategory;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MediaLibrary extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';

    public $category = '';

    public $upload;
public ?int $selectedMediaId = null;

    protected $queryString = [
        'search',
        'category',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }
public function select($id)
{
    $this->selectedMediaId = $id;
}

public function useSelected()
{
    if (!$this->selectedMediaId) {
        return;
    }

    $this->dispatch(
        'media-selected',
        id: $this->selectedMediaId
    );
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
        return view('livewire.media.media-library', [

            'categories' => MediaCategory::orderBy('name')->get(),

            'media' => Media::query()

                ->when($this->search, function ($query) {

                    $query->where(function ($q) {

                        $q->where('name', 'like', "%{$this->search}%")
                          ->orWhere('title', 'like', "%{$this->search}%")
                          ->orWhere('alt', 'like', "%{$this->search}%");

                    });

                })

                ->when($this->category, function ($query) {

                    $query->where('media_category_id', $this->category);

                })

                ->latest()

                ->paginate(24),

        ]);
    }
}
