<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $role string
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {

        if (!Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Not authenticated!'], 401);
            } else {
                return redirect('login')->with('not_authenticated', url()->current());
            }
        }
        $user_role = $request->user()->user_role;
        $val = intval($role);
        $page_role = UserRole::getInstance($val);

        if (!UserRole::checkPermission($page_role, $user_role)) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'You doesn\'t have permission to request this page!'], 401);
            } else {
                return redirect('/home')->with('status', 'Você não tem permissão para ver a pagina que solicitou!');
            }
        }

        return $next($request);
    }
}
