<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Team;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamsPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        /** @var Team|null $team */
        $team = Filament::getTenant() ?? $request->user()?->currentTeam;

        if ($team) {
            Team::setCurrent($team);
        }

        abort_if($team && auth()->user() && ! auth()->user()->canAccessTenant($team), 403, __('You do not have permission to access this team.'));

        if (auth()->user() && $team) {
            auth()->user()->switch($team);
        }

        return $next($request);
    }
}
