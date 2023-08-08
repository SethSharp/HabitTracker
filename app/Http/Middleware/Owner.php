<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Habit;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Owner
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route('id')) {
            $habit = Habit::withTrashed()->find($request->route('id'));

            if (auth()->user()->can('update', $habit)) {
                ray('can update');
                return $next($request);
            }
        } elseif ($request->route('habit')) {

            if (auth()->user()->can('update', $request->route('habit'))) {
                ray('can update');
                return $next($request);
            }
        }

        throw new HttpException(
            403,
            'Unauthorized access for this page',
        );
    }
}
