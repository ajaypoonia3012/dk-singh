<?php

namespace App\Services\Builder;

use App\Models\HomepageCard;
use Illuminate\Support\Facades\Gate;

class HomepageCardService
{
    public function all()
    {
        return HomepageCard::with('backgroundMedia')->orderBy('sort_order')->get();
    }

    public function find(int $id): HomepageCard
    {
        return HomepageCard::findOrFail($id);
    }

    public function create(): HomepageCard
    {
        Gate::authorize('create', HomepageCard::class);

        return HomepageCard::create([

            'title' => 'New Homepage Card',

            'subtitle' => 'Subtitle',

            'description' => 'Description',

            'button_text' => 'Learn More',

            'button_link' => '#',

            'background_image' => null,

            'icon' => '⭐',

            'sort_order' => HomepageCard::max('sort_order') + 1,

            'is_active' => true,

        ]);
    }

    public function update(
        HomepageCard $card,
        array $data
    ): HomepageCard {
        Gate::authorize('update', $card);

        $card->update($data);

        return $card->fresh();
    }

    public function delete(
        HomepageCard $card
    ): void {
        Gate::authorize('delete', $card);

        $card->delete();

        HomepageCard::whereNotNull('sort_order')
            ->orderBy('sort_order')
            ->get()
            ->values()
            ->each(function ($card, $index) {

                $card->update([
                    'sort_order' => $index + 1,
                ]);

            });
    }

    public function duplicate(
        HomepageCard $card
    ): HomepageCard {
        Gate::authorize('replicate', $card);

        $copy = $card->replicate();

        $copy->title .= ' Copy';

        $copy->sort_order =
            HomepageCard::max('sort_order') + 1;

        $copy->save();

        return $copy;
    }

    public function toggle(
        HomepageCard $card
    ): HomepageCard {
        Gate::authorize('update', $card);

        $card->update([

            'is_active' => ! $card->is_active,

        ]);

        return $card->fresh();
    }
}
