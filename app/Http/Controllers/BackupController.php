<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Backup;
use App\Classes\backUp\MySQLDump;
use App\Classes\backUp\MySQLImport;
use App\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class BackupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('backups.index', [
            'backups' => Backup::all(),
            'devices'=>Device::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        DB::beginTransaction();
        try {
//            $stateDownloadAuoBackUp=false;
            for($i=0;$i<count($request->device_id);$i++){
                $d=Device::findOrFail($request->device_id[$i]);
                if ($request->downloadBackUpEvery[$i]>0){
//                    $stateDownloadAuoBackUp=true;
                    $d->state_download_backup=true;
                    $d->download_backup_every=$request->downloadBackUpEvery[$i];
                    $d->day_download=date('Y-m-d', strtotime(date('Y-m-d') . '+' . $request->downloadBackUpEvery[$i] . ' days'));;
                }else{
                    $d->state_download_backup=false;
                    $d->download_backup_every=null;
                    $d->day_download=null;
                }
                $d->save();
            }
            if ($request->pass == '') {
                Backup::truncate();
                Session::flash('success', 'تعديل النسخ الإحتياطي  (لا يوجد مسار لإنشاء نسخة إحتياطية)');
                $activity = new Activity();
                $activity->user_id = Auth::user()->id;
                $activity->device_id = Auth::user()->device_id;
                $activity->data = 'تعديل النسخ الإحتياطي (لا يوجد مسار لإنشاء نسخة إحتياطية) ';
                $activity->type = 3;
                $activity->save();
                return back();
            }

            Backup::truncate();
            for ($x = 0; $x < count($request->pass); $x++) {
                $t = new BackUp();
                $t->pass = $request->pass[$x];
                $t->createBackUpEvery = $request->createBackUpEvery[$x];
                $dayCreate = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $request->createBackUpEvery[$x] . ' days'));
                $t->dayCreate = $dayCreate;
                $t->type = $request->type[$x];
                $t->save();
            }



        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
//            Session::flash('fault',$e->getMessage());
//            return back();
//            throw $e;
        }
        DB::commit();
        Session::flash('success', 'تعديل النسخ الإحتياطي');
        $activity = new Activity();
        $activity->user_id = Auth::user()->id;
        $activity->device_id = Auth::user()->device_id;
        $activity->data = 'تعديل النسخ الإحتياطي ';
        $activity->type = 3;
        $activity->save();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function createBackup($type='all',$id='')
    {
//        $id='' create backUp by $type ,$id=number create backUp by $id ,
//        $type= 0=>create backup for dataBase Only,1=>create backup for file Only,2=>create backUp for dataBase and File
        if ($id==''){
            if ($type=='all'){
                $data = Backup::all();
            }elseif ($type=='createAuto'){
                $data=Backup::where('dayCreate','<=',date('Y-m-d'))->get();
            }else{
                $data=Backup::where('type',$type)->orWhere('type','2')->get();
            }
        }else{
            $data=Backup::where('id',$id)->get();
        }
        $messageError = '';
        $messageSuccess = '';
        $stateDeclearFuctionCopyr=false;
        if (count($data) == 0) {
            Session::flash('fault', 'لا يوجد مسار للنسخ الإحتياطى');
            return redirect(route('home'));
        }
        for ($i = 0; $i < count($data); $i++) {
            //create backup for dataBase
            if ($data[$i]['type'] != '1' && ($type!=1||$type=='all'||$type=='createAuto')) {
                $cn = new \mysqli(env('REDIS_HOST'), env('DB_USERNAME'),
                    env('DB_PASSWORD'));
                //check if database exist
                $dataBase = mysqli_query($cn, "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . env('DB_DATABASE') . "' ");
                if (($dataBase->num_rows) > 0) {
                    /*set path*/
                    if (!is_dir($data[$i]['pass'])) {
                        $messageError .= ' مسار النسخ الإحتياطى ' . $data[$i]['pass'] . ' غير موجود ' . ' <br/> ';
                        continue;
                    }
                    if (!is_dir($data[$i]['pass'] . '/UltimateCode_BackUp')) {
                        mkdir($data[$i]['pass'] . '/UltimateCode_BackUp');
                        mkdir($data[$i]['pass'] . '/UltimateCode_BackUp/' . date('Y'));
                        mkdir($data[$i]['pass'] . '/UltimateCode_BackUp/' . date('Y') . '/' . date('m'));
                        $path = $data[$i]['pass'] . '/UltimateCode_BackUp/' . date('Y') . '/' . date('m');
                    } elseif (!is_dir($data[$i]['pass'] . '/UltimateCode_BackUp/' . date('Y'))) {
                        mkdir($data[$i]['pass'] . '/UltimateCode_BackUp/' . date('Y'));
                        mkdir($data[$i]['pass'] . '/UltimateCode_BackUp/' . date('Y') . '/' . date('m'));
                        $path = $data[$i]['pass'] . '/UltimateCode_BackUp/' . date('Y') . '/' . date('m');
                    } elseif (!is_dir($data[$i]['pass'] . '/UltimateCode_BackUp/' . date('Y') . '/' . date('m'))) {
                        mkdir($data[$i]['pass'] . '/UltimateCode_BackUp/' . date('Y') . '/' . date('m'));
                        $path = $data[$i]['pass'] . '/UltimateCode_BackUp/' . date('Y') . '/' . date('m');
                    } else {
                        $path = $data[$i]['pass'] . '/UltimateCode_BackUp/' . date('Y') . '/' . date('m');
                    }

                    $cn = new \mysqli(env('REDIS_HOST'), env('DB_USERNAME'),
                        env('DB_PASSWORD'), env('DB_DATABASE'));
                    $dump = new MySQLDump($cn);
                    $dump->save($path . "/" . env('DB_DATABASE') . ' ' . date('Y-m-d h-i-sa') . ".sql.gz");


                    /*update date creat backup*/

                    $dayCreate = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $data[$i]['createBackUpEvery'] . ' days'));
                    $data[$i]->dayCreate = $dayCreate;
                    $data[$i]->save();

                    $activty = new Activity();
                    $activty->user_id = Auth::user()->id;
                    $activty->device_id = Auth::user()->device_id;
                    $activty->data = 'انشاء نسخة احتياطية من قاعدة البيانات في المسار ' . $path;
                    $activty->type = 3;
                    $activty->save();

                    $messageSuccess .= 'تم إنشاء نسخة إحتياطية من قاعدة البيانات في المسار ' . $path . ' <br/> ';

                } else {
                    Session::flash('fault', 'قاعده البيانات غير موجودة');
                    return back();
                }
            }

            //create backup for imgFile
            if ($data[$i]['type'] != '0' && ($type!=0||$type=='all'||$type=='createAuto')) {
//                cope folder img from public
                if (!$stateDeclearFuctionCopyr){
                    $stateDeclearFuctionCopyr=true;
                    function copyr($src, $dst)
                    {
                        if (is_dir($src)) {
                            if (!file_exists($dst)) {
                                mkdir($dst, 0777, true);
                            }
                            foreach (scandir($src) as $file) {
                                if ($file == '.' || $file == '..') {
                                    continue;
                                }
                                copyr("$src/$file", "$dst/$file");
                            }
                        } elseif (is_file($src)) {
                            copy($src, $dst);
                        } else {
                            throw new \Exception("Cannot copy $src (unknown file type)");
                        }
                    }
                }

                /*set path*/
                if (!is_dir($data[$i]['pass'])) {
                    $messageError .= ' مسار النسخ الإحتياطى للملفات ' . $data[$i]['pass'] . ' غير موجود ' . ' <br/> ';
                    continue;
                }
                if (!is_dir($data[$i]['pass'] . '/backUp')) {
                    mkdir($data[$i]['pass'] . '/backUp');
                    mkdir($data[$i]['pass'] . '/backUp/' . date('Y'));
                    mkdir($data[$i]['pass'] . '/backUp/' . date('Y') . '/' . date('m'));
                    $path = $data[$i]['pass'] . '/backUp/' . date('Y') . '/' . date('m');
                } elseif (!is_dir($data[$i]['pass'] . '/backUp/' . date('Y'))) {
                    mkdir($data[$i]['pass'] . '/backUp/' . date('Y'));
                    mkdir($data[$i]['pass'] . '/backUp/' . date('Y') . '/' . date('m'));
                    $path = $data[$i]['pass'] . '/backUp/' . date('Y') . '/' . date('m');
                } elseif (!is_dir($data[$i]['pass'] . '/backUp/' . date('Y') . '/' . date('m'))) {
                    mkdir($data[$i]['pass'] . '/backUp/' . date('Y') . '/' . date('m'));
                    $path = $data[$i]['pass'] . '/backUp/' . date('Y') . '/' . date('m');
                } else {
                    $path = $data[$i]['pass'] . '/backUp/' . date('Y') . '/' . date('m');
                }
                copyr('C:/xampp/htdocs/public/imgStore', $path . "/" . env('DB_DATABASE') . ' file ' . date('Y-m-d h-i-sa'));
//                copyr(asset('imgStore'),$path."/".env('DB_DATABASE').' file '.date('Y-m-d h-i-sa'));
                /*update date creat backup*/

                $dayCreate = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $data[$i]['createBackUpEvery'] . ' days'));
                $data[$i]->dayCreate = $dayCreate;
                $data[$i]->save();

                $activty = new Activity();
                $activty->user_id = Auth::user()->id;
                $activty->device_id = Auth::user()->device_id;
                $activty->data = 'انشاء نسخة احتياطية من الملفات في المسار ' . $path;
                $activty->type = 3;
                $activty->save();

                $messageSuccess .= 'تم إنشاء نسخة إحتياطية من الملفات في المسار ' . $path . ' <br/> ';
            }
        }
        if ($messageError == '') {
            Session::flash('success', 'تم إنشاء نسخة إحتياطية بنجاح');
        } else {
            Session::flash('fault', $messageError . '<br/>' . $messageSuccess);
        }
        return redirect(route('home'));
    }

    public function downloadBackup()
    {
        /*download backup*/
        try {
            $d=Device::findOrFail(Auth::user()->device_id);
            if ($d->state_download_backup){
                $dayCreate = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $d['download_backup_every'] . ' days'));
                $d->day_download=$dayCreate;
                $d->save();
            }

            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'تنزيل نسخة إحتياطية ' ;
            $activty->type = 3;
            $activty->save();

            File::deleteDirectory(public_path() . '\\' . 'myBackUp');
            mkdir(public_path() . '\\' . 'myBackUp');
            $cn = new \mysqli(env('REDIS_HOST'), env('DB_USERNAME'),
                env('DB_PASSWORD'), env('DB_DATABASE'));
            $dump = new MySQLDump($cn);
            $fileName = env('DB_DATABASE') . ' ' . date('Y-m-d h-i-sa') . ".sql.gz";
            $dump->save(public_path() . "/myBackUp/" . $fileName);

            //download file
            return Response::download(public_path() . "\\myBackUp\\$fileName", $fileName, [
                'Content-Length: ' . filesize(public_path() . "\\myBackUp\\" . $fileName)
            ]);
        } catch (\Exception $e) {
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حصل خطاء فى تحميل النسخة الإحطياطية تفاصيل الخطاء ' . '<br/>' . $e->getMessage();
            $activty->type = 2;
            $activty->save();
            Session::flash('fault', 'حصل خطاء فى العملية برجاء مراجعة النشاطات لتفاصيل الخطاء ');
            return back();
        }

    }

    public function restore(Request $r)
    {
        if ($r->has('password')) {
            if ($r->password == env('DB_PASSWORD')) {
                if ($_FILES['restoreDb']['type'] == 'application/octet-stream') {
                    $cn = new \mysqli(env('REDIS_HOST'), env('DB_USERNAME'),
                        env('DB_PASSWORD'));
                    mysqli_query($cn, 'DROP DATABASE IF EXISTS ' . env('DB_DATABASE'));
                    mysqli_query($cn, 'CREATE DATABASE IF NOT EXISTS ' . env('DB_DATABASE') . '
        DEFAULT CHARACTER SET UTF8 DEFAULT COLLATE UTF8_GENERAL_CI');
                    $cn = new \mysqli(env('REDIS_HOST'), env('DB_USERNAME'),
                        env('DB_PASSWORD'), env('DB_DATABASE'));
                    $import = new MySQLImport($cn);
                    $import->load($_FILES['restoreDb']['tmp_name']);

                    return redirect(route('home'));
                }
            } else
                return redirect(route('home'));

        } else {
            if ($_FILES['restoreDb']['type'] == 'application/octet-stream' && Auth::user()->type == 1) {
                $cn = new \mysqli(env('REDIS_HOST'), env('DB_USERNAME'),
                    env('DB_PASSWORD'));
                mysqli_query($cn, 'DROP DATABASE IF EXISTS ' . env('DB_DATABASE'));
                mysqli_query($cn, 'CREATE DATABASE IF NOT EXISTS ' . env('DB_DATABASE') . '
        DEFAULT CHARACTER SET UTF8 DEFAULT COLLATE UTF8_GENERAL_CI');
                $cn = new \mysqli(env('REDIS_HOST'), env('DB_USERNAME'),
                    env('DB_PASSWORD'), env('DB_DATABASE'));
                $import = new MySQLImport($cn);
                $import->load($_FILES['restoreDb']['tmp_name']);


                Session::flash('success', 'استعادة نسخة احتياطية');
                $activty = new Activity();
                $activty->user_id = Auth::user()->id;
                $activty->device_id = Auth::user()->device_id;
                $activty->data = '***  تم استعادة نسخة احتياطية';
                $activty->type = 3;
                $activty->save();

                return redirect(route('home'));
            }
        }
    }

}
