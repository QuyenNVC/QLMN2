<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HeightStandard;
use App\Models\BmiStandard;
use App\Models\WeigthStandard;

class TieuChuanPhatTrienController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tieu_chuan_phat_trien');
    }

    public function getList(Request $request) {
        try {
            $table = '\\'.ucfirst($request->table).'Standard';
            $table = 'App\Models'.$table;
            if($request->age == 'all') {
                $physicalStandard = $table::all();
            } else {
                $physicalStandard = $table::where('age', $request->age)->get();
            }
            if(!$physicalStandard->first()) {
                for($i = 0; $i < 6; $i++) {
                    for($j = 1; $j < 13 ; $j++) {
                        $item = new $table();
                        $item->age  = $i;
                        $item->month = $j;
                        $item->save();
                    }
                }
                if($request->age == 'all') {
                    $physicalStandard = $table::all();
                } else {
                    $physicalStandard = $table::where('age', $request->age)->get();
                }
            }
            for($i = 0 ; $i < count($physicalStandard); $i++) {
                $item = $physicalStandard[$i];
                $data = json_decode($item->data, true);
                $physicalStandard[$i]->month = $physicalStandard[$i]->month + $physicalStandard[$i]->age*12;
                if(isset($data["male"])) {
                    $male = $data["male"];
                    foreach($data['male'] as $key => $info) {
                        $physicalStandard[$i][$key] = $info;
                    }
                }
                if(isset($data["female"])) {
                    $female = $data["female"];
                    foreach($data['female'] as $key => $info) {
                        $physicalStandard[$i][$key] = $info;
                    }
                }
            }
            $data = [
                'status'    =>  true,
                'physicalStandard'  => $physicalStandard
            ];
        } catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e
            ];
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $table = '\\'.ucfirst($request->table).'Standard';
            $table = 'App\Models'.$table;
            foreach($request->rowsChanged as $row) {
                $item = $table::find($row['id']);
                if($item) {
                    $male = [];
                    $female = [];
                    for($i = 1; $i < 11; $i ++) {
                        if(isset($row['male_'.$i])) {
                            $male['male_'.$i] = is_numeric($row['male_'.$i]) && $row['male_'.$i] == 0 ? $row['male_'.$i] : '';
                        }
                        if(isset($row['female_'.$i])) {
                            $female['female_'.$i] = is_numeric($row['female_'.$i]) && $row['female_'.$i] == 0 ? $row['female_'.$i] : '';
                        }
                    }
                    $standard['male'] = $male;
                    $standard['female'] = $female;
                    $item->data = json_encode($standard);
                    $item->save();
                }
            }
            
            $data = [
                'status'    => true,
            ];
        } catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e
            ];
        }
        return response()->json($data);
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
}
