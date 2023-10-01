<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\HakAkses;
use App\Models\Icon;
use App\Models\MenuHeader;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ITController extends Controller
{
    public function viewMenuHeader(Request $request)
    {
        if ($request->has('add')) {
            $headerName = $request->header_name;
            $headerStatus = $request->status;

            $header = MenuHeader::where('menu_header_name', $headerName)->first();
            if ($header) {
                return back()->with(['error' => 'Item Header Already Exist!']);
            }
            try {
                MenuHeader::create([
                    'menu_header_name' => $headerName,
                    'menu_header_status' => $headerStatus,
                ]);
                return back()->with(['success' => 'Success Insert Header!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed to Insert Header!']);
            }
        }

        if ($request->has('update')) {
            $headerName = $request->header_name;
            $headerStatus = $request->status;
            $headerId = $request->header_id;
            $header = MenuHeader::where('menu_header_name', $headerName)->first();
            if ($header) {
                return back()->with(['error' => 'Item Header Already Exist!']);
            }
            try {
                MenuHeader::whereId($headerId)->update([
                    'menu_header_name' => $headerName,
                    'menu_header_status' => $headerStatus,
                ]);
                return back()->with(['success' => 'Success Update Header!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed to Update Header!']);
            }
        }

        if ($request->has('delete')) {
            $headerId = $request->header_id;
            try {
                MenuHeader::whereId($headerId)->delete();
                return back()->with(['success' => 'Success Delete Header!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed to Delete Header!']);
            }
        }
        $headers = MenuHeader::all();
        $pageHeader = 'Menu Header Manager';
        return view('it.pages.menu_header', compact('pageHeader', 'headers'));
    }

    public function viewMenuItem(Request $request)
    {
        if ($request->has('add')) {
            $itemName = $request->item_name;
            $accessLink = $request->access_link;
            $fileName = $request->file_name;
            $functionName = $request->function_name;
            $itemStatus = $request->item_status;
            $hakAkses = $request->hak_akses;
            $masterHeaders = $request->master_header;
            $showDepartemens = $request->show_departemen;
            $menuIcon = $request->menu_icon;
            $urutan = $request->urutan;

            $item = MenuItem::where('menu_item_name', $itemName)
                ->orWhere('menu_item_link', $accessLink)
                ->orWhere('menu_function', $functionName)
                ->first();
            if ($item) {
                return back()->with(['error' => 'Item Already Exist!']);
            }

            foreach ($showDepartemens as $departemen) {
                foreach ($masterHeaders as $header) {
                    try {
                        MenuItem::create([
                            'master_header' => $header,
                            'menu_item_name' => $itemName,
                            'menu_item_link' => $accessLink,
                            'menu_item_file' => $fileName,
                            'menu_function' => $functionName,
                            'menu_item_status' => $itemStatus,
                            'menu_icon' => $menuIcon,
                            'modul_departemen' => $departemen,
                            'hak_akses' => $hakAkses,
                            'urutan' => $urutan,
                        ]);
                    } catch (\Throwable $th) {
                        return back()->with(['error' => "Failed to Insert Menu $itemName!"]);
                    }
                }

                $fileDir = resource_path() . "/views/$departemen/pages/";
                $file = $fileDir . "$fileName.blade.php";
                if (is_file($file)) {
                    return back()->with(['error' => "File $fileName already exists!"]);
                }
                if (!is_dir($fileDir)) {
                    mkdir($fileDir, 0777, true);
                }

                $content = resource_path() . '/views/template.blade.php';
                if (copy($content, $file) < 20) {
                    return back()->with(['error' => "Failed to Create File For $fileName"]);
                }
            }
            return back()->with(['success' => 'Success Insert Menu!']);
        }

        if ($request->has('update')) {
            $itemId = $request->item_id;
            $itemName = $request->item_name;
            $accessLink = $request->access_link;
            $fileName = $request->file_name;
            $functionName = $request->function_name;
            $itemStatus = $request->item_status;
            $hakAkses = $request->hak_akses;
            $masterHeader = $request->master_header;
            $menuIcon = $request->menu_icon_update;
            $urutan = $request->urutan;
            try {
                MenuItem::whereId($itemId)->update([
                    'master_header' => $masterHeader,
                    'menu_item_name' => $itemName,
                    'menu_item_link' => $accessLink,
                    'menu_item_file' => $fileName,
                    'menu_function' => $functionName,
                    'menu_item_status' => $itemStatus,
                    'menu_icon' => $menuIcon,
                    'hak_akses' => $hakAkses,
                    'urutan' => $urutan,
                ]);
                return back()->with(['success' => 'Success Update Item!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed to Update Item!']);
            }
        }

        if ($request->has('delete')) {
            $item_id = $request->item_id;
            try {
                MenuItem::whereId($item_id)->delete();
                return back()->with(['success' => 'Success Delete Item!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed to Delete Item!']);
            }
        }

        $items = MenuItem::with('menuHeader')->get();
        $hakAkses = HakAkses::all();
        foreach ($items as $item) {
            $kdAksesItem = $item->hak_akses;
            $matchingAkses = $hakAkses->first(function ($akses) use ($kdAksesItem) {
                return $akses->kd_hak_akses == $kdAksesItem;
            });
            $item->hak_akses = $matchingAkses;
        }
        $headers = MenuHeader::all();
        $icons = Icon::all();
        $departemens = Departemen::all();
        $pageHeader = 'Menu Item Manager';

        return view('it.pages.menu_item', compact('pageHeader', 'items', 'headers', 'hakAkses', 'icons', 'departemens'));
    }

    public function viewIcons(Request $request)
    {
        if ($request->has('add')) {
            $icon_name = $request->icon_name;
            $icon_code = $request->icon_code;

            $check = Icon::where('icons_code', $icon_code)->first();
            if ($check) {
                return back()->with(['error' => 'Icon Already Exist!']);
            }

            try {
                Icon::create([
                    'icons_name' => $icon_name,
                    'icons_code' => $icon_code
                ]);
                return back()->with(['success' => 'Success Insert Icon!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Insert Icon!']);
            }
        }

        if ($request->has('update')) {
            $icon_id = $request->icon_id;
            $icon_name = $request->icon_name;
            $icon_code = $request->icon_code;

            try {
                Icon::whereId($icon_id)
                    ->update([
                        'icons_name' => $icon_name,
                        'icons_code' => $icon_code
                    ]);
                return back()->with(['success' => 'Success Update Icon!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update Icon!']);
            }
        }

        if ($request->has('delete')) {
            $icon_id = $request->icon_id;
            try {
                Icon::whereId($icon_id)->delete();
                return back()->with(['success' => 'Success Delete Icon!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Delete Icon!']);
            }
        }

        $icons = Icon::all();
        $pageHeader = 'Icon Manager';
        return view('it.pages.icon_manager', compact('icons', 'pageHeader'));
    }

    public function viewUsers(Request $request)
    {
        if ($request->has('add')) {
            $username = $request->username;
            $mobile = $request->mobile;

            $user = User::where('username', $username)
                ->orWhere('mobile_user', $mobile)
                ->first();
            if ($user) {
                return back()->with(['error' => 'Username or Mobile Already Exist! Try Another One'])->withInput($request->input());
            }

            $nm_user = $request->nama_lengkap;
            $dob = $request->dob;
            $dob = $this->normalizeDate($dob);
            $departemen = $request->departemen;
            $hak_akses = $request->hak_akses;
            $cabang = $request->cabang;

            try {
                User::create([
                    'username' => $username,
                    'nm_user' => $nm_user,
                    'dob_user' => $dob,
                    'mobile_user' => $mobile,
                    'kd_departemen' => $departemen,
                    'hak_akses' => $hak_akses,
                    'kd_cabang' => $cabang,
                    'password' => bcrypt("Corp12345$"),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                return back()->with(['success' => 'Success Insert New User!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Insert New User!'])->withInput($request->input());
            }
        }

        if ($request->has('update')) {
            $user_id = $request->user_id;
            $mobile = $request->mobile;

            $user = User::where('mobile_user', $mobile)->first();
            if ($user && $user->id != $user_id) {
                return back()->with(['error' => 'Mobile Already Exist! Try Another One']);
            }

            $nm_user = $request->nama_lengkap;
            $dob = $request->dob;
            $dob = $this->normalizeDate($dob);
            $departemen = $request->departemen;
            $hak_akses = $request->hak_akses;
            $cabang = $request->cabang;
            try {
                User::whereId($user_id)->update([
                    'nm_user' => $nm_user,
                    'dob_user' => $dob,
                    'mobile_user' => $mobile,
                    'kd_departemen' => $departemen,
                    'hak_akses' => $hak_akses,
                    'kd_cabang' => $cabang,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                return back()->with(['success' => 'Success Update User!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update User!']);
            }
        }

        if ($request->has('impersonate')) {
            $id = $request->impersonate;
            $original_id = auth()->user()->id;
            auth()->logout();
            auth()->loginUsingId($id);

            $nama = auth()->user()->nm_user;
            session()->put(['impersonator' => $original_id]);

            return redirect('/dashboard')->with(['success' => "Now Logged as $nama"]);
        }

        if ($request->has('delete')) {
            $user_id = $request->user_id;
            try {
                User::whereId($user_id)->delete();
                return back()->with(['success' => 'Success Delete User!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Delete User!']);
            }
        }

        $departemens = Departemen::all();
        $hak_akses = HakAkses::all();
        $cabangs = Cabang::all();
        $users = User::with('departemen')->with('hakAkses')->with('cabang')->get();
        foreach ($users as $user) {
            $user->dob_user = date('d / m / Y', strtotime($user->dob_user));
        }
        $pageHeader = 'User Manager';
        return view('it.pages.user_manager', compact('pageHeader', 'users', 'departemens', 'hak_akses', 'cabangs'));
    }

    public function viewDepartemen(Request $request)
    {
        if ($request->has('add')) {
            $kd_departemen = $request->kd_departemen;
            $nm_departemen = $request->nm_departemen;
            $modul = $request->modul;
            $controller = $request->controller;

            try {
                Departemen::create([
                    'kd_departemen' => $kd_departemen,
                    'nm_departemen' => $nm_departemen,
                    'modul' => $modul,
                    'controller' => $controller,
                    'kd_cabang' => auth()->user()->kd_cabang,
                ]);
                return back()->with(['success' => 'Success Add Departemen!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Add Departemen!']);
            }
        }

        if ($request->has('update')) {
            $departemen_id = $request->departemen_id;
            $nm_departemen = $request->nm_departemen;
            $modul = $request->modul;
            $controller = $request->controller;

            try {
                Departemen::whereId($departemen_id)->update([
                    'nm_departemen' => $nm_departemen,
                    'modul' => $modul,
                    'controller' => $controller,
                ]);
                return back()->with(['success' => 'Success Update Departemen!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update Departemen!']);
            }
        }

        if ($request->has('delete')) {
            $departemen_id = $request->departemen_id;
            try {
                Departemen::whereId($departemen_id)->delete();
                return back()->with(['success' => 'Success Delete Departemen!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Delete Departemen!']);
            }
        }

        $departemens = Departemen::all();
        $pageHeader = 'Departemen Manager';
        return view('it.pages.departemen_manager', compact('departemens', 'pageHeader'));
    }

    public function viewBranches(Request $request)
    {
        if ($request->has('add')) {
            try {
                Cabang::create([
                    'kd_cabang' => $request->input('kd_cabang'),
                    'nm_cabang' => $request->input('nm_cabang'),
                ]);
                return back()->with(['success' => 'Success Add Branch!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Add Branch!']);
            }
        }
        if ($request->has('update')) {
            $cabangId = $request->input('cabang_id');
            try {
                Cabang::whereId($cabangId)->update([
                    'nm_cabang' => $request->input('nm_cabang'),
                ]);
                return back()->with(['success' => 'Success Update Branch!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update Branch!']);
            }
        }
        if ($request->has('delete')) {
            $cabangId = $request->input('cabang_id');
            try {
                Cabang::whereId($cabangId)->delete();
                return back()->with(['success' => 'Success Delete Branch!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Delete Branch!']);
            }
        }
        $pageHeader = 'Branch Manager';
        $cabangs = Cabang::all();
        return view('it.pages.branch_manager', compact('pageHeader', 'cabangs'));
    }

    public function viewFormItemMaster(Request $request)
    {
        if ($request->has('add')) {
            $item_name = $request->item_name;
            $item_type = $request->item_type;
            $item_category = $request->item_category;
            try {
                DB::table('automate_forms_item_master')->insert([
                    'item_name' => $item_name,
                    'item_type' => $item_type,
                    'item_category' => $item_category,
                ]);
                return back()->with(['success' => 'Success Add Item Form!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Add Item Form!']);
            }
        }
        if ($request->has('update')) {
            $item_id = $request->item_id;
            $item_name = $request->item_name;
            $item_type = $request->item_type;
            $item_category = $request->item_category;
            try {
                DB::table('automate_forms_item_master')->where('id', $item_id)->update([
                    'item_name' => $item_name,
                    'item_type' => $item_type,
                    'item_category' => $item_category,
                ]);
                return back()->with(['success' => 'Success Update Item Form!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update Item Form!']);
            }
        }
        if ($request->has('delete')) {
            $item_id = $request->item_id;
            try {
                DB::table('automate_forms_item_master')->where('id', $item_id)->delete();
                return back()->with(['success' => 'Success Delete Item Form!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Delete Item Form!']);
            }
        }
        $items = DB::table('automate_forms_item_master')->get();
        $categories = DB::table('automate_forms_item_master')
            ->select('item_category')
            ->groupBy('item_category')
            ->get();
        $pageHeader = 'Automatic Form Item Master';
        return view('it.pages.form_item_master', compact('pageHeader', 'items', 'categories'));
    }

    public function viewHakAkses(Request $request)
    {
        if ($request->has('add')) {
            try {
                HakAkses::create([
                    'kd_hak_akses' => $request->input('kd_hak_akses'),
                    'nm_hak_akses' => $request->input('nm_hak_akses'),
                ]);
                return back()->with(['success' => 'Success Add Access!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Add Access!']);
            }
        }
        if ($request->has('update')) {
            try {
                HakAkses::whereId($request->input('akses_id'))->update([
                    'nm_hak_akses' => $request->input('nm_hak_akses'),
                ]);
                return back()->with(['success' => 'Success Update Access!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update Access!']);
            }
        }
        if ($request->has('delete')) {
            try {
                HakAkses::whereId($request->input('akses_id'))->delete();
                return back()->with(['success' => 'Success Delete Access!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Delete Access!']);
            }
        }
        $pageHeader = 'Manage Access Level';
        $items = HakAkses::all();
        return view('it.pages.access_level_manager', compact('pageHeader', 'items'));
    }
}
