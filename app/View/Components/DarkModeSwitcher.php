<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DarkModeSwitcher extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id = 'theme-toggle'
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.dark-mode-switcher');
    }
}
