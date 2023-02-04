<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;
use Illuminate\Support\Facades\Storage;
use App\Service\AddLog;
use Illuminate\Support\Facades\Auth;

class InfoController extends Controller
{
    private $table = "info";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.info');
    }

    public function getInfo($id) {
        try {
            if(Auth::user()->id_coso) {
                if(Auth::user()->id_coso != $id) {
                    $data = [
                        'status'    =>  false,
                        'message'   =>  $id ? 'Bạn không có quyền truy cập vào thông tin của cơ sở này' : 'Bạn không có quyền tạo cơ sở'
                    ];
                    return response()->json($data, 200);
                }
            }
            $item = Info::find($id);
            
            if($item) {
                $item['urlLogo'] = asset("storage/logo/$item->logo");
                $data = [
                    'status'    =>  true,
                    'item'      =>  $item
                ];
            } else {
                $data = [
                    'status'    =>  false,
                    'message'   =>  'Không có thông tin'
                ];
            }
        }
        catch (Exception $e) {
            $data = [
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function getList() {
        try {
            $items = Info::whereNull('deleted_at')->get()->toArray();
            for ($i=0; $i < count($items); $i++) {
                $logo = $items[$i]["logo"];
                $items[$i]["urlLogo"] = asset("storage/logo/$logo");
            }
            $data = [
                "status"    =>  true,
                "items"     =>  $items,
                "id_coso"   =>  Auth::user()->id_coso
            ];
        } catch(Exception $e) {
            $data = [
                "status"    =>  false,
                "message"   => $e->getMessage()
            ];
        }
        return response()->json($data, 200,);
     }

    public function getForm(&$id = null)
    {
        return view('admin.info_form')->with('id', $id);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if(Auth::user()->id_coso) {
                if(Auth::user()->id_coso != $request->info['id']) {
                    $data = [
                        'status'    =>  false,
                        'message'   =>  $request->info['id'] ? 'Bạn không có quyền cập nhật vào thông tin của cơ sở này' : 'Bạn không có quyền tạo cơ sở'
                    ];
                    return response()->json($data, 200);
                }
            }
            if($request->info['id']) {
                $info = Info::find($request->info['id']);
            } else {
                $info = new Info();
            }

            foreach($request->info as $key => $item) {
                $info[$key] = $item;
            }
            $info->logo = $request->image["name"] ? $this->saveFile($request->image) : $info->logo;
            if(Auth::user()->cannot('save', $info)) {
                abort(403);
            }
            $info->save();
            
            $data = [
                'status'    =>  true,
                'id_coso'      =>  $info->id
            ];
        }
        catch (Exception $e) {
            $data = [
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
        return response()->json($data);
    }

    protected function saveFile($file) {
        if(!is_null($file['content'])) {
            $file_name =  $file['name'];
            $this->handleFile($file_name, '/logo/', $file['content']);
            return $file_name;
        } else {
            return '';
        }
    }

    protected function handleFile($filename, $root, 
    $content) {
        $content = explode(';', $content)[1];
        $content = explode(',', $content)[1];
        $file_path = sprintf('%s/%s', $root, $filename);
        $storage = Storage::disk('public');

        $checkDirectory = $storage->exists($root);

        
        if (!$checkDirectory) {
        $storage->makeDirectory($root);
        }

        $storage->put($file_path, base64_decode($content));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            foreach($request->array as $id) {
                $item = Info::find($id);
                AddLog::addLog("delete", $this->table, $id, $item->school_name, Auth::id());
                $item->delete();
                // $userCheck->isDeleted = 1;
                // $userCheck->save();
            }
            $data = [
                'status' => true
            ];
        }
        catch (Exception $e) {
            $data = [
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
        return response()->json($data);
    }
}
