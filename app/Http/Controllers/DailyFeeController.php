<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyFee;
use App\Models\HinhThucDiemDanh;
use Illuminate\Support\Facades\Auth;

class DailyFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.daily_fee');
    }

    public function getList(Request $request) {
        try {
            $dailyfees = DailyFee::whereNull('isDeleted');
            if($request->id_coso) {
                $dailyfees = $dailyfees->where('id_coso', $request->id_coso);
            }
            $dailyfees = $dailyfees->get();
            $data = [
                'dailyfees'  => $dailyfees,
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
            $dailyfee = new DailyFee();
            $dailyfee->name = $request->name;
            $dailyfee->note = $request->note;
            $dailyfee->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
            if(Auth::user()->cannot('save', $dailyfee)) {
                abort(403);
            }
            $dailyfee->save();
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
            $dailyfee = DailyFee::where('id', $request->id)->first();
            if ( $dailyfee ) {
                $dailyfee->name = $request->name;
                $dailyfee->note = $request->note;
                $dailyfee->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
                if(Auth::user()->cannot('save', $dailyfee)) {
                    abort(403);
                }
                $dailyfee->save();
                $data = [
                    'status' => true,
                    'dailyfee' => $dailyfee
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
            $dailyfee = DailyFee::where("id", $id)->get()->first();
            $dailyfee->isDeleted = '1';
            if(Auth::user()->cannot('save', $dailyfee)) {
                abort(403);
            }
            $dailyfee->save();

            $hinhThucDiemDanhs = HinhThucDiemDanh::whereNull('isDeleted')->get();
            foreach($hinhThucDiemDanhs as $item) {
                $idOfHinhThucs = $item->daily;
                if(strlen($idOfHinhThucs) > 1) {
                    $idOfHinhThucs = explode(',', $idOfHinhThucs);
                    $index = array_search($id, $idOfHinhThucs);
                    if ($index !== false) {
                        unset($idOfHinhThucs[$index]);
                        $idOfHinhThucs = implode(',', $idOfHinhThucs);
                        $item->daily = $idOfHinhThucs;
                        $item->save();
                    }
                }
                else if(strlen($idOfHinhThucs) == 1) {
                    if($idOfHinhThucs == $id) {
                        $item->daily = '';
                        $item->save();
                    }
                }
            }
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
