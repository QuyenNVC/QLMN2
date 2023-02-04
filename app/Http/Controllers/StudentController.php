<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Record;
use App\Models\Classes;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request, &$id = null) {
        return view('admin.student_list');
    }

    // public function getTreeList(Request $request) {
    //     try {
    //         $school_year = $request->school_year;
    //         $grade = $request->grade;
    //         $class_id = $request->class;
    //         $treeData = [];
    //         $treeType = [];

    //         // $classes = Classes::all();
    //         $classes = Classes::where('school_year', $school_year);
    //         if(!empty($grade)) {
    //             $classes = $classes->where('grade', $grade);
    //         }
    //         $classes = $classes->get();
            
    //         $treeType_0 = [];
    //         $treeType_0["type"] = "#"; 
    //         $valid_children_0 = [];

    //         for($i=0; $i<count($classes); $i++) {
    //             $valid_children_0[] = "LOP".$classes[$i]['id'];

    //             $treeType_i = [
    //                 "icon" => "far fa-hospital",
    //                 "is_content" => false,
    //                 "type" => "LOP".$classes[$i]['id'],
    //                 "id"    =>  $classes[$i]['id']
    //             ];
                

    //             //tree data
    //             $treeData_i = [
    //                 "count" => 0,
    //                 "id" => $classes[$i]['id'],
    //                 "is_content" => false,
    //                 "text" => $classes[$i]['name'],
    //                 "type" => "LOP".$classes[$i]['id']
    //             ];

    //             $valid_children_i = [];
    //             $treeData_children_i = [];
    //             $studentClass = Record::where('id_class', $classes[$i]['id'])->whereNull('isDeleted')->get();
                
    //             if(!empty($class_id) && $class_id == $classes[$i]['id']) {
    //                 for($j=0; $j<count($studentClass); $j++) {
    //                     $valid_children_i[] = $studentClass[$j]["id"];
    
    //                     //tree data
    //                     $treeData_children_item = [
    //                         "count" => 0,
    //                         "is_content" => true,
    //                         "text" => $studentClass[$j]["name"],
    //                         "type" => $studentClass[$j]["id"],
    //                         "icon" => "far fa-user"
    //                     ];
    //                     $treeData_children_i[] = $treeData_children_item;
    //                 }
    //             }

    //             if(count($studentClass) > 0) {
    //                 $treeData_children_i[] = [1];
    //             }

    //             $treeData_i["children"] = $treeData_children_i;
    //             $treeType_i['valid_children'] = $valid_children_i;
                
                
    //             $treeData[] = $treeData_i;
    //             $treeType[] = $treeType_i;
    //         }

    //         // Xử lý học sinh chưa phân lớp
    //         $valid_children_0[] = "Chưa phân lớp";
    //         $treeType_i = [
    //             "icon" => "far fa-hospital",
    //             "is_content" => false,
    //             "type" => "Chưa phân lớp",
    //             "id"    =>  'undefined'
    //         ];
    //         $treeData_i = [
    //             "count" => 0,
    //             "id" => 'undefined',
    //             "is_content" => false,
    //             "text" => "Học sinh chưa phân lớp",
    //             "type" => "Chưa phân lớp",
    //         ];
    //         $valid_children_i = [];
    //         $treeData_children_i = [];
    //         $studentClass = Record::whereNull('id_class')->whereNull('isDeleted')->get();

    //         if($class_id == 'undefined') {
    //             for($j=0; $j<count($studentClass); $j++) {
    //                 $valid_children_i[] = $studentClass[$j]["id"];

    //                 //tree data
    //                 $treeData_children_item = [
    //                     "count" => 0,
    //                     "is_content" => true,
    //                     "text" => $studentClass[$j]["name"],
    //                     "type" => $studentClass[$j]["id"],
    //                     "icon" => "far fa-user"
    //                 ];
    //                 $treeData_children_i[] = $treeData_children_item;
    //             }
    //         }

    //         if(count($studentClass) > 0) {
    //             $treeData_children_i[] = [1];
    //         }

    //         $treeData_i["children"] = $treeData_children_i;
    //         $treeType_i['valid_children'] = $valid_children_i;
    //         $treeData[] = $treeData_i;
    //         $treeType[] = $treeType_i;


    //         $treeType_0['valid_children'] = $valid_children_0;

    //         $treeType[] = $treeType_0;

    //         if(!empty($class_id) && $class_id != 'undefined') {
    //             $allStudent = Record::where('id_class', $class_id)->get();
    //             for($i=0; $i<count($allStudent); $i++) {

    //                 $treeType_i = [
    //                     "icon" => "far fa-user",
    //                     "type" => $allStudent[$i]["id"],
    //                     'valid_children' => [],
    //                 ];
                    
    //                 $treeType[] = $treeType_i;
    //             }
    //         }

    //         if(!empty($class_id) && $class_id == 'undefined') {
    //             $allStudent = Record::whereNull('id_class')->get();
    //             for($i=0; $i<count($allStudent); $i++) {

    //                 $treeType_i = [
    //                     "icon" => "far fa-user",
    //                     "type" => $allStudent[$i]["id"],
    //                     'valid_children' => [],
    //                 ];
                    
    //                 $treeType[] = $treeType_i;
    //             }
    //         }


    //         $data = [
    //             'status' => true,
    //             'treeType' => $treeType,
    //             'treeData' => $treeData,
    //         ];
    //     }
    //     catch (Exception $e) {
    //         $data = [
    //             'status' => false
    //         ];
    //     }
    //     return json_encode($data);
    // }
}
