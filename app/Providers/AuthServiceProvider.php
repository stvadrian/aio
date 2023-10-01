<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\MenuHeader;
use App\Models\MenuItem;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $nonce = hash('sha256', date('Y-m-d H:i:s'));
        view()->share('nonce', $nonce);

        view()->composer('*', function ($view) {
            $template = 'modernize';
            $menu_items = null;

            if (Auth::check()) {
                $profile_path = storage_path() . '/app/' . auth()->user()->profile_img;
                $profile_img = base64_encode(file_get_contents($profile_path));
                $base64_img = 'data: ' . mime_content_type($profile_path) . ';base64,' . $profile_img;
                auth()->user()->preview_profile = $base64_img;

                $menu_items = array();
                $menu_header = MenuHeader::where('menu_header_status', '1')
                    ->orWhere('id', 1)
                    ->get();
                foreach ($menu_header as $header) {
                    $menu_item = MenuItem::where('master_header', $header->id)
                        ->where('menu_item_status', '1')
                        ->where('hak_akses', '<=', auth()->user()->hak_akses)
                        ->orderBy('urutan')
                        ->get();
                    $menu_items[$header->menu_header_name] = $menu_item;
                }
            }

            $view->with([
                'template' => $template,
                'nonce' => View::shared('nonce'),
                'menu_items' => $menu_items,
            ]);
        });
    }
}
