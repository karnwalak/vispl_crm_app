<?php

namespace App\Http\Controllers\admin;

use App\ActivityData;
use App\Http\Controllers\Controller;
use App\LeadData;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Quotation;
use App\User;
use Session;

class AjaxController extends Controller {

    public function checkMobileNumber(Request $request) {
        $count = 0;
        if($request->id == null) {
           $count = User::where('mobileno', $request->mobilenumber)->count();
        } else {
           $count = User::where('mobileno', $request->mobilenumber)->where('id', '<>',$request->leadid)->count();
        }

        if($count > 0) {
            return response()->json([
                'status' => false,
            ]);
        } else {
            return response()->json([
                'status' =>true,
            ]);
        }

    }

    public function get_zone_users(Request $request) {
        // Get logged in user id
        $user_id = auth()->user()->id;
        // Get logged in user role
        $user_role = auth()->user()->role;
        // Get logged in user zone
        $user_zone = auth()->user()->zone;
        // Get Users
        $users = DB::table('users')->select('id', 'name')->orderBy('name', 'ASC');

        // Zone Filter
        if ($user_role == 'Country Head') {
            $users = $users->whereIn('users.role', array('Member', 'State Level Admin', 'Zonal Level Admin'));
        } elseif ($user_role == 'Zonal Level Admin') {
            $users = $users->whereIn('users.role', array('Member', 'State Level Admin', 'Zonal Level Admin'));
            $users = $users->where('users.zone', '=', $user_zone);
        } elseif ($user_role == 'State Level Admin') {
            $users = $users->whereIn('users.role', array('Member', 'State Level Admin'));
            $users = $users->where('users.zone', '=', $user_zone);
        } elseif ($user_role == 'Member') {
            $users = $users->where('users.id', '=', $user_id);
        }

        if ($request->zone_id) {
            $users = $users->where('zone', '=', $request->zone_id);
        }

        $users = $users->get();

        return json_encode($users);
    }

    public function getSourceValue(Request $request) {
        $SourceValue = DB::table('sourcevalue')->where('source_type_id', $request->source_type_id)->get();
        return response()->json([
            'data' => $SourceValue,
        ], 200);
    }

    public function checkAddLeadData(Request $request) {
        $LeadData = LeadData::where('organisation', $request->organisation)
                ->where('product_type', $request->product_type)
                ->count();
        return response()->json([
            'status' => $LeadData > 0 ? false : true,
        ]);
    }

    public function checkEditLeadData(Request $request) {
        $LeadData = LeadData::where('organisation', $request->organisation)
                ->where('product_type', $request->product_type)
                ->where('id', '<>', $request->editid)
                ->count();
        return response()->json([
            'status' => $LeadData > 0 ? false : true,
        ]);
    }
}
