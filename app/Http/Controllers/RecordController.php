<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;
use App\Models\Classes;
use App\Models\SchoolYear;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\RecordSiblings;
use App\Service\AddLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
// use App\Models\StudentAnnualFee;
use App\Models\StudentFee;
use App\Services\ApiResultService;
use App\Services\StudentService;
use App\Services\RecordService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\helpers\HelperController;

class RecordController extends Controller
{
    public function quanLy(Request $request, &$id = null) {
        return view('admin.record')->with('id', $id);      
    }

    // API FUNCTION
    public function getList(Request $request, &$id_enrollment = null) {
        try {
            $result = RecordService::getData($request, $id_enrollment);

            $data = [
                'status' => true,
                'items' => $result["items"],
                'total' =>  $result["total"]
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
    }

    public function deleteRecord(Request $request, $id) {
        try {
            $record = Record::where('id', $id)->first();
            Storage::disk('public')->deleteDirectory('/files/ho-so-tuyen-sinh/'.$record->id);
            $record->isDeleted = 1;
            if(Auth::user()->cannot('save', $record)) {
                abort(403);
            }
            $record->save();

            RecordSiblings::where('id_older', $id)->orWhere('id_little', $id)->delete();

            $data = [
                'status' => true
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
        
    }

    public function existRecord(Request $request) {
        $recordtItem = Record::where('id', '!=', $request->mhs)->where('name', '=', $request->name)->where('birth_date', '=', $request->birth_date)->where('address', '=', $request->address)->first();
        if($recordtItem) {
            $data=[
                'status' => true,
                'student' => $studentItem
            ];
            
        } else {
            $data=[
                'status' => false
            ];
        }
        return json_encode($data);
    }

    public function getForm(&$id = null) {
        return view('admin.record_form')->with('id', $id);
    }

    public function getSiblings(Request $request) {
        try {
            $siblings = Record::where('id', '!=', $request->id)
            ->whereNull('isDeleted')
            ->where(function($q) use ($request) {
                $q->where('address', $request->address)
                ->orWhere(function($query) use ($request) {
                    $query->where('parent_phone', $request->parent_phone)
                    ->whereNotNull('parent_phone');
                })
                ->orWhere(function($query) use ($request) {
                    $query->where('mother_phone', $request->mother_phone)
                    ->whereNotNull('mother_phone');
                });
            })
            ->select('id', 'name')
            ->get();
            $data = [
                'status'    =>  true,
                'siblings'  =>  $siblings
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return json_encode($data);
    }

    public function checkExistRecord(Request $request) {
        try {
            $record = Record::where('name', $request['name'])
            ->where('address', $request['address'])
            ->where('birth_date', $request['birth_date'])
            ->where('id', '!=', $request['id'])
            ->where('id_coso', $request->id_coso)
            ->get()->first();
            if($record) {
                $data = [
                    'status' => true,
                    'exist' => true,
                ];
            } else {
                $data = [
                    'status' => true,
                    'exist' => false
                ];
            }
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return json_encode($data);
    }

    public function store(Request $request) {
        try {
            $record = Record::find($request->id);
            if(!$record) {
                $record = new Record();
                $year = date("Y");
                $month = date("m");
                if($month > 6) {
                    $school_year = $year;
                } else {
                    $school_year = $year - 1;
                }
                $school_yearItem = SchoolYear::where('name', $school_year)->get()->first();
                $enrollment = Enrollment::where('school_year', $school_yearItem->id)->get()->first();
                $record->id_enrollment =  $enrollment->id;
                $record->status = 0;
            }
            $record->name = $request->name;
            $record->biet_danh = $request->biet_danh;
            $record->gender = $request->gender;
            $record->birth_date = $request->birth_date;
            $record->address = $request->address;
            $record->height = $request->height;
            $record->weigth = $request->weigth;
            $record->ethnicity = is_array($request->ethnicity) ? $request->ethnicity['id'] : $request->ethnicity;
            $record->medical_history = $request->medical_history;
            $record->parent_name = $request->parent_name;
            $record->parent_age = $request->parent_age;
            $record->parent_job = is_array($request->parent_job) ? $request->parent_job['id'] : $request->parent_job;
            $record->parent_phone = $request->parent_phone;
            $record->parent_email = $request->parent_email;
            $record->mother_name = $request->mother_name;
            $record->mother_age = $request->mother_age;
            $record->mother_job = is_array($request->mother_job) ? $request->mother_job['id'] : $request->mother_job;
            // $record->mother_phone = $request->mother_phone;
            // $record->mother_email = $request->mother_email;
            $record->parents_note = $request->parents_note;
            $record->note = $request->note;
            $record->type_relate = $request->type_relate;
            $record->extra_services = json_encode($request->extra_services);
            $record->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
            if(Auth::user()->cannot('save', $record)) {
                abort(403);
            }
            $record->save();

            RecordSiblings::where('id_older', $record['id'])->orWhere('id_little', $record['id'])->delete();
            if($record['type_relate'] == 1) { 
                foreach($request->siblings as $sibling) {
                    $recordSiblings = new RecordSiblings();
                    $recordSiblings->id_little = $record['id'];
                    $recordSiblings->id_older = $sibling;
                    $recordSiblings->save();
                }
            }

            if($record['type_relate'] == 0) {
                foreach($request->siblings as $sibling) {
                    $recordSiblings = new RecordSiblings();
                    $recordSiblings->id_older = $record['id'];
                    $recordSiblings->id_little = $sibling['id'];
                    $recordSiblings->save();
                }
            }
            
            // XỬ LÝ FILE
            $record->image = $this->saveFile($request['image'], $record->id);
            $record->psychological_record = $this->saveFile($request['files']['file1'], $record->id);
            $record->health_record = $this->saveFile($request['files']['file2'], $record->id);
            $record->family_register = $this->saveFile($request['files']['file3'], $record->id);
            $record->birth_certificate = $this->saveFile($request['files']['file4'], $record->id);

            
            $record->save();
            
            $data = [
                'status'    => true,
                'recordId'  => $record->id
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return json_encode($data);
    }

    protected function saveFile($file, $id) {
        if(!is_null($file['content'])) {
            $file_name =  $file['name'];
            $this->handleFile($file_name, '/files/ho-so-tuyen-sinh/'.$id, $file['content']);
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

    public function updateRecordFee(Request $request) {
        try {
            $record = Record::find($request->id);
            if($record) {
                $record->method_payment = $request->method_payment;
                $record->period_payment = $request->period_payment;
                $record->extra_services = json_encode($request['extra_services']);
                $record->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
                if(Auth::user()->cannot('save', $record)) {
                    abort(403);
                }
                $record->save();
                $data = [
                    'status'    =>  true
                ];           
            }
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return json_encode($data);
    }

    public function getRecordById($id) {
        try {
            $record = Record::find($id);
            if($record) {
                if($record->type_releate == 0 || $record->type_releate == 1) {
                    $dataSiblings = [];
                    if($record->type_relate == 0) {
                        $siblings = RecordSiblings::where('id_older', $id)->get();
                        if($siblings) {
                            foreach($siblings as $sibling) {
                                $student = Record::find($sibling['id_little']);
                                $dataSiblings[] = $student->id;
                            }
                        }
                    }
                    if($record->type_relate == 1) {
                        $siblings = RecordSiblings::where('id_little', $id)->get();
                        if($siblings) {
                            foreach($siblings as $sibling) {
                                $student = Record::find($sibling['id_older']);
                                $dataSiblings[] = $student->id;
                            }
                        }
                    }
                    $record->siblings = $dataSiblings;
                }
                $record['urlImage'] = asset("storage/files/ho-so-tuyen-sinh/$record->id/$record->image");
                $record['urlFile1'] = asset("storage/files/ho-so-tuyen-sinh/$record->id/$record->psychological_record");
                $record['urlFile2'] = asset("storage/files/ho-so-tuyen-sinh/$record->id/$record->health_record");
                $record['urlFile3'] = asset("storage/files/ho-so-tuyen-sinh/$record->id/$record->family_register");
                $record['urlFile4'] = asset("storage/files/ho-so-tuyen-sinh/$record->id/$record->birth_certificate");

                $data = [
                    'status' => true,
                    'record' => $record
                ];
            } else {
                $data = [
                    'status'    =>  false,
                    'message'   => 'Hồ sơ không tồn tại'
                ];
            }
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return json_encode($data);
    }

    public function arrangeClassMultiRecord(Request $request) {
        try {
            if(empty($request->selected)) {
                $data = [
                    'status'    =>  false,
                    'message'   => 'Chưa có học sinh nào được chọn'
                ];
                return json_encode($data);
            }
            $selected = $request->selected;
            $excludes = $request->excludes;
            $items = RecordService::getData($request, null)["items"]->toArray();
            $arrays = HelperController::arraySelected($items, $selected, $excludes);
            foreach($arrays as $ma_ho_so) {
                $this->arrangeClassAuto($ma_ho_so);
            }
            $data = [
                'status'    =>  true,
                'message'   => 'Xếp lớp thành công'
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return json_encode($data);
    }

    protected function arrangeClassAuto($ma_ho_so) {
        try {
            $record = Record::find($ma_ho_so);
            if($record && $record->status == 0) { 
                $birth_date = explode('-', $record->birth_date);
                $birth_year = $birth_date[0];
                $current_year = date("Y");
                $age = $current_year - $birth_year;
                $grade = '';
                if($age >= 3) { //lớp mầm chồi lá
                    switch ($age) {
                        case 3:
                            $grade = 2;
                            break;
                        case 4:
                            $grade = 3;
                            break;
                        case 5:
                            $grade = 4;
                            break;
                    }
                    
                } else {
                    $birth_month = $birth_date[1];
                    $current_month = date("m");
                    $countMonth = (int)$age*12 + (int)$current_month - (int)$current_month;
                    if($countMonth < 6) {
                        $data = [
                            'status' => false,
                            'message'   => 'Không có khối lớp phù hợp cho học sinh'
                        ];
                        return json_encode($data);
                    } else if($countMonth >= 6 && $countMonth <= 12) {
                        $grade = 5;
                    } else if($countMonth >= 13 && $countMonth <= 18) {
                        $grade = 6;
                    } else if($countMonth >= 19 && $countMonth <= 24) {
                        $grade = 7;
                    } else if($countMonth >= 25 && $countMonth <= 36) {
                        $grade = 8;
                    }
                }
                $enrollment = Enrollment::find($record->id_enrollment);
                if(!$enrollment) {
                    return false;
                }
                $grades_enrollment = explode(',', $enrollment->grades);
                if(!in_array($grade, $grades_enrollment)) {
                    return false;
                }
                $class = DB::select("select * from class where status = 1 and quantity < max_quantity and grade = $grade and school_year = $enrollment->school_year and id_coso = $record->id_coso");
                if(!$class) {
                    return false;
                }
                $class = Classes::find($class[0]->id);
                $record->id_class = $class->id;
                $record->status = 1;
                if(Auth::user()->cannot('save', $record)) {
                    abort(403);
                }
                $record->save();
                $class->quantity = (int)$class->quantity + 1;
                $class->save();

                // // Thêm dữ liệu trong table học phí học sinh sau khi chia lớp
                // $studentFee = StudentFee::where('ma_hs', $ma_ho_so)->get()->first();
                // if(!$studentFee) {
                //     $studentFee = new StudentFee();
                //     $studentFee->ma_hs = $ma_ho_so;

                //     $year = date("Y");
                //     $month = date("m");
                //     $studentFee->year = $school_year;
                //     $studentFee->month = $month;
                //     $studentFee->save();
                // }
                // // End tạo dữ liệu trong table học phí

                // $data = [
                //     'status'    =>  true,
                //     'class'   => $class
                // ];
                // return json_encode($data);
                return true;
            } else {
                // $data = [
                //     'status'    =>  false,
                //     'message'   => 'Học sinh trên đã được phân lớp'
                // ];
                // return json_encode($data);
                return false;
            }
        }
        catch(Exception $e) {
            // $data = [
            //     'status'    =>  false,
            //     'message'   => $e
            // ];
            // return json_encode($data);
            return false;
        }
    }

    public function getLastMHS() {
        try {
            // $records = Record::orderByDesc('id')->get()->first();
            // viết lại hàm SQL vì Record lcu1 này đã filter theo id_coso
            $records = DB::select('select * from enrollment_records order by id desc');
            $lastMHS = $records[0]->id + 1;
            $data = [
                'status'    =>  true,
                'lastMHS'   =>  $lastMHS
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e
            ];
        }
        return json_encode($data);
    }

    public function submitStudent(Request $request) {
        try {
            $record = Record::find($request->id);
            if(!$record) {
                $record = new Record();
                $year = date("Y");
                $month = date("m");
                if($month > 6) {
                    $school_year = $year;
                } else {
                    $school_year = $year - 1;
                }
                $school_yearItem = SchoolYear::where('name', $school_year)->get()->first();
                $enrollment = Enrollment::where('school_year', $school_yearItem->id)->get()->first();
                $record->id_enrollment =  $enrollment->id;

                $record->status = 0;
            }
            $record->name = $request->name;
            $record->biet_danh = $request->biet_danh;
            $record->gender = $request->gender;
            $record->birth_date = $request->birth_date;
            $record->address = $request->address;
            $record->ethnicity = is_array($request->ethnicity) ? $request->ethnicity['id'] : $request->ethnicity;
            $record->doi_tuong_uu_tien = is_array($request->doi_tuong_uu_tien) ? $request->doi_tuong_uu_tien['id'] : $request->doi_tuong_uu_tien;
            $record->character = $request->character;
            if(!empty($request->id_class)) {
                $record->status = 1;
                $record->id_class = is_array($request->id_class) ? $request->id_class['id'] : $request->id_class;
            }
            
            $record->parent_name = $request->parent_name;
            $record->parent_age = $request->parent_age;
            $record->parent_job = is_array($request->parent_job) ? $request->parent_job['id'] : $request->parent_job;
            $record->parent_phone = $request->parent_phone;
            $record->parent_email = $request->parent_email;
            $record->mother_name = $request->mother_name;
            $record->mother_age = $request->mother_age;
            $record->mother_job = is_array($request->mother_job) ? $request->mother_job['id'] : $request->mother_job;
            $record->parents_note = $request->parents_note;
            $record->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
            if(Auth::user()->cannot('save', $record)) {
                abort(403);
            }
            $record->save();
            $record->image = $this->saveFile($request['image'], $record->id);
            $record->ngay_vao_hoc = $request->ngay_vao_hoc;
            if($request->baoLuu) {
                $record->status = 2;
                $record->ngay_bao_luu = $request->ngay_bao_luu;
            }
            if($request->isAbsent) {
                $record->status = 3;
                $record->ngay_nghi_hoc = $request->ngay_nghi_hoc;
            }
            $record->save();
            
            $data = [
                'status'    => true,
                'recordId'  => $record->id
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e
            ];
        }
        return json_encode($data);
    }

    public function getStudentsByIdClass(Request $request, &$id_class = null) {
        try {
            // if($id_class) {
            //     $items = Record::whereNull('isDeleted')->where('id_class', $id_class)->select(['id', 'name', 'birth_date', 'id_class', 'ethnicity', 'parent_name', 'parent_phone', 'address', 'id_coso'])->orderBy('id', 'desc')->get();
            // } else {
            //     $items = Record::whereNull('isDeleted')->select(['id', 'name', 'birth_date', 'gender', 'id_class', 'parent_name', 'parent_phone', 'address', 'id_coso'])->orderBy('id', 'desc')->get();
            // }
            $items = RecordService::getData($request, null)['items'];
            
            $data = [
                'status'    =>  true,
                'items'     =>  $items,
                // 'id_coso'    =>  Auth::user()->id_coso
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ];
        }
        return response()->json($data);
    }

    public function deleteStudent(Request $request) {
        try {
            foreach($request->array as $id) {
                $item = Record::where('id', $id)->first();
                // AddLog::addLog("delete", $this->table, $id, $userCheck->username, Auth::id());
                $item->isDeleted = 1;
                $item->save();
            }
            $data = [
                'status'    =>  true
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ];
        }
        return response()->json($data);
    }

    /**
     * Function get data employee resume
     * @author HaoDT
     */
    public function getEmployeeResume(Request $request)
    {
        try {
            // $name = $request->get('name', '');
            $schoolYears = $request->get('school_year', []);
            $grades = $request->get('grades', []);
            $class = $request->get('class', []);
            $months = $request->get('months', []);
            $status = $request->get('status', -1);
            $pageSize = $request->get('page_size', 10);
            $page = $request->get('page', 1);
            $studentIds = $request->get('student_ids', []);
            $excludes = $request->get('excludes_ids', []);
            

            $data = StudentService::getData($studentIds, $excludes, $schoolYears, $grades, $class, $months, $status, $pageSize, $page);
            return ApiResultService::success($data);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }
}