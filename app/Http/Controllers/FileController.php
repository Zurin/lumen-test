<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\File;
class FileController extends Controller
{
    /**
     * Register new user
     *
     * @param $request Request
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $id_user = $request->input('id_user');
            $nama_file = $request->file->getClientOriginalName();
            $ekstensi = $request->file->getClientOriginalExtension();
            if ($ekstensi == 'pdf') {
                $file = md5(microtime()).'-'.$nama_file;
                $upload = $request->file->move('uploads', $file);
                if ($upload) {
                    $save = File::create([
                        'nama_file' => $file,
                        'id_user'   => $id_user
                    ]);
                    if ($save) {
                        if (!$fp = @fopen(url('uploads')."/".$file,"r")) {
                            $pages = 'failed opening file '.url('uploads')."/".$file;
                        } else {
                                $max=0;
                                while(!feof($fp)) {
                                        $line = fgets($fp,255);
                                        if (preg_match('/\/Count [0-9]+/', $line, $matches)){
                                                preg_match('/[0-9]+/',$matches[0], $matches2);
                                                if ($max<$matches2[0]) $max=$matches2[0];
                                        }
                                }
                                $pages = $max;
                        }
                        fclose($fp);
                        $res['status'] = 200;
                        $res['message'] = 'Sukses upload file!';
                        $res['data'] = array(
                            'id_user' => $id_user,
                            'file_path' => url('uploads')."/".$file,
                            'document_pages' => $pages
                        );
                    } else {
                        $res['status'] = 406;
                        $res['message'] = 'Gagal simpan database!';
                    }
                } else {
                    $res['status'] = 406;
                    $res['message'] = 'Gagal upload file!';
                }
            } else {
                $res['status'] = 406;
                $res['message'] = 'Tipe file bukan pdf!';
            }
            
        } else {
            $res['status'] = 406;
            $res['message'] = 'File belum di set!';
        }
        
        return response($res);
    }

    public function get_file($name)
    {
        $file = storage_path('uploads') . '/' . $name;
        if (file_exists($file)) {
            $file = file_get_contents($file);
            return response($file, 200)->header('Content-Type', 'application/pdf');
        }
        $res['success'] = false;
        $res['message'] = "File ".$file." not found";
        
        return $res;
    }
    
}