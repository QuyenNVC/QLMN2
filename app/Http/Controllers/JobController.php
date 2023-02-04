<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;

class JobController extends Controller
{
    public function getList(Request $request) {
        try {
            $jobs = Job::all();
            $data = [
                'jobs'  => $jobs,
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
            $job = new Job();
            $job->name = $request->name;
            $job->save();
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
