<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentAnnualFee;
use App\Models\Record;
use App\Models\Classes;
use App\Models\SchoolYear;
use App\Models\CostAnnualFeeAnnualFee;
use App\Models\Grade;

class StudentAnnualFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.student_annual_fee');
    }

    public function getList(Request $request) {
        try {
            // $list = Record::leftJoin('class', 'enrollment_records.id_class', '=', 'class.id')
            // ->leftJoin('student_annual_fee', 'enrollment_records.id', '=', 'student_annual_fee.ma_hs')
            // ->leftJoin('school_year', 'class.school_year', '=', 'school_year.id')
            // ->leftJoin('grade', 'grade.id', '=', 'class.grade')
            // ->leftJoin('cost_annual_fee', 'grade.id', '=', 'cost_annual_fee.grade')
            // ->where('class.id', $request->class)
            // ->where('enrollment_records.status', '<=', '1')
            // ->select(['enrollment_records.id', 'enrollment_records.name','school_year.period as school_year_name', 'cost_annual_fee.id as cost_annual_fee_id'])->get();
            $costAnnualFeeAnnualFee = CostAnnualFeeAnnualFee::join('cost_annual_fee', 'cost_annual_fee_annual_fee.id_cost', '=', 'cost_annual_fee.id')->join('annual_fee', 'annual_fee.id', '=', 'cost_annual_fee_annual_fee.id_type')->where('cost_annual_fee.grade', $request->grade)->whereNull('cost_annual_fee.isDeleted')->select(['annual_fee.name', 'cost_annual_fee_annual_fee.cost'])->get();
            $students = Record::join('student_annual_fee', 'enrollment_records.id', '=', 'student_annual_fee.ma_hs')->join('class', 'class.id', '=', 'enrollment_records.id_class')->where('id_class', $request->class)->where('enrollment_records.status', '<=', 1)->where('class.school_year', $request->school_year)->whereNull('enrollment_records.isDeleted')
            ->select(['enrollment_records.id as ma_hs', 'enrollment_records.address', 'enrollment_records.name as name', 'student_annual_fee.is_paid as is_paid'])->get();
            $data = [
                'status'    =>  true,
                'annual_fee' => $costAnnualFeeAnnualFee,
                'students'  =>  $students
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
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
    public function destroy($id)
    {
        //
    }

    public function updateIsPaid(Request $request) {
        try {
            $item = StudentAnnualFee::where('ma_hs', $request->ma_hs)->where('school_year', $request->school_year)->get()->first();
            if($item) {
                if($request->is_paid) {
                    $item->is_paid = 1;
                } else {
                    $item->is_paid = null;
                }
                $item->save();
                $data = [
                    'status'    => true
                ];
            }
            else {
                $data = [
                    'status'    =>  false
                ];
            }
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return response()->json($data);
    }
}
