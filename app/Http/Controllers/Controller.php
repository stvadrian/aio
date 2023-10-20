<?php

namespace App\Http\Controllers;

use App\Mail\PDFMail;
use DateTime;
use DateTimeZone;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use ZipArchive;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->baselink = $_SERVER['SERVER_NAME'];
        $this->rootname = 'aio';
        $this->profile_path =  storage_path() . '/app';

        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                $branch = auth()->user()->kd_cabang;
                switch ($branch) {
                    case 1:
                        // $this->connection = DB::connection('');
                        // Used for multiple database each branches
                        $this->cabang = (object) [
                            'logo' => $this->encodeImage(storage_path() . '/app/public/logo.png'),
                        ];
                        break;

                    default:
                        abort(404, 'Invalid Request');
                        break;
                }
            }
            return $next($request);
        });
    }

    public function generateQR($link, $save_path, $save_name)
    {
        $url = 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' . urlencode($link) . '&chs=190x190&chld=L|0';
        $response = Http::withHeaders([
            'Accept' => 'image/png',
            'Content-Type' => 'image/png',
        ])
            ->get($url);

        if ($response->status() != 200) {
            return false;
        }

        $png = $response->body();
        if (!is_dir($save_path)) {
            mkdir($save_path, 0777, true);
        }
        if (file_put_contents($save_path . $save_name . '.png', $png)) {
            return $save_path . $save_name . '.png';
        } else {
            return false;
        }
    }

    public function determineDataType($inputType)
    {
        if ($inputType == 'date') {
            $dataType = 'date';
        } elseif ($inputType == 'time') {
            $dataType = 'time';
        } elseif ($inputType == 'datetime') {
            $dataType = 'time';
        } elseif ($inputType == 'textarea' || $inputType == 'select2multiple' || $inputType == 'checkbox') {
            $dataType = 'text';
        } elseif ($inputType == 'number') {
            $dataType = 'int';
        } else {
            $dataType = 'VARCHAR (255)';
        }
        return $dataType;
    }

    public function getDayName($date, $dateSeparator, $dateLanguage)
    {
        $parts = explode($dateSeparator, $date);
        $d = date("l", mktime(0, 0, 0, $parts[1], $parts[2], $parts[0]));

        if ($dateLanguage == 'en') {
            return $d;
        } else {
            if ($d == 'Monday') {
                return 'Senin';
            } elseif ($d == 'Tuesday') {
                return 'Selasa';
            } elseif ($d == 'Wednesday') {
                return 'Rabu';
            } elseif ($d == 'Thursday') {
                return 'Kamis';
            } elseif ($d == 'Friday') {
                return 'Jumat';
            } elseif ($d == 'Saturday') {
                return 'Sabtu';
            } elseif ($d == 'Sunday') {
                return 'Minggu';
            } else {
                return null;
            }
        }
    }

    public function displayDate($date, $montype = 'full')
    {
        if (!$date) {
            return null;
        }
        $day = substr($date, 8, 2);
        $mon = substr($date, 5, 2);
        $year = substr($date, 0, 4);
        if ($mon == '1') {
            if ($montype == 'half') {
                $month = 'Jan';
            } else {
                $month = "Januari";
            }
        } elseif ($mon == '2') {
            if ($montype == 'half') {
                $month = 'Feb';
            } else {
                $month = "Februari";
            }
        } elseif ($mon == '3') {
            if ($montype == 'half') {
                $month = 'Mar';
            } else {
                $month = "Maret";
            }
        } elseif ($mon == '4') {
            if ($montype == 'half') {
                $month = 'Apr';
            } else {
                $month = "April";
            }
        } elseif ($mon == '5') {
            if ($montype == 'half') {
                $month = 'Mei';
            } else {
                $month = "Mei";
            }
        } elseif ($mon == '6') {
            if ($montype == 'half') {
                $month = 'Jun';
            } else {
                $month = "Juni";
            }
        } elseif ($mon == '7') {
            if ($montype == 'half') {
                $month = 'Jul';
            } else {
                $month = "Juli";
            }
        } elseif ($mon == '8') {
            if ($montype == 'half') {
                $month = 'Agu';
            } else {
                $month = "Agustus";
            }
        } elseif ($mon == '9') {
            if ($montype == 'half') {
                $month = 'Sep';
            } else {
                $month = "September";
            }
        } elseif ($mon == '10') {
            if ($montype == 'half') {
                $month = 'Okt';
            } else {
                $month = "Oktober";
            }
            $month = "Oktober";
        } elseif ($mon == '11') {
            if ($montype == 'half') {
                $month = 'Nov';
            } else {
                $month = "November";
            }
        } elseif ($mon == '12') {
            if ($montype == 'half') {
                $month = 'Des';
            } else {
                $month = "Desember";
            }
        }
        $result = "$day $month $year";
        return $result;
    }

    public function encodeImage($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    public function generatePDF($data, $templatePath, $pdfName)
    {
        $pdf = PDF::loadView($templatePath, compact('data'));
        return $pdf->stream($pdfName . '.pdf');
    }

    public function sanitizeFormName($form_name)
    {
        return trim(strtolower(str_replace(' ', '_', addslashes($form_name))), " \t\n\r");
    }

    public function sendEmail(Request $request)
    {
        $email = $request->email;
        $nama = $request->pasien;
        $filename = $request->file;

        $maildata = [
            'nama' => $nama,
        ];

        $send = Mail::to($email)->send(new PDFMail($maildata, $filename));

        if ($send) {
            return 1;
        } else {
            return 0;
        }
    }

    public function duration($time2, $time1, $resultType = null)
    {
        $now = new DateTime($time2);
        $ago = new DateTime($time1);

        $diff = $ago->diff($now);

        $hours = $diff->h;
        $minutes = $diff->i;
        $days = $diff->days;

        if (strlen($hours) == 1) {
            $hours = "0$hours";
        }
        if (strlen($minutes) == 1) {
            $minutes = "0$minutes";
        }

        if ($resultType == 'hours') {
            $hours = $hours + ($diff->days * 24);
            return "$hours:$minutes";
        }
        return $days . 'd ' . $hours . 'h ' . $minutes . 'm';
    }

    public function normalizeDate($date, $secs = false)
    {
        $date = str_replace(' ', '', $date);
        $dates = explode('/', $date);
        $day = $dates[0];
        $mon = $dates[1];
        $year = $dates[2];
        $date = "$year-$mon-$day";
        if ($secs) {
            return date('Y-m-d H:i:s', strtotime($date));
        } else {
            return date('Y-m-d', strtotime($date));
        }
    }

    public function sendWABlas($mobile, $message, $type, $payload = null)
    {
        $token_WA = "SIUhGTGr9OWR0SC5FYCXRExH0wQGn1NnSn3wxWQPLJkdDqcnoy3Jim0D6FDWFJRo";
        $success = true;
        if ($type == "text") {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://pati.wablas.com/api/send-message?phone=$mobile&message=$message&token=$token_WA",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER => false
            ));
            curl_exec($curl);
            if (curl_errno($curl)) {
                $success = false;
            }
            curl_close($curl);
        } elseif ($type == "button" && $payload != null) {
            $curl = curl_init();
            curl_setopt(
                $curl,
                CURLOPT_HTTPHEADER,
                array(
                    "Authorization: $token_WA",
                    "Content-Type: application/json"
                )
            );
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($curl, CURLOPT_URL, "https://pati.wablas.com/api/v2/send-template");
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

            curl_exec($curl);
            if (curl_errno($curl)) {
                $success = false;
            }
            curl_close($curl);
        }
        return $success;
    }

    public function generateRandomToken($content = null)
    {
        if ($content) {
            return md5($content . date('Y-m-d H:i:s'));
        }
        return md5(date('Y-m-d H:i:s'));
    }

    public function curlHelper($url, $method, $postdata = array())
    {
        $curl = curl_init();
        if (strtoupper($method) == 'POST') {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POSTFIELDS => http_build_query($postdata),
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTREDIR => true,
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Content-Type: */*',
                ),
            ));
        } elseif (strtoupper($method) == 'GET') {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Content-Type: */*',
                    'Content-Length: 0'
                ),
            ));
        }

        $response = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($response, true);

        return $json;
    }

    public function laravelHttp($url, $method, $postdata = array())
    {
        if (strtoupper($method) == 'POST') {
            $response = Http::asForm()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => '*/*',
                ])->post($url, $postdata);
        } else {
            $response = Http::asForm()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => '*/*',
                ])->get($url);
        }
        $json = json_decode($response->body(), true);

        return $json;
    }

    public function buildRequest($datas)
    {
        $req_obj = new \Illuminate\Http\Request();
        $req_obj->setMethod('POST');
        foreach ($datas as $data => $val) {
            $req_obj->request->add([$data => $val]);
        }

        return $req_obj;
    }

    public function addDay($originalDate, $amount)
    {
        $newDate = date('Y-m-d', strtotime($originalDate . ' + ' . $amount . ' days'));
        return $newDate;
    }

    public function generateExcel($excelData)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = array_keys($excelData[0]);
        $columnIndex = 1;

        foreach ($headers as $header) {
            $cell = $sheet->getCellByColumnAndRow($columnIndex, 1);
            $cell->setValue($header);
            $columnIndex++;
        }

        $row = 2;
        foreach ($excelData as $rowData) {
            $columnIndex = 1;
            foreach ($rowData as $value) {
                $cell = $sheet->getCellByColumnAndRow($columnIndex, $row);
                $cell->setValue($value);
                $columnIndex++;
            }
            $row++;
        }

        $columnCount = $sheet->getHighestColumn();
        $columnCountIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($columnCount);
        for ($columnIndex = 1; $columnIndex <= $columnCountIndex; $columnIndex++) {
            $sheet->getColumnDimensionByColumn($columnIndex)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $excelFilePath = sys_get_temp_dir() . '/export.xlsx';
        $writer->save($excelFilePath);

        return $writer;
    }

    public function zipExcel($exports, $filenames)
    {
        $zipPath = sys_get_temp_dir() . '/exports.zip';
        $zip = new ZipArchive();
        $counter = 0;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($exports as $index => $export) {
                $fileName = $filenames[$counter++] . '.xlsx';
                $excelFilePath = sys_get_temp_dir() . '/' . $fileName;
                $export->save($excelFilePath);
                $zip->addFile($excelFilePath, $fileName);
            }
            $zip->close();

            $response = response()->download($zipPath, $filenames[--$counter] . '.zip', [
                'Content-Type' => 'application/zip',
            ]);
            $response->deleteFileAfterSend(true);

            return $response;
        } else {
            return response('Failed to create ZIP archive.', 500);
        }
    }

    public function downloadExcel($export, $filename)
    {
        $fileName = $filename . '.xlsx';
        $excelFilePath = sys_get_temp_dir() . '/' . $fileName;
        $export->save($excelFilePath);

        $response = response()->download($excelFilePath, $fileName)->deleteFileAfterSend(true);
        return $response;
    }

    public function customResponse($statusCode, $data, $customHeader = null)
    {
        $response = response()->json($data, $statusCode);
        if (isset($customHeader)) {
            $response->header('X-Custom-Header', $customHeader);
        }
        return $response;
    }
}
