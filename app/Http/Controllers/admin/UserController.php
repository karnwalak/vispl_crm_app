<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Quotation;
use Session;

class UserController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function empolyees() {
        $usersdaasetting = DB::table('users')
                ->select('users.*', 'regiondata.regionname')
                ->leftjoin('regiondata', 'regiondata.id', '=', 'users.zone')
                ->where('users.id', '!=', 1)->get();
        return view('admin.master.empolyees')->with('usersdaasetting', $usersdaasetting);
    }

    ///////////// Update Section 
    public function userregistration(Request $request) {
        date_default_timezone_set("Asia/Calcutta");
        $savedata = $request->all();
        $allradydata = DB::table('users')->where('email', '=', $savedata['employeeemail'])->get();

        if (count($allradydata) > 0) {
            Session::flash('message', "Username already exist");
            return Redirect::back();
        }

        $allradydata = DB::table('users')->where('mobileno', '=', $savedata['employeemobile'])->get();

        if (count($allradydata) > 0) {
            Session::flash('message', "User Mobile Number already exist");
            return Redirect::back();
        }

        if ($savedata['role']!= 'Country Head' && !isset($savedata['statename'])) {
            Session::flash('message', "Please Add from the zone");
            return Redirect::back();
        }
        $statename = '';
        if (isset($savedata['statename'])) {
            $statename = implode(',', $savedata['statename']);
        }


        $password = $savedata['password'];
        $password = Hash::make($password);

        if ($savedata['role'] == 'Member' || $savedata['role'] == 'State Level Admin' || $savedata['role'] == 'Zonal Level Admin' || $savedata['role'] == 'Country Head') {
            $usertype = 2;
        } else if ($savedata['role'] == 'Customer') {
            $usertype = 4;
        }

        $insertdata = array(
            'name' => $savedata['employee'],
            'username' => $savedata['employeeemail'],
            'email' => $savedata['employeeemail'],
            'mobileno' => $savedata['employeemobile'],
            'role' => $savedata['role'],
            'usertype' => $usertype,
            'status' => 1,
            'password' => $password,
            'zone' => $savedata['zone'],
            'desgnationid' => $savedata['designation'],
            'stateid' => $statename,
            'pincode' => $savedata['pincode'],
            'address' => $savedata['address'],
            'createdby' => 1,
            'created_at' => date("Y-m-d h:i:s"),
        );

        DB::table('users')->insert($insertdata);

        Session::flash('sucmessage', "Congratulations, Region has been Save");

        return Redirect::back();
    }

    ///////////// Update Section 


    public function edituser($id) {
        $usersdata = DB::table('users')->where('id', $id)->first();

        return view('admin.master.edituser')->with('usersdata', $usersdata);
    }

    ///////////// Update Section 

    public function updatepassword(Request $request) {
        date_default_timezone_set("Asia/Calcutta");
        $savedata = $request->all();

        if ($savedata['password'] != $savedata['confirmpassword']) {
            Session::flash('message', "Password Mismatched..");
            return Redirect::back();
        }

        $password = $savedata['password'];
        $password = Hash::make($password);

        $insertdata = array(
            'password' => $password,
        );

        DB::table('users')->where('id', $savedata['userid'])->update($insertdata);

        Session::flash('sucmessage', "Congratulations, Password has been Updated");

        return Redirect::back();
    }

    public function changepassword() {

        return view('admin.master.changepassword');
    }

    ///////////// Update Section 

    public function updateprofile(Request $request) {
        date_default_timezone_set("Asia/Calcutta");
        $savedata = $request->all();

        if ($savedata['role']!= 'Country Head' && !isset($savedata['statename'])) {
            Session::flash('message', "Please Add from the zone");
            return Redirect::back();
        }
        $statename = '';
        if (isset($savedata['statename'])) {
            $statename = implode(',', $savedata['statename']);
        }

        
        $insertdata = array(
            'name' => $savedata['username'],
            'email' => $savedata['useremail'],
            'mobileno' => $savedata['usermobileno'],
            'desgnationid' => $savedata['designation'],
            'role' => $savedata['role'],
            'pincode' => $savedata['userpincode'],
            'address' => $savedata['useraddress'],
        );

        if ($savedata['role'] == 'Country Head') {
            $insertdata['zone'] = '';
            $insertdata['stateid'] = '';
        } else {
            $insertdata['zone'] = $savedata['zone'];
            $insertdata['stateid'] = $statename;
        }

        DB::table('users')->where('id', $savedata['editid'])->update($insertdata);

        Session::flash('sucmessage', "Congratulations, User has been Updated");

        return redirect()->route('empolyees');
    }

}
