<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnnualFee;
use App\Models\CostAnnualFee;
use App\Models\CostAnnualFeeAnnualFee;
use App\Services\CostAnnualFeeService;

class CostAnnualFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.cost_annual_fee');
    }

    public function getList($id_coso = null) {
        try {
            $data = [
                'status' => true,
                'costAnnualFees' => CostAnnualFeeService::getData($id_coso, null, null)
            ];
        }
        catch(Exception $e) {
            return $e->getModel();
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
        try {
            $costAnnualFeeItem = new CostAnnualFee();
            $costAnnualFeeItem->school_year = $request->school_year;
            $costAnnualFeeItem->grade = $request->grade_id;
            $costAnnualFeeItem->effective_date = $request->effective_date ? $request->effective_date : null;
            $costAnnualFeeItem->note = $request->note;
            $costAnnualFeeItem->save();
            
            foreach($request->fees as $fee) {
                $costAnnualFee_AnnualFee = new CostAnnualFeeAnnualFee();
                $costAnnualFee_AnnualFee->id_cost = $costAnnualFeeItem->id;
                $costAnnualFee_AnnualFee->id_type = $fee['id'];
                $costAnnualFee_AnnualFee->cost = $fee['value'];
                $costAnnualFee_AnnualFee->save();
            }
            $data = [
                'status' => true
            ];
            return json_encode($data);
        }
        catch(Exception $e) {
            $data = [
                'message' => $e
            ];
            return json_encode($data);
        }
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
            $costAnnualFeeItem = CostAnnualFee::where("id", $request->id)->get()->first();
            if($costAnnualFeeItem) {
                $costAnnualFeeItem->school_year = $request->school_year;
                $costAnnualFeeItem->grade = $request->grade_id;
                $costAnnualFeeItem->effective_date = $request->effective_date ? $request->effective_date : null;
                $costAnnualFeeItem->note = $request->note;
                $costAnnualFeeItem->save();
                
                CostAnnualFeeAnnualFee::where('id_cost', $request->id)->delete();
                foreach($request->fees as $fee) {
                    $costAnnualFee_AnnualFee = new CostAnnualFeeAnnualFee();
                    $costAnnualFee_AnnualFee->id_cost = $request->id;
                    $costAnnualFee_AnnualFee->id_type = $fee['id'];
                    $costAnnualFee_AnnualFee->cost = $fee['value'];
                    $costAnnualFee_AnnualFee->save();
                }

                $data = [
                    'status' => true,
                ];
            } else {
                $data = [
                    'status' => false,
                    'message' => 'Không tìm thấy định mức học phí năm cần cập nhật'
                ];
            }
            return json_encode($data);
        }
        catch(Exception $e) {
            $data = [
                'message' => $e
            ];
            return json_encode($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $costAnnualFee = CostAnnualFee::where("id", $id)->get()->first();
            $costAnnualFee->isDeleted = '1';
            $costAnnualFee->save();
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
