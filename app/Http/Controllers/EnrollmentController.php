<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.enrollment');
    }

    public function getList() {
        try {
            $enrollments = Enrollment::whereNull('isDeleted')->get();
            $data = [
                'status' => true,
                'enrollments' => $enrollments
            ];
        }
        catch(Exception $e) {
            $data = [
                'status' => false,
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
        try {
            // $request->name = 'Kỳ tuyển sinh năm 2022-2023';
            // $request->quantity = '15';
            // $request->school_year = '2';
            // $request->date_start = '2022-01-19';
            // $request->date_end = '2022-01-22';
            // $request->status = '1';
            // $request->note = 'Ghi chú';
            // $request->grades = [
            //     0 => [
            //         'id' => 2,
            //         'name' => "Lớp mầm"
            //     ],
            //     1 => [
            //         'id' => 3,
            //         'name' => "Lớp chồi"
            //     ]
            // ];
            $enrollment = new Enrollment();
            $enrollment->name = $request->name;
            $enrollment->quantity = $request->quantity;
            $enrollment->school_year = $request->school_year;
            $enrollment->date_start = $request->date_start;
            $enrollment->date_end = $request->date_end;
            $enrollment->status = $request->status;
            $enrollment->note = $request->note;
            $grades = [];
            foreach($request->grades as $grade) {
                $grades[] = $grade['id'];
            }
            $enrollment->grades = implode(',', $grades);
            $enrollment->save();
        }
        catch(Exception $e) {
            $data = [
                'status' => false,
                'message' => $e
            ];
        }
        return json_encode($data);
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
        // $request->id = '5';
        // $request->name = 'Kỳ tuyển sinh năm 2022-2023';
        //     $request->quantity = '15';
        //     $request->school_year = '2';
        //     $request->date_start = '2022-01-19';
        //     $request->date_end = '2022-01-22';
        //     $request->status = '1';
        //     $request->note = 'Ghi chú';
        //     $request->grades = [
        //         0 => [
        //             'id' => 2,
        //             'name' => "Lớp mầm"
        //         ],
        //         1 => [
        //             'id' => 3,
        //             'name' => "Lớp chồi"
        //         ]
        //     ];
        try {
            $enrollment = Enrollment::where("id", $request->id)->get()->first();
            if($enrollment) {
                $enrollment->name = $request->name;
                $enrollment->quantity = $request->quantity;
                $enrollment->school_year = $request->school_year;
                $enrollment->date_start = $request->date_start;
                $enrollment->date_end = $request->date_end;
                $enrollment->status = $request->status;
                $enrollment->note = $request->note;
                $grades = [];
                foreach($request->grades as $grade) {
                    $grades[] = $grade['id'];
                }
                $enrollment->grades = implode(',', $grades);
                $enrollment->save();
                $data = [
                    'status' => true
                ];
            } else {
                $data = [
                    'status' => false,
                    'message' => 'Không tìm thấy kỳ tuyển sinh cần cập nhật'
                ];
            }
        }
        catch(Exception $e) {
            $data = [
                'status' => false
            ];
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
        try {
            $enrollment = Enrollment::where("id", $id)->get()->first();
            $enrollment->isDeleted = '1';
            $enrollment->save();
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
