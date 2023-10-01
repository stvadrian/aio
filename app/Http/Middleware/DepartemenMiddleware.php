<?php

namespace App\Http\Middleware;

use App\Models\Departemen;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DepartemenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $params): Response
    {
        $user_dept = Departemen::where('kd_departemen', auth()->user()->kd_departemen)->first();
        if ($user_dept) {
            $user_access_lvl = auth()->user()->hak_akses;
            $params_arr = explode('///', $params);
            $modul = $params_arr[0];
            if (count($params_arr) > 1) {
                $page_access_lvl = $params_arr[1];
            } else {
                $page_access_lvl = 1;
            }

            if ((str_contains(strtolower($modul), strtolower($user_dept->modul)) && $user_access_lvl >= $page_access_lvl)) {
                if (url()->previous() != $request->url()) {
                    session()->forget(['date_s', 'date_e', 'data_set']);
                }
                return $next($request);
            }
        }

        return redirect('error/403');
    }
}
