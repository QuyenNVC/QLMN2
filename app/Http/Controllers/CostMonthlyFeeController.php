<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyFee;
use App\Models\CostMonthlyFee;
use App\Models\CostMonthlyFeeMonthlyFee;
use App\Services\CostMonthlyFeeService;

class CostMonthlyFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.cost_monthly_fee');
    }

    public function getList($id_coso = null) {
        try {
            $data = [
                'status' => true,
                'costMonthlyFees' => CostMonthlyFeeService::getData($id_coso, null, null)
            ];
        }
        catch(Exception $e) {
            return $e->getMessage();
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
            $costMonthlyFeeItem = new CostMonthlyFee();
            $costMonthlyFeeItem->school_year = $request->school_year;
            $costMonthlyFeeItem->grade = $request->grade_id;
            $costMonthlyFeeItem->effective_date = $request->effective_date ? $request->effective_date : '';
            $costMonthlyFeeItem->note = $request->note;
            $costMonthlyFeeItem->save();
            // có mảng giá trị học phí $request->fees[{id}]
            // CostMonthlyFeeMonthlyFee::where('id_cost', $costMonthlyFeeItem->id)->delete();

            
            
            foreach($request->fees as $fee) {
                $costMonthlyFee_monthlyFee = new CostMonthlyFeeMonthlyFee();
                $costMonthlyFee_monthlyFee->id_cost = $costMonthlyFeeItem->id;
                $costMonthlyFee_monthlyFee->id_type = $fee['id'];
                $costMonthlyFee_monthlyFee->cost = $fee['value'];
                $costMonthlyFee_monthlyFee->save();
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
            $costMonthlyFeeItem = CostMonthlyFee::where("id", $request->id)->get()->first();
            if($costMonthlyFeeItem) {
                $costMonthlyFeeItem->school_year = $request->school_year;
                $costMonthlyFeeItem->grade = $request->grade_id;
                $costMonthlyFeeItem->effective_date = $request->effective_date ? $request->effective_date : '';
                $costMonthlyFeeItem->note = $request->note;
                $costMonthlyFeeItem->save();

                CostMonthlyFeeMonthlyFee::where('id_cost', $request->id)->delete();
                foreach($request->fees as $fee) {
                    $costMonthlyFee_monthlyFee = new CostMonthlyFeeMonthlyFee();
                    $costMonthlyFee_monthlyFee->id_cost = $request->id;
                    $costMonthlyFee_monthlyFee->id_type = $fee['id'];
                    $costMonthlyFee_monthlyFee->cost = $fee['value'];
                    $costMonthlyFee_monthlyFee->save();
                }

                $data = [
                    'status' => true,
                    // 'message' => 'Không tìm thấy định mức học phí tháng cần cập nhật'
                ];
            } else {
                $data = [
                    'status' => false,
                    'message' => 'Không tìm thấy định mức học phí tháng cần cập nhật'
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
            $costMonthlyFee = CostMonthlyFee::where("id", $id)->get()->first();
            $costMonthlyFee->isDeleted = '1';
            $costMonthlyFee->save();
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
