<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\HakAkses;
use App\Models\Icon;
use App\Models\MenuHeader;
use App\Models\MenuItem;
use App\Models\ThreadCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        User::firstOrCreate([
            'username' => 'admin',
            'nm_user' => 'Administrator',
            'dob_user' => '1970-01-01',
            'mobile_user' => '1',
            'kd_departemen' => '2',
            'hak_akses' => '3',
            'kd_cabang' => '1',
            'password' => bcrypt('admin')
        ]);
        User::firstOrCreate([
            'username' => 'admin2',
            'nm_user' => 'Administrator2',
            'dob_user' => '1970-01-01',
            'mobile_user' => '2',
            'kd_departemen' => '2',
            'hak_akses' => '3',
            'kd_cabang' => '1',
            'password' => bcrypt('admin')
        ]);



        $departemens = [
            array('nm_departemen' => 'General', 'modul' => 'umum', 'controller' => 'UserController'),
            array('nm_departemen' => 'Information Technology', 'modul' => 'it', 'controller' => 'ITController'),
            array('nm_departemen' => 'Outpatient', 'modul' => 'opd', 'controller' => 'OPDController'),
            array('nm_departemen' => 'Perawat', 'modul' => 'perawat', 'controller' => 'PerawatController'),
            array('nm_departemen' => 'Emergency', 'modul' => 'emergency', 'controller' => 'EDController'),
        ];
        $last_id = Departemen::count();
        foreach ($departemens as $departemen) {
            Departemen::firstOrCreate([
                'kd_departemen' => ++$last_id,
                'nm_departemen' => $departemen['nm_departemen'],
                'modul' => $departemen['modul'],
                'controller' => $departemen['controller'],
                'kd_cabang' => 1
            ]);
        }


        $cabangs = array(
            'RS1'
        );
        $last_id = Cabang::count();
        for ($i = 0; $i < sizeof($cabangs); $i++) {
            Cabang::firstOrCreate([
                'kd_cabang' => ++$last_id,
                'nm_cabang' => $cabangs[$i],
            ]);
        }

        $hak_akses = [
            array('kd_hak_akses' => '1', 'nm_hak_akses' => 'Staff'),
            array('kd_hak_akses' => '2', 'nm_hak_akses' => 'Supervisor'),
            array('kd_hak_akses' => '3', 'nm_hak_akses' => 'Administrator'),
        ];
        foreach ($hak_akses as $akses) {
            HakAkses::firstOrCreate([
                'kd_hak_akses' => $akses['kd_hak_akses'],
                'nm_hak_akses' => $akses['nm_hak_akses'],
            ]);
        }

        $menu_icons = [
            array('icons_name' => 'Dashboard', 'icons_code' => 'ti ti-layout-dashboard'),
            array('icons_name' => 'Article', 'icons_code' => 'ti ti-article'),
            array('icons_name' => 'Form', 'icons_code' => 'ti ti-forms'),
            array('icons_name' => 'Setting', 'icons_code' => 'ti ti-settings'),
            array('icons_name' => 'Users', 'icons_code' => 'ti ti-users'),
            array('icons_name' => 'User', 'icons_code' => 'ti ti-user'),
            array('icons_name' => 'Icons', 'icons_code' => 'ti ti-icons'),
            array('icons_name' => 'Menu', 'icons_code' => 'ti ti-menu'),
            array('icons_name' => 'Menu 2', 'icons_code' => 'ti ti-menu-2'),
            array('icons_name' => 'Building', 'icons_code' => 'ti ti-building'),
            array('icons_name' => 'Header', 'icons_code' => 'ti ti-heading'),
            array('icons_name' => 'Database', 'icons_code' => 'ti ti-database'),
            array('icons_name' => 'QR Code', 'icons_code' => 'ti ti-qrcode'),
            array('icons_name' => 'Message', 'icons_code' => 'ti ti-message'),
            array('icons_name' => 'Mail', 'icons_code' => 'ti ti-mail'),
            array('icons_name' => 'Discussion', 'icons_code' => 'ti ti-messages'),
            array('icons_name' => 'Confetti', 'icons_code' => 'ti ti-confetti'),
            array('icons_name' => 'Prompt', 'icons_code' => 'ti ti-prompt'),
            array('icons_name' => 'Report', 'icons_code' => 'ti ti-report'),
            array('icons_name' => 'Branch', 'icons_code' => 'ti ti-sitemap'),
            array('icons_name' => 'Access Point', 'icons_code' => 'ti ti-access-point'),
            array('icons_name' => '123', 'icons_code' => 'ti ti-123'),
        ];
        foreach ($menu_icons as $icon) {
            Icon::firstOrCreate([
                'icons_name' => $icon['icons_name'],
                'icons_code' => $icon['icons_code'],
            ]);
        }

        $menu_headers = [
            array('menu_header_name' => 'General'),
            array('menu_header_name' => 'Data Manager'),
        ];
        foreach ($menu_headers as $header) {
            MenuHeader::create([
                'menu_header_name' => $header['menu_header_name'],
                'menu_header_status' => 1
            ]);
        }

        $menu_items = [
            array('master_header' => 1, 'menu_item_name' => 'Dashboard', 'menu_item_link' => 'dashboard', 'menu_item_file' => 'dashboard', 'menu_function' => 'viewDashboard', 'menu_icon' => 'ti ti-layout-dashboard', 'hak_akses' => 1, 'modul_departemen' => 'umum'),
            array('master_header' => 1, 'menu_item_name' => 'Forms', 'menu_item_link' => 'forms', 'menu_item_file' => 'index', 'menu_function' => 'viewDashboard', 'menu_icon' => 'ti ti-forms', 'hak_akses' => 1, 'modul_departemen' => 'umum'),
            array('master_header' => 1, 'menu_item_name' => 'QR Generator', 'menu_item_link' => 'qr-generator', 'menu_item_file' => 'index', 'menu_function' => 'viewQRGenerator', 'menu_icon' => 'ti ti-qrcode', 'hak_akses' => 1, 'modul_departemen' => 'umum'),
            array('master_header' => 1, 'menu_item_name' => 'Forum', 'menu_item_link' => 'forum', 'menu_item_file' => 'category_list', 'menu_function' => 'viewForumCategory', 'menu_icon' => 'ti ti-messages', 'hak_akses' => 1, 'modul_departemen' => 'umum'),
            array('master_header' => 2, 'menu_item_name' => 'Menu Header Manager', 'menu_item_link' => 'menu-header-manager', 'menu_item_file' => 'menu_header', 'menu_function' => 'viewMenuHeader', 'menu_icon' => 'ti ti-heading', 'hak_akses' => 3, 'modul_departemen' => 'it'),
            array('master_header' => 2, 'menu_item_name' => 'Menu Item Manager', 'menu_item_link' => 'menu-item-manager', 'menu_item_file' => 'menu_item', 'menu_function' => 'viewMenuItem', 'menu_icon' => 'ti ti-article', 'hak_akses' => 3, 'modul_departemen' => 'it'),
            array('master_header' => 2, 'menu_item_name' => 'Icon Manager', 'menu_item_link' => 'icon-manager', 'menu_item_file' => 'icon_manager', 'menu_function' => 'viewIcons', 'menu_icon' => 'ti ti-icons', 'hak_akses' => 3, 'modul_departemen' => 'it'),
            array('master_header' => 2, 'menu_item_name' => 'Branch Manager', 'menu_item_link' => 'branch-manager', 'menu_item_file' => 'branch_manager', 'menu_function' => 'viewBranches', 'menu_icon' => 'ti ti-sitemap', 'hak_akses' => 3, 'modul_departemen' => 'it'),
            array('master_header' => 2, 'menu_item_name' => 'Departemen Manager', 'menu_item_link' => 'departemen', 'menu_item_file' => 'departemen_manager', 'menu_function' => 'viewDepartemen', 'menu_icon' => 'ti ti-building', 'hak_akses' => 3, 'modul_departemen' => 'it'),
            array('master_header' => 2, 'menu_item_name' => 'Access Level Manager', 'menu_item_link' => 'access-level', 'menu_item_file' => 'access_level_manager', 'menu_function' => 'viewHakAkses', 'menu_icon' => 'ti ti-123', 'hak_akses' => 3, 'modul_departemen' => 'it'),
            array('master_header' => 2, 'menu_item_name' => 'User Manager', 'menu_item_link' => 'user-manager', 'menu_item_file' => 'user_manager', 'menu_function' => 'viewUsers', 'menu_icon' => 'ti ti-users', 'hak_akses' => 3, 'modul_departemen' => 'it'),
            array('master_header' => 2, 'menu_item_name' => 'Form Item Master', 'menu_item_link' => 'form-item-master', 'menu_item_file' => 'form_item_master', 'menu_function' => 'viewFormItemMaster', 'menu_icon' => 'ti ti-database', 'hak_akses' => 3, 'modul_departemen' => 'it'),
        ];
        foreach ($menu_items as $item) {
            MenuItem::create([
                'master_header' => $item['master_header'],
                'menu_item_name' => $item['menu_item_name'],
                'menu_item_link' => $item['menu_item_link'],
                'menu_item_file' => $item['menu_item_file'],
                'menu_function' => $item['menu_function'],
                'menu_item_status' => 1,
                'menu_icon' => $item['menu_icon'],
                'hak_akses' => $item['hak_akses'],
                'urutan' => 9,
                'modul_departemen' => $item['modul_departemen'],
            ]);
        }


        $form_items = [
            array('item_name' => 'Free Text', 'item_type' => 'text', 'item_category' => 'Texts'),
            array('item_name' => 'Alphabet Only Text', 'item_type' => 'textAlpha', 'item_category' => 'Texts'),
            array('item_name' => 'Expandable Free Text', 'item_type' => 'textarea', 'item_category' => 'Texts'),
            array('item_name' => 'Integer', 'item_type' => 'number', 'item_category' => 'Numbers'),
            array('item_name' => 'Number Text', 'item_type' => 'numberOnly', 'item_category' => 'Numbers'),
            array('item_name' => 'Checkbox', 'item_type' => 'checkbox', 'item_category' => 'Options'),
            array('item_name' => 'Bullets', 'item_type' => 'radio', 'item_category' => 'Options'),
            array('item_name' => 'Selection', 'item_type' => 'select', 'item_category' => 'Options'),
            array('item_name' => 'Searchable Selection', 'item_type' => 'select2', 'item_category' => 'Options'),
            array('item_name' => 'Multiple Searchable Selection', 'item_type' => 'select2multiple', 'item_category' => 'Options'),
            array('item_name' => 'Free Type Date', 'item_type' => 'customDate', 'item_category' => 'Times'),
            array('item_name' => 'Date', 'item_type' => 'date', 'item_category' => 'Times'),
            array('item_name' => 'Time', 'item_type' => 'time', 'item_category' => 'Times'),
            array('item_name' => 'Date & Time', 'item_type' => 'datetime', 'item_category' => 'Times'),
            array('item_name' => 'Title', 'item_type' => 'title', 'item_category' => 'Others'),
            array('item_name' => 'Range', 'item_type' => 'range', 'item_category' => 'Others'),
            array('item_name' => 'File', 'item_type' => 'file', 'item_category' => 'Others'),
        ];
        foreach ($form_items as $item) {
            DB::table('automate_forms_item_master')->insert([
                'item_name' => $item['item_name'],
                'item_type' => $item['item_type'],
                'item_category' => $item['item_category'],
            ]);
        }


        $thread_categories = [
            array('category_name' => 'Welcome', 'category_icon' => 'ti ti-confetti', 'category_description' => 'Introduce yourself to our community'),
            array('category_name' => 'Issues', 'category_icon' => 'ti ti-report', 'category_description' => 'Drop all your issues and complaints here'),
            array('category_name' => 'Projects', 'category_icon' => 'ti ti-prompt', 'category_description' => 'Manage your projects progress with your team'),
        ];
        foreach ($thread_categories as $category) {
            ThreadCategory::firstOrCreate([
                'category_name' => $category['category_name'],
                'category_icon' => $category['category_icon'],
                'category_description' => $category['category_description'],
            ]);
        }
    }
}
