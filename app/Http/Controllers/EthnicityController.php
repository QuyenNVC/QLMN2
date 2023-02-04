<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ethnicity;

class EthnicityController extends Controller
{
    public function getList(Request $request) {
        try {
            $ethnicities = Ethnicity::all();
            $data = [
                'ethnicities'  => $ethnicities,
                'status' => true
            ];
        }
        catch (Exception $e) {
            $data = [
                status => false
            ];
        };
        return json_encode($data);
    }

    public function store(Request $request) {
        try {
            $ethnicity = new Ethnicity();
            $ethnicity->name = $request->name;
            $ethnicity->save();
            $data = [
                'status'    =>  true
            ];
        }
        catch(Exceptioin $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return json_encode($data);
    }
}
