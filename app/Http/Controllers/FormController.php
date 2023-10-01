<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Forwarding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDO;
use Throwable;

class FormController extends Controller
{
    public function viewQRCodeForm($form_name_e)
    {
        $check_qr = Form::where('form_name_e', $form_name_e)->first();
        if ($check_qr) {
            $path_qr = $check_qr->qr_code;
            if ($path_qr == null || $path_qr == '' || !file_exists($path_qr)) {
                $link_form = $check_qr->link_form;
                $path_qr = $this->generateQR($link_form, storage_path() . "/app/$form_name_e/", "QR_form");
                if (!$path_qr) {
                    return redirect('/error/500');
                }
                try {
                    Form::whereId($check_qr->id)->update(['qr_code' => $path_qr]);
                } catch (\Throwable $th) {
                    return redirect('/error/500');
                }
            }
            try {
                $qr_img = $this->encodeImage($path_qr);
            } catch (\Throwable $th) {
                $qr_img = null;
            }
            return view('umum.forms.pages.qr_view')->with(['form' => $check_qr, 'qr_img' => $qr_img]);
        }
        return redirect('/error/404');
    }

    public function viewForms(Request $request)
    {
        if ($request->has('add')) {
            $form_name = $request->form_name;
            $form_desc = $request->form_description;
            $form_status = $request->form_status;
            $form_name_e = $this->sanitizeFormName($form_name);
            $link_form = $this->baselink . "/$this->rootname/forms/view/$form_name_e";

            $check = Form::where('form_name_e', $form_name_e)->first();
            if ($check) {
                return back()->with(['error' => 'Form Name Already Exists. <br> Try Another One.']);
            }

            try {
                Form::create([
                    'form_name' => $form_name,
                    'form_name_e' => $form_name_e,
                    'link_form' => $link_form,
                    'description' => $form_desc,
                    'status' => $form_status,
                    'created_by' => auth()->user()->id
                ]);

                $db_driver = DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME);
                Schema::connection($db_driver)->create("automate_form_$form_name_e", function ($table) {
                    $table->increments('id');
                    $table->string('item_name');
                    $table->string('item_name_e');
                    $table->string('item_type');
                    $table->integer('item_order');
                    $table->integer('item_mandatory');
                    $table->text('item_options');
                });

                Schema::connection($db_driver)->create("automate_form_post_$form_name_e", function ($table) {
                    $table->increments('id');
                    $table->timestamp('created_at')->useCurrent();
                });
                return back()->with('success', 'Success Create Form!');
            } catch (Throwable $th) {
                return back()->with('error', 'Failed Create Form!');
            }
        }

        if ($request->has('delete')) {
            $form_id = $request->form_id;

            try {
                $form = Form::whereId($form_id)->first();
                $form_name_e = $form->form_name_e;
                $table_name = "automate_form_$form_name_e";
                $table_post = "automate_form_post_$form_name_e";
                Schema::drop($table_name);
                Schema::drop($table_post);
                Form::whereId($form_id)->delete();

                return back()->with(['success' => 'Success Delete Form!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Delete Form!']);
            }
        }

        $forms = Form::select(['automate_forms.*', 'users.nm_user'])
            ->join('users', 'users.id', 'automate_forms.created_by')
            ->get();
        $pageHeader = 'Form List';
        return view('umum.forms.index', compact('pageHeader', 'forms'));
    }

    public function viewForm($link_form)
    {
        $link = $this->baselink . '/' . $this->rootname . '/forms/view/' . $link_form;
        $form = DB::table('automate_forms')->where('link_form', "$link")->first();

        if ($form && $form->status == '1') {
            $form_name_e = $this->sanitizeFormName($form->form_name);
            $form_items = DB::table("automate_form_$form_name_e")->orderBy('item_order')->get();

            $i = 0;
            $titles = [];
            $title_contents = [];

            foreach ($form_items as $item) {
                if ($i == 0) {
                    $i++;
                    continue;
                } else {
                    if ($item->item_type != 'title') {
                        array_push($title_contents, array($item->item_name, $item->item_type, $item->item_mandatory, $item->item_options));
                    } else {
                        array_push($titles, array($title_contents));
                        $title_contents = [];
                    }
                    if ($i + 1 == sizeof($form_items)) {
                        array_push($titles, array($title_contents));
                    }
                    $i++;
                }
            }
            // if ($form->background_path == '') {
            //     $form->background_path = storage_path() . "/background_forms/blob.svg";
            // }
            try {
                $form_background = $this->encodeImage($form->background_path);
            } catch (\Throwable $th) {
                $form_background = null;
            }
            return view('umum.forms.template_form')->with(['form_items' => $form_items, 'form_name' => $link_form, 'titles' => $titles, 'form' => $form, 'form_background' => $form_background]);
        }
        return redirect('/errors/404');
    }

    public function editForm(Request $request, $form_name_e)
    {

        if ($request->has('bg_form')) {
            $file = $request->file('bg_form');
            $extension = $file->getClientOriginalExtension();
            $filename = "$form_name_e.$extension";

            $background_form_dir = storage_path() . "/app/$form_name_e/";
            if (!is_dir($background_form_dir)) {
                mkdir($background_form_dir, 0777, true);
            }
            $path = $file->storeAs($form_name_e, $filename);
            if ($path) {
                $path = storage_path() . "/app/$path";
                try {
                    DB::table('automate_forms')
                        ->where('form_name_e', $form_name_e)
                        ->update([
                            'background_path' => $path
                        ]);
                    return back()->with(['success' => 'Update Background Success!']);
                } catch (\Throwable $th) {
                    return back()->with(['error' => 'Failed Update Background!']);
                }
            } else {
                return back()->with(['error' => 'Failed Storing Background!']);
            }
        }

        if ($request->has('add_forwarding')) {
            $fw_name = $request->forward_name;
            $fw_link = $request->forward_link;
            $form_id = $request->form_id;

            try {
                Forwarding::create([
                    'form_id' => $form_id,
                    'fw_name' => $fw_name,
                    'fw_link' => $fw_link
                ]);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Add Forwarding!']);
            }
            return back()->with(['success' => 'Success Add Forwarding!']);
        }

        if ($request->has('update_forwarding')) {
            $fw_name = $request->forward_name;
            $fw_link = $request->forward_link;
            $fw_id = $request->fw_id;

            try {
                Forwarding::whereId($fw_id)->update([
                    'fw_name' => $fw_name,
                    'fw_link' => $fw_link
                ]);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed to Update Forwarding!']);
            }
            return back()->with(['success' => 'Success Update Forwarding!']);
        }

        if ($request->has('delete_forwarding')) {
            $fw_id = $request->fw_id;
            try {
                Forwarding::whereId($fw_id)->delete();
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed to Delete Forwarding!']);
            }
            return back()->with(['success' => 'Success Delete Forwarding!']);
        }

        if ($request->has('add_form_item')) {
            $item_name = $request->item_name;
            $column_name = strtolower(str_replace(' ', '_', $item_name));
            $item_type = $request->item_type;
            $item_mandatory = $request->is_mandatory;
            $orders = explode('/', $request->item_order);
            $item_order = $orders[0];
            $max_order = $orders[1];

            $form_name = $request->form_name;

            $dataType = $this->determineDataType($item_type);

            $check_item = DB::table("automate_form_$form_name")
                ->where('item_name', $item_name)
                ->first();

            if (!$check_item) {
                try {
                    if ($max_order != 0) {
                        if ($item_order == 1) {
                            DB::table("automate_form_$form_name")->increment('item_order', 1);
                        } elseif ($item_order <= $max_order) {
                            DB::table("automate_form_$form_name")->where('item_order', '>=', $item_order)->increment('item_order',  1);
                        }
                    }
                    DB::table("automate_form_$form_name")->insert([
                        'item_name' => $item_name,
                        'item_name_e' => $column_name,
                        'item_type' => $item_type,
                        'item_order' => $item_order,
                        'item_mandatory' => $item_mandatory,
                        'item_options' => ''
                    ]);

                    if ($item_type != 'title') {
                        DB::statement("ALTER TABLE automate_form_post_$form_name ADD $column_name $dataType");
                    }

                    return back()->with(['success' => 'Add Item Successful!']);
                } catch (Throwable $th) {
                    return back()->with(['error' => 'Failed to Add Item!']);
                }
            }
            return back()->with(['warning' => 'Item Already Exist! \n Try Different Names']);
        }

        if ($request->has('delete_item_form')) {
            $item_id = $request->item_id;
            $form_name_e = $request->form_name_e;

            $check_item = DB::table("automate_form_$form_name_e")->where('id', $item_id)->first();

            if ($check_item) {
                $column_post = $check_item->item_name_e;
                $item_order = $check_item->item_order;
                try {
                    DB::table("automate_form_$form_name_e")->whereId($item_id)->delete();

                    // UPDATE ORDER AFTER DELETE
                    DB::table("automate_form_$form_name_e")->where('item_order', '>', $item_order)->decrement('item_order',  1);
                    // DELETE COLUMN POST
                    if ($check_item->item_type != 'title') {
                        DB::statement("ALTER TABLE automate_form_post_$form_name_e DROP COLUMN $column_post");
                    }

                    return back()->with(['success' => 'Success Delete Item!']);
                } catch (Throwable $th) {
                    return back()->with(['error' => 'Failed Delete Item!']);
                }
            }
            return back()->with(['error' => 'Unable to delete item. \nContact your administrator.']);
        }

        if ($request->has('update_item_form')) {
            $item_name = $request->item_name;
            $item_type = $request->item_type;
            $item_id = $request->item_id;
            $orders = explode('/', $request->item_order);
            $item_order = $orders[0];
            $max_order = $orders[1];
            $current_order = $request->current_order;
            $item_mandatory = $request->is_mandatory;

            $form_name_e = $request->form_name_e;
            $column_name = strtolower(str_replace(' ', '_', $item_name));
            $dataType = $this->determineDataType($item_type);

            try {
                // UPDATE ITEM ROW
                if ($item_order != $current_order) {
                    if ($item_order < $current_order) {
                        DB::table("automate_form_$form_name_e")
                            ->where('item_order', '>=', $item_order)
                            ->where('item_order', '<', $current_order)
                            ->increment('item_order', 1);
                    } else {
                        DB::table("automate_form_$form_name_e")
                            ->where('item_order', '<=', $item_order)
                            ->where('item_order', '>', $current_order)
                            ->decrement('item_order', 1);
                    }
                }

                $columns = DB::table("automate_form_$form_name_e")->where('id', $item_id)->first();
                $column_post = strtolower(str_replace(' ', '_', $columns->item_name));

                if ($item_type != 'title') {
                    // DELETE COLUMN POST
                    DB::statement("ALTER TABLE automate_form_post_$form_name_e DROP COLUMN $column_post");
                    DB::statement("ALTER TABLE automate_form_post_$form_name_e ADD $column_name $dataType");
                }

                DB::table("automate_form_$form_name_e")->where('id', $item_id)->update([
                    'item_name' => $item_name,
                    'item_name_e' => $column_name,
                    'item_type' => $item_type,
                    'item_order' => $item_order,
                    'item_mandatory' => $item_mandatory
                ]);

                return back()->with(['success' => 'Update Item Successful!']);
            } catch (Throwable $th) {
                return back()->with(['error' => 'Failed to Update Item!']);
            }
        }

        $check = Form::where('form_name_e', $form_name_e)->first();
        if (!$check) {
            return redirect('error/404');
        }

        $form_items = DB::table("automate_form_$form_name_e")->orderBy('item_order')->get();
        $form = DB::table("automate_forms")->where('form_name_e', $form_name_e)->first();
        $item_categories = DB::table('automate_forms_item_master')
            ->select('item_category', DB::raw('MAX(id) as max_id'))
            ->groupBy('item_category')
            ->orderBy('max_id')
            ->get();
        foreach ($item_categories as $category) {
            $item_types = DB::table('automate_forms_item_master')
                ->where('item_category', $category->item_category)
                ->get();
            $category->item_category_child = $item_types;
        }

        if ($form->background_path) {
            try {
                $base64 = $this->encodeImage($form->background_path);
            } catch (\Throwable $th) {
                $base64 = null;
            }
        } else {
            $base64 = null;
        }

        $forwards = DB::table('forwardings')->where('form_id', $form->id)->get();
        return view('umum.forms.pages.edit_form')->with([
            'page_header' => $form->form_name . ' Detail',
            'items' => $form_items,
            'form' => $form,
            'form_bg' => $base64,
            'item_categories' => $item_categories,
            'forwards' => $forwards
        ]);
    }

    public function showOptions(Request $request, $form_name_e, $item_name)
    {
        if ($request->has('add_options')) {
            $item_id = $request->item_id;
            $item_option = $request->add_item_option;
            if (str_contains($item_option, '/')) {
                return back()->with(['error' => 'Sorry! Option cannot contains slashes.']);
            }
            $item_option = addslashes($item_option);

            try {
                $item = DB::table("automate_form_$form_name_e")->whereId($item_id)->first();
            } catch (\Throwable $th) {
                return redirect('/error/500');
            }

            if ($item->item_options != '') {
                $options = explode('///', $item->item_options);
                foreach ($options as $option) {
                    if ($item_option == $option) {
                        return back()->with(['error' => 'Option already exist!']);
                    }
                }
                $item_option = "$item->item_options///$item_option";
            }

            try {
                DB::table("automate_form_$form_name_e")->whereId($item_id)->update([
                    'item_options' => $item_option
                ]);
                return back()->with(['success' => 'Add Option Successful!']);
            } catch (Throwable $th) {
                return back()->with(['error' => 'Failed to Add Option!']);
            }
        }

        if ($request->has('delete_options')) {
            $item_id = $request->item_id;
            $item_option = $request->item_option;
            try {
                $item = DB::table("automate_form_$form_name_e")->whereId($item_id)->first();
                if (str_contains($item->item_options, '///')) {
                    $new_item_opt = str_replace(["$item_option///", "///$item_option"], '', $item->item_options);
                } else {
                    $new_item_opt = str_replace(["$item_option"], '', $item->item_options);
                }
            } catch (\Throwable $th) {
                return redirect('/errors/500');
            }

            try {
                DB::table("automate_form_$form_name_e")->whereId($item_id)->update([
                    'item_options' => $new_item_opt
                ]);
                return back()->with(['success' => 'Delete Option Successful!']);
            } catch (Throwable $th) {
                return back()->with(['error' => 'Failed to Delete Option!']);
            }
        }

        if ($request->has('delete_options_selected')) {
            $item_id = $request->item_id;
            $item_option = $request->selected_option;

            try {
                $item = DB::table("automate_form_$form_name_e")->whereId($item_id)->first();
                $count = count($item_option);
                foreach ($item_option as $option) {
                    if ($count > 1) {
                        $item->item_options = str_replace(["$option///", "///$option"], '', $item->item_options);
                        $new_item_opt = $item->item_options;
                    } else {
                        $new_item_opt = str_replace(["$option"], '', $item->item_options);
                    }
                    $count--;
                }
            } catch (\Throwable $th) {
                return redirect('/error/500');
            }

            try {
                DB::table("automate_form_$form_name_e")->whereId($item_id)->update([
                    'item_options' => $new_item_opt
                ]);
                return back()->with(['success' => 'Delete Option Successful!']);
            } catch (Throwable $th) {
                return back()->with(['error' => 'Failed to Delete Option!']);
            }
        }

        if ($request->has('add_option_from_api')) {
            $item_id = $request->item_id;
            $api_link = $request->api_link;
            $key = $request->key;

            $result = $this->curlHelper($api_link, 'GET');
            if (isset($result['status'])) {
                if ($result['status'] != 'success') {
                    return back()->with(['error' => 'Error Getting Data']);
                }
            }

            try {
                $item = DB::table("automate_form_$form_name_e")->whereId($item_id)->first();
            } catch (\Throwable $th) {
                return redirect('/error/500');
            }

            $item_option = '';
            foreach ($result['data'] as $data) {
                if (str_contains($data['key'], '/')) {
                    return back()->with(['error' => 'Sorry! Option cannot contains slashes.']);
                }
                $options = explode('///', $item->item_options);
                foreach ($options as $option) {
                    if ($data[$key] == $option) {
                        return back()->with(['error' => 'Option already exist!']);
                    }
                }
                $item_option .= $data[$key];
                if (end($result['data'])[$key] != $data[$key]) {
                    $item_option .= '///';
                }
            }

            if ($item->item_options != '') {
                $item_option = "$item->item_options///$item_option";
            }

            try {
                DB::table("automate_form_$form_name_e")->whereId($item_id)->update([
                    'item_options' => $item_option
                ]);
                return back()->with(['success' => 'Add Option Successful!']);
            } catch (Throwable $th) {
                return back()->with(['error' => 'Failed to Add Option!']);
            }
        }

        $item_name = urldecode($item_name);
        try {
            $options = DB::table("automate_form_$form_name_e")->where('item_name', $item_name)->get();
            $form = DB::table("automate_forms")->where('form_name_e', $form_name_e)->first();
            return view('umum.forms.pages.options')->with(['form' => $form, 'options' => $options]);
        } catch (\Throwable $th) {
            return redirect('/errors/500');
        }
    }

    public function viewDataForm($form_name_e, Request $request)
    {
        if (isset($request->date_s) && isset($request->date_e)) {
            $date_s = $request->date_s;
            $date_e = $request->date_e;
        } else {
            if (session()->has(['date_s', 'date_e'])) {
                $date_s = session()->get('date_s');
                $date_e = session()->get('date_e');
            } else {
                $date_s = date('Y-m-d');
                $date_e = date('Y-m-d');
            }
        }
        session(['date_s' => $date_s, 'date_e' => $date_e]);
        try {
            $list_data = DB::table("automate_form_post_$form_name_e")->where('created_at', '>=', "$date_s 00:00:00")->where('created_at', '<=', "$date_e 23:59:59")->get();
            foreach ($list_data as $key => $value) {
                $formated_date = $this->displayDate($list_data[$key]->created_at);
                $list_data[$key]->created_at = $formated_date;
                foreach ($value as $list => $val) {
                    $dateInfo = date_parse($val);
                    $timeInfo = date_parse($val);

                    /// CHECK IF IS DATE
                    if ($dateInfo['error_count'] === 0 && checkdate($dateInfo['month'], $dateInfo['day'], $dateInfo['year'])) {
                        $list_data[$key]->$list = date('d/m/Y', strtotime($val));
                    }
                    /// CHECK IF IS TIME
                    elseif ($timeInfo['error_count'] === 0 && $timeInfo['hour'] >= 0 && $timeInfo['hour'] < 24 && $timeInfo['minute'] >= 0 && $timeInfo['minute'] < 60 && $timeInfo['second'] >= 0 && $timeInfo['second'] < 60) {
                        $list_data[$key]->$list = date('H:i:s', strtotime($val));
                    }
                }
            }
            $columns = DB::table("automate_form_$form_name_e")->where('item_type', '!=', 'title')->orderBy('item_order')->get();
            $form = Form::where('form_name_e', $form_name_e)->first();
            return view('umum.forms.pages.view_data')->with([
                'page_header' => $form->form_name . ' Submitted Data',
                'columns' => $columns,
                'list_data' => $list_data,
                'form' => $form
            ]);
        } catch (\Throwable $th) {
            return redirect('/error/500');
        }
    }

    public function viewForwardPage($form_name_e, $postid, Request $request)
    {
        if (isset($_POST['edit'])) {
            $posts = $request->all();
            $data = array();

            foreach ($posts as $key => $val) {
                if ($key == '_token' || $key == 'edit') {
                    continue;
                }
                $val_fix = addslashes("$val");
                $data[$key] = $val_fix;
            }

            try {
                DB::table("automate_form_post_$form_name_e")->update($data);
                return back()->with(['success' => 'Edit Data Success!']);
            } catch (Throwable $th) {
                return back()->with('error', 'Failed to Save Data. Try again later.');
            }
        }
        try {
            $list_data = DB::table("automate_form_post_$form_name_e")->whereId($postid)->first();
            if ($list_data) {
                $columns = DB::table("automate_form_$form_name_e")->orderBy('item_order')->get();
                $form = Form::where('form_name_e', $form_name_e)->first();
                $forwards = Forwarding::where('form_id', $form->id)->get();
                return view('umum.forms.pages.forward_page')->with([
                    'page_header' => "Forwarding $form->form_name Post",
                    'columns' => $columns,
                    'list_data' => $list_data,
                    'form' => $form,
                    'forwards' => $forwards
                ]);
            }
            return redirect('/error/404');
        } catch (\Throwable $th) {
            return redirect('/error/500');
        }
    }

    public function editStatusForm(Request $request, $form_name_e)
    {
        $new_stat = $request->set_status;
        try {
            DB::table('automate_forms')
                ->where('form_name_e', $form_name_e)
                ->update(['status' => "$new_stat"]);
            return back()->with(['success' => 'Form Status Updated!']);
        } catch (\Throwable $th) {
            return back()->with(['error' => 'Failed Update Form Status!']);
        }
    }

    public function postForm(Request $request, $form_name_e)
    {
        if (isset($request->honeypot)) {
            return redirect("/forms/view/$form_name_e/success");
        }

        $posts = $request->all();
        $data = array();

        foreach ($posts as $key => $val) {
            if ($key == '_token' || $key == 'preserve_sendWA') {
                continue;
            }

            if (is_array($val)) {
                $val_arr = "";
                foreach ($val as $item_val) {
                    $val_arr .= "$item_val";
                    if (end($val) != $item_val) {
                        $val_arr .= "///";
                    }
                }
                $val_fix = addslashes($val_arr);
            } elseif (is_file($val)) {
                $file = $_FILES[$key];
                $ori_name = $request->file($key)->getClientOriginalName();

                $time = date("(H-i-s)");
                $source = $file['tmp_name'];
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = $key . "_" . $time . "_$ori_name";
                $filename = str_replace(' ', '_', $filename);

                $now = date('Y-m-d');
                $dir = storage_path() . "/app/$form_name_e/uploaded_files/$now/";
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                if (copy($source, $dir . basename($filename))) {
                    $val_fix = "/app/$form_name_e/uploaded_files/$now/" . basename($filename);
                }
            } else {
                $val_fix = addslashes("$val");
            }
            $data[$key] = $val_fix;
        }

        try {
            DB::table("automate_form_post_$form_name_e")->insert($data);

            if (isset($request->preserve_sendWA)) {
                $wa_text = "Your response has been saved to our system.";
                // $this->sendWABlas($mobile, "", "text");
            }
            return redirect("/forms/view/$form_name_e/success");
        } catch (Throwable $th) {
            return back()->with('error', 'Failed to submit. Try again later.')->withInput($request->input());
        }
    }

    public function showSuccessPage($form_name_e)
    {
        $form = Form::where('form_name_e', $form_name_e)->first();
        if ($form) {
            try {
                $form_background = $this->encodeImage($form->background_path);
            } catch (\Throwable $th) {
                $form_background = null;
            }

            $title = $form->form_name;
            return view('umum.forms.template_success', compact('form', 'title', 'form_background'));
        }
        return redirect('/error/404');
    }
}
