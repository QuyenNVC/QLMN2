<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExtraService;
use App\Models\CostExtraService;
use App\Models\CostExtraServiceExtraService;
use App\Services\CostExtraServiceService;

class CostExtraServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.cost_extra_service');
    }

    public function getList($id_coso = null) {
        try {
            $data = [
                'status' => true,
                'costExtraServices' => CostExtraServiceService::getData($id_coso, null, null)
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
            $costExtraServiceItem = new CostExtraService();
            $costExtraServiceItem->school_year = $request->school_year;
            $costExtraServiceItem->grade = $request->grade_id;
            $costExtraServiceItem->effective_date = $request->effective_date ? $request->effective_date : null;
            $costExtraServiceItem->note = $request->note;
            $costExtraServiceItem->save();
            
            foreach($request->fees as $fee) {
                $costExtraService_ExtraService = new CostExtraServiceExtraService();
                $costExtraService_ExtraService->id_cost = $costExtraServiceItem->id;
                $costExtraService_ExtraService->id_type = $fee['id'];
                $costExtraService_ExtraService->cost = $fee['value'];
                $costExtraService_ExtraService->save();
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
            $costExtraServiceItem = CostExtraService::where("id", $request->id)->get()->first();
            if($costExtraServiceItem) {
                $costExtraServiceItem->school_year = $request->school_year;
                $costExtraServiceItem->grade = $request->grade_id;
                $costExtraServiceItem->effective_date = $request->effective_date ? $request->effective_date : null;
                $costExtraServiceItem->note = $request->note;
                $costExtraServiceItem->save();

                CostExtraServiceExtraService::where('id_cost', $request->id)->delete();
                foreach($request->fees as $fee) {
                    $costExtraService_ExtraService = new CostExtraServiceExtraService();
                    $costExtraService_ExtraService->id_cost = $request->id;
                    $costExtraService_ExtraService->id_type = $fee['id'];
                    $costExtraService_ExtraService->cost = $fee['value'];
                    $costExtraService_ExtraService->save();
                }

                $data = [
                    'status' => true,
                ];
            } else {
                $data = [
                    'status' => false,
                    'message' => 'Không tìm thấy định mức dịch vụ cộng thêm cần cập nhật'
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
            $costExtraService = CostExtraService::where("id", $id)->get()->first();
            $costExtraService->isDeleted = '1';
            $costExtraService->save();
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
