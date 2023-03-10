<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnnualFee;
use Illuminate\Support\Facades\Auth;

class AnnualFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.annual_fee');
    }

    public function getList(Request $request) {
        try {
            if($request->get('id_coso')) {
                $annualfees = AnnualFee::whereNull ('isDeleted')->where('id_coso', $request->get('id_coso'))->get();
            } else {
                $annualfees = AnnualFee::whereNull ('isDeleted')->get();
            }
            $data = [
                'annualfees'  => $annualfees,
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
            $annualfee = new AnnualFee();
            $annualfee->name = $request->name;
            $annualfee->note = $request->note;
            $annualfee->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
            if(Auth::user()->cannot('save', $annualfee)) {
                abort(403);
            }
            // $annualfee->isDeleted = "";
            $annualfee->save();
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
            $annualfee = AnnualFee::where('id', $request->id)->first();
            if ( $annualfee ) {
                $annualfee->name = $request->name;
                $annualfee->note = $request->note;
                $annualfee->id_coso = Auth::user()->id_coso ? Auth::user()->id_coso : $request->id_coso;
                if(Auth::user()->cannot('save', $annualfee)) {
                    abort(403);
                }
                $annualfee->save();
                $data = [
                    'status' => true,
                    'annualfee' => $annualfee
                ];
                return json_encode($data);
            } else {
                $data = [
                    'status' => false,
                    'message' => 'L???i d??? li???u'
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
            $annualfee = AnnualFee::where("id", $id)->get()->first();
            $annualfee->isDeleted = '1';
            if(Auth::user()->cannot('save', $annualfee)) {
                abort(403);
            }
            $annualfee->save();
            $data = [
                'status' => true
            ];
        }
        catch(Exception $e) {
            $data = [
                'status' => false,
                'message' => "L???i Server"
            ]; 
        }
        return json_encode($data);
    }
}
