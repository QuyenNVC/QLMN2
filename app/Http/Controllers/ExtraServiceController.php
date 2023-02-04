<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExtraService;
use Illuminate\Support\Facades\Auth;

class ExtraServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.extra_service');
    }

    public function getList(Request $request) {
        try {
            if($request->get('id_coso')) {
                $extraservices = ExtraService::whereNull ('isDeleted')->where('id_coso', $request->get('id_coso'))->get();
            } else {
                $extraservices = ExtraService::whereNull ('isDeleted')->get();
            }
            $data = [
                'extraservices'  => $extraservices,
                'status' => true,
                'id_coso'    =>  Auth::user()->id_coso
            ];
        }
        catch (Exception $e) {
            $data = [
                status => false
            ];
        };
        return json_encode($data);
    }

    public function create(Request $request) {
        try {
            $extraservice = new ExtraService();
            $extraservice->name = $request->name;
            $extraservice->note = $request->note;
            $extraservice->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
            if(Auth::user()->cannot('save', $extraservice)) {
                abort(403);
            }
            // $extraservice->isDeleted = "";
            $extraservice->save();
            $data = [
                'status' => true
            ];
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => $e
            ];    
        }
        return json_encode($data);
    }

    public function update(Request $request) {
        try {
            $extraservice = ExtraService::where('id', $request->id)->first();
            if ( $extraservice ) {
                $extraservice->name = $request->name;
                $extraservice->note = $request->note;
                $extraservice->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
                if(Auth::user()->cannot('save', $extraservice)) {
                    abort(403);
                }
                $extraservice->save();
                $data = [
                    'status' => true,
                    'annualfee' => $extraservice
                ];
                return json_encode($data);
            } else {
                $data = [
                    'status' => false,
                    'message' => 'Lỗi dữ liệu'
                ];
                return json_encode($data);
            }
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => $e
            ];
            return json_encode($data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Request $request, $id) {
        try {
            $extraservice = ExtraService::where("id", $id)->get()->first();
            $extraservice->isDeleted = '1';
            if(Auth::user()->cannot('save', $extraservice)) {
                abort(403);
            }
            $extraservice->save();
            $data = [
                'status' => true
            ];
        }
        catch(Exception $e) {
            $data = [
                'status' => false,
                'message' => "Lỗi Server"
            ]; 
        }
        return json_encode($data);
    }
}
