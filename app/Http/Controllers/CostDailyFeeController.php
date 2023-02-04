<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CostDailyFee;
use App\Models\Classes;
use App\Models\SchoolYear;
use App\Models\DailyFee;
use Illuminate\Support\Facades\Auth;

class CostDailyFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.cost_daily_fee');
    }

    public function getList(Request $request) {
        try {
            $classes = Classes::where('school_year', $request->get('school_year'));
            if($request->get('id_coso')) {
                $classes = $classes->where('id_coso', $request->get('id_coso'));
            }
            $classes = $classes->get();
            foreach($classes as $class) {
                $costDailyFees = CostDailyFee::where('id_class', $class->id)->get();
                $class['costDailyFees'] = $costDailyFees;
            }
            $data = [
                'status' => true,
                'classes' => $classes,
            ];
        }
        catch(Exception $e) {
            $data = [
                'message' => $e
            ];
        }
        return json_encode($data);
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
            CostDailyFee::where('id_class', $request->id_class)->delete();
            
            $dailyFees = DailyFee::all();
            foreach($dailyFees as $dailyFee) {
                $costDailyFee = new CostDailyFee();
                $costDailyFee->id_class = $request->id_class;
                
                $costDailyFee->id_type = $dailyFee['id'];
                $costDailyFee->cost = $request[$dailyFee['id']];
                if(Auth::user()->cannot('save', $dailyFee)) {
                    abort(403);
                }
                $costDailyFee->save();
            }
            $data = [
                'status' => true,
                // 'request' => $request
            ];
        }
        catch(Exception $e) {
            return $e;
        }
        return json_encode($data);
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
