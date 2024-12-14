<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController
{
    /**
     * Render the dashboard view.
     */
    public function __invoke(): View
    {
        return view('dashboard');
    }
}
