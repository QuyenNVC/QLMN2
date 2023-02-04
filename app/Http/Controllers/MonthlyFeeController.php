<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyFee;
use Illuminate\Support\Facades\Auth;

class MonthlyFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.monthly_fee');
    }

    public function getList(Request $request) {
        try {
            if($request->get('id_coso')) {
                $monthlyfees = MonthlyFee::whereNull ('isDeleted')->where('id_coso', $request->get('id_coso'))->get();
            } else {
                $monthlyfees = MonthlyFee::whereNull ('isDeleted')->get();
            }
            $data = [
                'monthlyfees'  => $monthlyfees,
                'status' => true,
                'id_coso'    =>  Auth::user()->id_coso
            ];
        }
        catch (Exception $e) {
            $data = [
                status => false
            ];
        };
        return json_encode($data);
    }

    public function create(Request $request) {
        try {
            $monthlyfee = new MonthlyFee();
            $monthlyfee->name = $request->name;
            $monthlyfee->note = $request->note;
            $monthlyfee->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
            if(Auth::user()->cannot('save', $monthlyfee)) {
                abort(403);
            }
            $monthlyfee->save();
            $data = [
                'status' => true
            ];
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => $e
            ];    
        }
        return json_encode($data);
    }

    public function update(Request $request) {
        try {
            $monthlyfee = MonthlyFee::where('id', $request->id)->first();
            if ( $monthlyfee ) {
                $monthlyfee->name = $request->name;
                $monthlyfee->note = $request->note;
                $monthlyfee->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
                if(Auth::user()->cannot('save', $monthlyfee)) {
                    abort(403);
                }
                $monthlyfee->save();
                $data = [
                    'status' => true,
                    'monthlyfee' => $monthlyfee
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
                'message' => $e
            ];
            return json_encode($data);
        }
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Request $request, $id) {
        try {
            $monthlyfee = MonthlyFee::where("id", $id)->get()->first();
            $monthlyfee->isDeleted = '1';
            if(Auth::user()->cannot('save', $monthlyfee)) {
                abort(403);
            }
            $monthlyfee->save();
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
