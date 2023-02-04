<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Record;
use App\Models\ClassTeacher;
use Illuminate\Support\Facades\DB;
use App\Services\ApiResultService;
use App\Services\ClassService;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    protected static $table = 'class';
    public function index()
    {
        return view('admin.class');
    }

    public function getList(Request $request) {
        try{
            // $lops = Classes::all();
            $lops = Classes::leftJoin('class_teacher','class.id','=','class_teacher.id_class')
            ->leftJoin('nhan_vien', 'class_teacher.id_giaovien', '=', 'nhan_vien.ma_nv')
            ->leftJoin('grade', 'class.grade', '=', 'grade.id');
            if($request->id_coso) {
                $lops = $lops->where('id_coso', $request->id_coso);
            }
            if($request->school_year) {
                $lops = $lops->where('school_year', $request->school_year);
            }
            if($request->grade) {
                $lops = $lops->where('grade', $request->id_coso);
            }
            $lops = $lops->select(['class.*','nhan_vien.fullname as home_teacher', 'nhan_vien.ma_nv as home_teacher_id', 'grade.name as grade_name'])
            ->orderByDesc('id')->get();
            $data = [
                'status' => true,
                'lops' => $lops,
                // 'id_coso'    =>  Auth::user()->id_coso
            ];
        }
        catch(Exception $e) {
            $data = [
                'status' => false
            ];
        }
        return json_encode($data);
    }

    public function getListActive(Request $request) {
        try {
            // $lops = Classes::where('class.status','<>',2)->where(`class.quantity`, '<', `class.max_quantity`)->get();
            // $lops = Classes::all();
            $lops = Auth::user()->id_coso ? DB::select("select * from class where status != 2 and quantity < max_quantity and id_coso = ".Auth::user()->id_coso." order by 'id' desc") : DB::select("select * from class where status != 2 and quantity < max_quantity order by 'id' desc");
            $data = [
                'lops'  => $lops,
                'status' => true,
                'id_coso'    =>  Auth::user()->id_coso
            ];
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => "Lỗi Server"
            ];
        };
        return json_encode($data);
    }

    public function create(Request $request) {
        try {
            $class = new Classes();
            $class->name = $request->name;
            $class->grade = $request->grade;
            $class->quantity = "0";
            $class->max_quantity = $request->max_quantity;
            $class->time_start = $request->time_start ? $request->time_start : null;
            $class->time_end = $request->time_end ? $request->time_end : null;
            $class->school_year = $request->school_year;
            $class->class_room = $request->class_room;
            $class->note = $request->note;
            $class->status = "1"; //mặc định là lớp đang hoạt động;
            $class->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
            if(Auth::user()->cannot('save', $class)) {
                abort(403);
            }
            $class->save();
            $class_teacher = new ClassTeacher();
            $class_teacher->id_class = $class->id;
            $class_teacher->id_giaovien = $request->home_teacher;
            $class_teacher->save();
            $data = [
                'status' => true
            ];
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => "Lỗi Server"
            ];    
        }
        return json_encode($data);
    }

    public function update(Request $request) {
        try {
            $class = Classes::where('id', $request->id)->first();
            if ( $class ) {
                $class->name = $request->name;
                $class->grade = $request->grade;
                $class->max_quantity = $request->max_quantity;
                $class->time_start = $request->time_start;
                $class->time_end = $request->time_end;
                $class->school_year = $request->school_year;
                $class->class_room = $request->class_room;
                $class->status = "1"; //mặc định là lớp đang hoạt động;
                $class->note = $request->note;
                $class->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
                $this->save($class, self::$table);
                Record::where('id_class', $class->id)->update(['id_coso' => $class->id_coso]);

                $class_teacher = ClassTeacher::where('id_class', $request->id)->first();
                if($class_teacher) {
                    ClassTeacher::where('id_class', $request->id)->delete();
                }
                $class_teacher = new ClassTeacher();
                $class_teacher->id_class = $request->id;
                $class_teacher->id_giaovien = $request->home_teacher;
                $class_teacher->save(); 
                $data = [
                    'status' => true,
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
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
        
    }

    public function destroy(Classes $class) {
        try{
            $this->delete($class, self::$table);
            ClassTeacher::where("id_class", $class->id)->delete();
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

    public function getClass(Request $request, $id) {
        try{
            // $lops = Classes::all();
            $lop = Classes::leftJoin('class_teacher','class.id','=','class_teacher.id_class')
            ->leftJoin('nhan_vien', 'class_teacher.id_giaovien', '=', 'nhan_vien.ma_nv')
            ->leftJoin('grade', 'class.grade', '=', 'grade.id')
            ->where("class.id", $id)
            ->select(['class.*','nhan_vien.ma_nv as home_teacher', 'grade.id as grade'])
            ->get();
            $data = [
                'status' => true,
                'lop' => $lop
            ];
        }
        catch(Exception $e) {
            $data = [
                'status' => false
            ];
        }
        return json_encode($data);
    }

    public function getClassesByFilter(Request $request, $school_year, $grade) {
        try {
            $items = Classes::where('status', '!=', 2);
            if($school_year != 'undefined' && $grade != 'undefined') {
                $items = $items->where('grade', $grade)->where('school_year', $school_year);
            }
            if($request->id_coso) {
                $items = $items->where('id_coso', $request->id_coso);
            }
            $items = $items->select(['id', 'name'])->get();
            $data = [
                'status'    =>  true,
                'items'     =>  $items
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
     * getDataTeacherClass get data teacher class
     * @author HaoDT
     */
    public function getDataTeacherClass(Request $request)
    {
        try {
            $classIds = $request->get('class_ids', []);
            $excludes = $request->get('excludes_ids', []);
            $departments = $request->get('department', []);
            $class = $request->get('class', []);
            $pageSize = $request->get('page_size', 10);
            $page = $request->get('page', 1);
            $data = ClassService::getData($classIds, $excludes, $class, $departments, $pageSize, $page);
            return ApiResultService::success($data);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }
}
