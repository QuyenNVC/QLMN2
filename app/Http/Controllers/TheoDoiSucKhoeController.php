<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TheoDoiSucKhoe;
use App\Models\Record;
use App\Models\HeightStandard;
use App\Models\BmiStandard;
use App\Models\WeigthStandard;
use App\Services\ApiResultService;
use App\Services\MonitorPhysicalService;
use App\Services\RecordService;
use App\Http\Controllers\helpers\HelperController;

class TheoDoiSucKhoeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.theo_doi_suc_khoe');
    }

    public function getList(Request $request) {
        try {
            $date = $request->month;
            $request->year = HelperController::format_month_year_helper($date)[1];
            $request->month = HelperController::format_month_year_helper($date)[0];
            $students = RecordService::getData($request, null)['items'];
            $dataTheoDoiSucKhoes = [];
            if($students) {
                foreach($students as $item) {
                    $data = TheoDoiSucKhoe::where('month', $request->month)->where('year', $request->year)->where('ma_hs', $item->id)->get()->first();
                    if(!$data) {
                        $data = new TheoDoiSucKhoe();
                        $data->month = $request->month;
                        $data->year = $request->year;
                        $data->ma_hs = $item->id;
                        $data->save();
                    }
                    $dayDiff = $this->tinhThangTuoi($item->birth_date);
                    $age_standard = $dayDiff->y;
                    $month_standard = $dayDiff->m;
                    if($month_standard == 0) {
                        if($age_standard > 0) {
                            $month_standard = 12;
                            $age_standard = $age_standard - 1;
                        } else {
                            $month_standard = 1;
                        }
                    }
                    $height_standard =  HeightStandard::where('age', $age_standard)->where('month', $month_standard)->get()->first();
                    $weigth_standard =  WeigthStandard::where('age', $age_standard)->where('month', $month_standard)->get()->first();
                    $bmi_standard =  BmiStandard::where('age', $age_standard)->where('month', $month_standard)->get()->first();
                    if($item->gender) {
                        if($height_standard->data) {
                            $height_standard  = json_decode($height_standard->data, true)['male'];
                            $min_height = isset($height_standard['male_5']) ? $height_standard['male_5'] : '';
                            $max_height = isset($height_standard['male_6']) ? $height_standard['male_6'] : '';
                            $height_standard_range = $min_height.' - '.$max_height;
                        } else {
                            $height_standard_range = '';
                        }
                        
                        if($weigth_standard->data) {
                            $weigth_standard  = json_decode($weigth_standard->data, true)['male'];
                            $min_weigth = isset($weigth_standard['male_5']) ? $weigth_standard['male_5'] : '';
                            $max_weigth = isset($weigth_standard['male_6']) ? $weigth_standard['male_6'] : '';
                            $weigth_standard_range = $min_weigth.' - '.$max_weigth;
                        } else {
                            $weigth_standard_range = '';
                        }
                        
                        if($bmi_standard->data) {
                            $bmi_standard  = json_decode($bmi_standard->data, true)['male'];
                            $min_bmi = isset($bmi_standard['male_3']) ? $bmi_standard['male_3'] : '';
                            $max_bmi = isset($bmi_standard['male_4']) ? $bmi_standard['male_4'] : '';
                            $bmi_standard_range = $min_bmi.' - '.$max_bmi;
                        } else {
                            $bmi_standard_range = '';
                        }
                    } else {
                        if($height_standard->data) {
                            $height_standard  = json_decode($height_standard->data, true)['male'];
                            $min_height = isset($height_standard['female_5']) ? $height_standard['female_5'] : '';
                            $max_height = isset($height_standard['female_6']) ? $height_standard['female_6'] : '';
                            $height_standard_range = $min_height.' - '.$max_height;
                        } else {
                            $height_standard_range = '';
                        }
                        
                        if($weigth_standard->data) {
                            $weigth_standard  = json_decode($weigth_standard->data, true)['male'];
                            $min_weigth = isset($weigth_standard['female_5']) ? $weigth_standard['female_5'] : '';
                            $max_weigth = isset($weigth_standard['female_6']) ? $weigth_standard['female_6'] : '';
                            $weigth_standard_range = $min_weigth.' - '.$max_weigth;
                        } else {
                            $weigth_standard_range = '';
                        }
                        
                        if($bmi_standard->data) {
                            $bmi_standard  = json_decode($bmi_standard->data, true)['male'];
                            $min_bmi = isset($bmi_standard['female_3']) ? $bmi_standard['female_3'] : '';
                            $max_bmi = isset($bmi_standard['female_4']) ? $bmi_standard['female_4'] : '';
                            $bmi_standard_range = $min_bmi.' - '.$max_bmi;
                        } else {
                            $bmi_standard_range = '';
                        }
                    }
                    $theo_doi_suc_khoe_i = [
                        'ma_hs' => $item->id,
                        'name'  =>  $item->name,
                        'height'  => $data->height,
                        'weigth'  => $data->weigth,
                        'evaluate_height'  => $data->evaluate_height,
                        'evaluate_weigth'  => $data->evaluate_weigth,
                        'evaluate_physical'  => $data->evaluate_physical,
                        'cognitive_development'  => $data->cognitive_development,
                        'talent_development'  => $data->talent_development,
                        'relationship_development'  => $data->relationship_development,
                        'language_development'  => $data->language_development,
                        'be_khoe'  => $data->be_khoe,
                        'be_ngoan'  => $data->be_ngoan,
                        'months_old'    =>  $month_standard + $age_standard*12,
                        'height_standard'   =>  $height_standard_range,
                        'weigth_standard'   =>  $weigth_standard_range,
                    ];
                    $dataTheoDoiSucKhoes[] = $theo_doi_suc_khoe_i;
                }
            }
            $data = [
                'status'    =>  true,
                'dataTheoDoiSucKhoes'    => $dataTheoDoiSucKhoes
            ];
        } catch(Exception $e) {
            $data = [
                'status'    => false,
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
    public function update(Request $request)
    {
        try {
            $date = $request->month;
            $year = HelperController::format_month_year_helper($date)[1];
            $month = HelperController::format_month_year_helper($date)[0];
            foreach($request->rowsChanged as $row) {
                $item = TheoDoiSucKhoe::where('ma_hs', $row["ma_hs"])->where('month', $month)->where('year', $year)->get()->first();
                if($item) {
                    $item->height = is_numeric($row['height']) ? $row['height'] : null;
                    $item->weigth = is_numeric($row['weigth']) ? $row['weigth'] : null;
                    $item->evaluate_height = $row['evaluate_height'] ? $row['evaluate_height'] : null;
                    $item->evaluate_weigth = $row['evaluate_weigth'] ? $row['evaluate_weigth'] : null;
                    $item->evaluate_physical = $row['evaluate_physical'] ? $row['evaluate_physical'] : null;
                    $item->cognitive_development = $row['cognitive_development'] ? $row['cognitive_development'] : null;
                    $item->talent_development = $row['talent_development'] ? $row['talent_development'] : null;
                    $item->relationship_development = $row['relationship_development'] ? $row['relationship_development'] : null;
                    $item->language_development = $row['language_development'] ? $row['language_development'] : null;
                    $item->be_khoe = $row['be_khoe'] ? '1' : null;
                    $item->be_ngoan = $row['be_ngoan'] ? '1' : null;
                    $item->save();
                }
            }
            $data = [
                'status'    =>  true,
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

    private function tinhThangTuoi($birth_day) {
        $curDate = date_create();
        $birth_day = date_create($birth_day);
        $months = $curDate->diff($birth_day);
        return $months;
    }

    /**
     * Function get data employee resume
     * @author HaoDT
     */
    public function getDataMonitorPhysical(Request $request)
    {
        try {
            $monitorPhysicalIds = $request->get('monitor_physical__ids', []);
            $excludes = $request->get('excludes_ids', []);
            $class = $request->get('class', []);
            $grades = $request->get('grades', []);
            $month = $request->get('month', []);
            $year = $request->get('year', []);
            $certification = $request->get('certification', []);
            $pageSize = $request->get('page_size', 10);
            $page = $request->get('page', 1);
            $data = MonitorPhysicalService::getData($monitorPhysicalIds, $excludes, $class, $grades, $month, $year, $certification, $pageSize, $page);
            return ApiResultService::success($data);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }
}
