<?php

namespace App\Http\Controllers;

use App\Models\QR;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ConsoleTVs\Charts\Facades\Charts;


class UserController extends Controller
{
    public function viewError($error_code)
    {
        if ($error_code == '401') {
            $error_msg = 'Unauthenticated Access';
        }
        if ($error_code == '403') {
            $error_msg = 'Forbidden Access';
        }
        if ($error_code == '404') {
            $error_msg = 'Page Not Found';
        }
        if ($error_code == '405') {
            $error_msg = 'Method Not Allowed';
        }
        if ($error_code == '500') {
            $error_msg = 'Internal Server Error';
        }
        if ($error_code == '503') {
            $error_msg = 'Service Unavailable';
        }
        return view('layouts.error')->with(['error_code' => $error_code, 'error_msg' => $error_msg]);
    }

    public function stopImpersonating()
    {
        if (session()->has('impersonator')) {
            $impersonator_id = session()->get('impersonator');
            auth()->logout();
            auth()->loginUsingId($impersonator_id);

            session()->forget(['impersonator']);
        }
        return redirect('/user-manager')->with(['success' => 'Impersonation stopped']);
    }

    public function viewDashboard()
    {
        $barDatasets = [
            [
                'label' => 'Dataset 1',
                'data' => [12, 19, 3, 17, 28],
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
            ],
            [
                'label' => 'Dataset 2',
                'data' => [8, 15, 6, 12, 22],
                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                'borderColor' => 'rgba(255, 99, 132, 1)',
                'borderWidth' => 1,
            ],
        ];
        $barChartData = [
            'labels' => ['Category 1', 'Category 2', 'Category 3', 'Category 4', 'Category 5'],
            'datasets' => $barDatasets,
            'chartType' => 'bar',
            'chartOptions' => [
                'title' => [
                    'display' => true,
                    'text' => 'Dynamic Bar Chart',
                ],
            ],
        ];

        $pieDatasets = [
            [
                'label' => 'Dataset 1',
                'data' => [12, 19, 3, 17, 28],
                'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1,
            ],
        ];
        $pieChartData = [
            'labels' => ['Category 1', 'Category 2', 'Category 3', 'Category 4', 'Category 5'],
            'datasets' => $pieDatasets,
            'chartType' => 'pie',
            'chartOptions' => [
                'title' => [
                    'display' => true,
                    'text' => 'Dynamic Pie Chart',
                ],
            ],
        ];

        return view('umum.pages.dashboard', compact('barChartData', 'pieChartData'));
    }

    public function viewProfile(Request $request)
    {
        if ($request->has('update_profile')) {
            $profileDir = storage_path() . '/profile_img';
            if (!is_dir($profileDir)) {
                mkdir($profileDir, 0777, true);
            }
            $file = $request->profile_img;
            $extension = $file->getClientOriginalExtension();
            $filename = auth()->user()->username . ".$extension";
            $path = '/' . $request->profile_img->storeAs('profile_img', $filename);
            try {
                User::whereId(auth()->user()->id)->update([
                    'profile_img' => $path,
                ]);
                return back()->with(['success' => 'Success Update Avatar!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update Avatar!']);
            }
        }

        if ($request->has('update_account')) {
            if (isset($request->honeypot)) {
                return back()->with('succes', 'Success!');
            }

            if (!Hash::check($request->cur_pass, auth()->user()->password)) {
                return back()->with(['error' => 'Incorrect current password']);
            }
            $nm_lengkap = $request->nm_lengkap;
            $dob = $request->dob;
            $dob = $this->normalizeDate($dob);
            $mobile = $request->mobile;
            if ($request->password != null && $request->password != '') {
                $pass = $request->password;
                $pass = bcrypt($pass);
            } else {
                $pass = auth()->user()->password;
            }

            try {
                User::whereId(auth()->user()->id)->update([
                    'nm_user' => $nm_lengkap,
                    'dob_user' => $dob,
                    'mobile_user' => $mobile,
                    'password' => $pass
                ]);
                return back()->with(['success' => 'Success Update Account!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Update Account!']);
            }
        }
        $formatted_dob = date('d / m / Y', strtotime(auth()->user()->dob_user));
        $pageHeader = 'Account Details';
        return view('umum.pages.profile', compact('formatted_dob', 'pageHeader'));
    }


    public function toggleDarkmode()
    {
        if (session('darkmode')) {
            session()->forget(['darkmode']);
        } else {
            session()->put(['darkmode' => true]);
        }

        return redirect()->back();
    }

    public function exportToExcel(Request $request)
    {
        $excel_name = '';
        if ($request->has('excel_name')) {
            $excel_name = $request->excel_name;
        }
        if ($excel_name == '') {
            $excel_name = 'Exported';
        }
        $data = json_decode($request->data);
        $html = '<table border=1>
                <thead>
                    <tr>';

        foreach ($data[0] as $th => $value) {
            $html .= "<th>$th</th>";
        }

        $html .= '</tr>
                </thead>
                <tbody>';
        foreach ($data as $rows) {
            $row = (array) $rows;
            $html .= '<tr>';
            foreach ($row as $td) {
                $html .= "<td>$td</td>";
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        $filename = "$excel_name.xls";

        return new StreamedResponse(function () use ($html) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, $html);
            fclose($stream);
        }, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename,
        ]);
    }


    public function viewQRGenerator(Request $request)
    {
        if (isset($request->link_qr)) {
            $link = $request->link_qr;
            $save_name = urlencode(strtolower($request->qr_name));
            $save_path = storage_path() . '/app/generated_qr/';
            $qr = $this->generateQR($link, $save_path, $save_name);

            try {
                QR::create([
                    'qr_name' => $request->qr_name,
                    'qr_content' => $link,
                    'qr_path' => $qr,
                    'created_by' => auth()->user()->nm_user,
                ]);
                return back()->with(['success' => 'QR Successfully Generated']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Generating QR']);
            }
        }

        if (isset($request->qrid)) {
            $id = $request->qrid;
            try {
                QR::whereId($id)->delete();
                return back()->with(['success' => 'QR Successfully Deleted!']);
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed Deleting QR']);
            }
        }
        $qrs =  QR::with('user')->get();
        $pageHeader = 'QR Code Generator';
        return view('umum.pages.qr_generator', compact('pageHeader', 'qrs'));
    }

    public function viewGeneratedQR($qr_name)
    {
        $qr_name = urldecode($qr_name);
        $qr = QR::where('qr_name', $qr_name)->firstOrFail();
        try {
            $qr->qr_img = $this->encodeImage($qr->qr_path);
        } catch (\Throwable $th) {
            $qr->qr_img = null;
        }
        return view('layouts.qr_view')->with(['qr' => $qr]);
    }


    public function viewReportGenerator(Request $request)
    {
        if ($request->has('add_report')) {
            $report_name = $request->report_name;
            $report_qry = $request->report_qry;
            $report_qry = str_replace(['"'], '', $report_qry);

            try {
                $insert = DB::table('report_list')->insert([
                    'report_name' => $report_name,
                    'report_query' => $report_qry,
                    'kd_rumah_sakit' => auth()->user()->kd_rumah_sakit
                ]);
                if (!$insert) {
                    return back()->with(['error' => 'Failed to Add Report']);
                }
            } catch (\Throwable $th) {
                return back()->with(['error' => 'Failed to Add Report']);
            }
            return back()->with(['success' => 'Success Add Report']);
        }

        if ($request->has('generate') || $request->has('view')) {
            $date_s = $request->date_s;
            $date_e = $request->date_e;
            session()->put(['date_s' => $date_s, 'date_e' => $date_e]);
            $reportid = $request->selected_report;
            $token = 'qlNp8AaT+5s4UTpppiKRNw==';

            $report = DB::table('report_list')->whereId($reportid)->first();
            $report_name = $report->report_name;
            $report_query = $report->report_query;

            $report_query = str_replace(['$tgl1'], $date_s, $report_query);
            $report_query = str_replace(['$tgl2'], $date_e, $report_query);

            $url = 'http://10.10.1.61:8080/api/tc_raw.php';
            $postdata = array('_token' => $token, 'qrys' => $report_query);

            $result = $this->laravelHttp($url, 'POST', $postdata);
            if ($result['status'] != 'success') {
                return back()->with(['error' => 'Something went wrong. Try again later.']);
            }
            $excel_data = $result['data'];

            if ($request->has('generate')) {
                $exports[] = $this->generateExcel($excel_data);
                $exportsName[] = "$report_name ($date_s - $date_e)";

                if (!empty($exports)) {
                    if (count($exports) > 1) {
                        return $this->zipExcel($exports, $exportsName);
                    } else {
                        return $this->downloadExcel($exports[0], $exportsName[0]);
                    }
                }
                return back()->with(['warning' => 'No Data Available on that specific date.']);
            }

            if ($request->has('view')) {
                return back()->with(['data' => $result['data']]);
            }
        }
        $reports = DB::table('report_list')->where('kd_rumah_sakit', auth()->user()->kd_rumah_sakit)->get();
        return view('umum.pages.report_generator')->with(['reports' => $reports]);
    }

    public function viewCVForm()
    {
        $pageHeader = 'CV Builder';
        return view('umum.pages.cv_builder_form', compact('pageHeader'));
    }
}
