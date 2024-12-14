<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class MeetupsTabs extends Component
{
    /**
     * The active tab.
     */
    #[Url(as: 'meetups', keep: true)]
    public string $activeTab = 'upcoming';

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('livewire.meetups-tabs');
    }
}
