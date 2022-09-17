<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Quotation;
use Session;

class MasterController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function Department()
    {
		$departmentdata = DB::table('departmentdata')->where('dartmentlavel',1)->orderBy('id', 'DESC')->get();

		$designation = DB::table('designation')->orderBy('id', 'DESC')->get();

        return view('admin.master.department')->with('departmentdata',$departmentdata)->with('designation',$designation);
    }




	///////////// Update Section

	public function saveregion(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(

				'regionname'=>$savedata["regionname"],
				'regionparent'=>0,
				'regioblavel'=>1,
				'createdy'=>$savedata['userid'],
				'createdtime'=>date("Y-m-d h:i:s"),

		);

		DB::table('regiondata')->insert($updatedata);

        Session::flash('sucmessage', "Congratulations, Region has been Save");

		return Redirect::back();
    }

	///////////// Update Section

	public function savecircle(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");

		 $savedata =  $request->all();   $circlename = '';


		$regiondatadata = DB::table('regiondata')->where('regionparent',$savedata["parentregion"])->first();

		if(isset($savedata["circlename"])) {
			$circlename = implode(',',$savedata["circlename"]);
		}

		if($regiondatadata) {
			 $updatedata = array(

					'regionname'=>$circlename,
					'regionparent'=>$savedata["parentregion"],
					'regioblavel'=>2,
					'modifyid'=>$savedata['userid'],
					'modifydate'=>date("Y-m-d h:i:s"),

			);
			DB::table('regiondata')->where('id',$regiondatadata->id)->update($updatedata);

		} else {

			 $updatedata = array(

					'regionname'=>$circlename,
					'regionparent'=>$savedata["parentregion"],
					'regioblavel'=>2,
					'createdy'=>$savedata['userid'],
					'createdtime'=>date("Y-m-d h:i:s"),

			);
			DB::table('regiondata')->insert($updatedata);
		}



        Session::flash('sucmessage2', "Congratulations, Region has been Save");

		return Redirect::back();
    }

	///////////// Update Section

	public function savedepartment(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(

				'name'=>$savedata["designation"],
				'parentid'=>0,
				'dartmentlavel'=>1,
				'createdby'=>$savedata['userid'],
				'createdtme'=>date("Y-m-d h:i:s"),

		);

		DB::table('departmentdata')->insert($updatedata);

        Session::flash('sucmessage2', "Congratulations, Department has been Save");

		return Redirect::back();
    }

	///////////// Update Section

	public function savedesignation(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(

				'designationname'=>$savedata["designation"],
				'createdby'=>$savedata['userid'],
				'createdtime'=>date("Y-m-d h:i:s"),

		);

		DB::table('designation')->insert($updatedata);

        Session::flash('sucmessage2', "Congratulations, Desgnation has been Save");

		return Redirect::back();
    }


	///////////// Update Section

	public function userregistration(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(

				'designationname'=>$savedata["designation"],
				'createdby'=>$savedata['userid'],
				'createdtime'=>date("Y-m-d h:i:s"),

		);

		DB::table('designation')->insert($updatedata);

        Session::flash('sucmessage2', "Congratulations, Desgnation has been Save");

		return Redirect::back();
    }


	///////////// Update Section

	public function updatedepartment(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(

				'name'=>$savedata["designation"],

		);

		DB::table('departmentdata')->where('id',$savedata["editid"])->update($updatedata);

        Session::flash('sucmessage2', "Congratulations, Department has been Updated");

		return redirect('/admin/department');
    }



	///////////// Update Section

	public function updateregion(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(

				'regionname'=>$savedata["regionname"],

		);

		DB::table('regiondata')->where('id',$savedata["editid"])->update($updatedata);

        Session::flash('sucmessage', "Congratulations, Region has been updated");

		return redirect('/admin/region');
    }

	///////////// Update Section

	public function updatecircle(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();
			$circlename ='';
		 if(isset($savedata["circlename"])) {
			$circlename = implode(',',$savedata["circlename"]);
		}

		  $updatedata = array(
				'regionparent'=>$savedata["parentregion"],
				'regionname'=>$circlename,

		);

		DB::table('regiondata')->where('id',$savedata["editid"])->update($updatedata);

        Session::flash('sucmessage2', "Congratulations, Circle has been updated");

		return redirect('/admin/region');
    }

    public function Empolyees()
    {
        return view('admin.master.empolyees');
    }

    public function Region()
    {
		$regiondata = DB::table('regiondata')->where('regioblavel',1)->orderBy('id', 'DESC')->get();

		$crciledata = DB::table('regiondata')->where('regioblavel',2)->orderBy('id', 'DESC')->get();

        return view('admin.master.region')->with('regiondata',$regiondata)->with('crciledata',$crciledata);
    }

	 public function editregion($id)
    {
		$regiondata = DB::table('regiondata')->where('id', $id)->first();

        return view('admin.master.edit_region')->with('regiondata',$regiondata);
    }




	 public function editdepartment($id)
    {
		$departmentdata = DB::table('departmentdata')->where('id', $id)->first();

        return view('admin.master.editdepartment')->with('departmentdata',$departmentdata);
    }


	///////////// Update Section

	public function updatedesignation(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();

		  $updatedata = array(
				'designationname'=>$savedata["designation"],

		);

		DB::table('designation')->where('id',$savedata["editid"])->update($updatedata);

        Session::flash('sucmessage2', "Congratulations, Circle has been updated");

		return redirect('/admin/department');
    }



	 public function editcircle($id)
    {
		$regiondata = DB::table('regiondata')->where('id', $id)->first();

        return view('admin.master.editcircle')->with('regiondata',$regiondata);
    }

	 public function editdesignation($id)
    {
		$regiondata = DB::table('designation')->where('id', $id)->first();

        return view('admin.master.editdesignation')->with('regiondata',$regiondata);
    }




    public function Product()
    {

		$producttype = DB::table('producttype')->where('prolevel',1)->orderBy('id', 'DESC')->get();

		$subproduct = DB::table('producttype')->where('prolevel',2)->orderBy('id', 'DESC')->get();


        return view('admin.master.product')->with('producttype',$producttype)->with('subproduct',$subproduct);
    }


	public function saveproduct(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(

				'producttype'=>$savedata["producttype"],
				'prolevel'=>1,
				'createdby'=>$savedata['userid'],
				'createdtime'=>date("Y-m-d h:i:s"),

		);

		DB::table('producttype')->insert($updatedata);

        Session::flash('sucmessage', "Congratulations, Desgnation has been Save");

		return Redirect::back();
    }

	 public function editproduct($id)
    {
		$producttype = DB::table('producttype')->where('id', $id)->first();

        return view('admin.master.editproduct')->with('producttype',$producttype);
    }


	public function updateproduct(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();

		  $updatedata = array(
				'producttype'=>$savedata["producttype"],

		);

		DB::table('producttype')->where('id', $savedata['editid'])->update($updatedata);

        Session::flash('sucmessage', "Congratulations, Product has been Updated");

		return redirect('/admin/product');
    }


	public function updatesubproduct(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();

		  $updatedata = array(
				'proparentid'=>$savedata["parentproduct"],
				'producttype'=>$savedata["subproducttype"],

		);

		DB::table('producttype')->where('id', $savedata['editid'])->update($updatedata);

        Session::flash('sucmessage2', "Congratulations, Sub Product has been Updated");

		return redirect('/admin/product');
    }





	public function savesubproduct(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(

				'producttype'=>$savedata["subproduct"],
				'proparentid'=>$savedata["parentproduct"],
				'prolevel'=>2,
				'createdby'=>$savedata['userid'],
				'createdtime'=>date("Y-m-d h:i:s"),

		);

		DB::table('producttype')->insert($updatedata);

        Session::flash('sucmessage2', "Congratulations, Sub Product has been Save");

		return Redirect::back();
    }



 public function editsubproduct($id)
    {
		$producttype = DB::table('producttype')->where('id', $id)->first();

        return view('admin.master.editsubproduct')->with('producttype',$producttype);
    }

    public function Source()
    {
		$sourcetype = DB::table('sourcetype')->orderBy('id', 'DESC')->get();

		$sourcevalue = DB::table('sourcevalue')->select('sourcevalue.*', 'sourcetype.sourcetype')->join('sourcetype', 'sourcetype.id', '=', 'sourcevalue.source_type_id')->orderBy('sourcevalue.id', 'DESC')->get();


        return view('admin.master.source')->with('sourcetype',$sourcetype)->with('sourcevalue',$sourcevalue);
    }


	public function savesourcetype(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(

				'sourcetype'=>$savedata["sourcetype"],
				'createdby'=>$savedata['userid'],
				'createdtime'=>date("Y-m-d h:i:s"),

		);

		DB::table('sourcetype')->insert($updatedata);

        Session::flash('sucmessage', "Congratulations, Desgnation has been Save");

		return Redirect::back();
    }


	public function savesourcevalue(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(
				'source_type_id' => $savedata['source_type_id'],
				'sourcevalue'=>$savedata["sourcevalue"],
				'createdby'=>$savedata['userid'],
				'createdtime'=>date("Y-m-d h:i:s"),

		);

		DB::table('sourcevalue')->insert($updatedata);

        Session::flash('sucmessage2', "Congratulations, Desgnation has been Save");

		return Redirect::back();
    }


		public function editsourcetype($id)
    {
		$sourcetype = DB::table('sourcetype')->where('id', $id)->first();

        return view('admin.master.editsourcetype')->with('sourcetype',$sourcetype);
    }

		public function updatesourcetype(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();

		  $updatedata = array(

				'sourcetype'=>$savedata["sourcetype"],


		);

		DB::table('sourcetype')->where('id', $savedata["editid"])->update($updatedata);

         Session::flash('sucmessage', "Congratulations, SOurce has been Updated");

		return redirect('/admin/source');
    }

	public function editsourcevalue($id)
    {
		$sourcevalue = DB::table('sourcevalue')->where('id', $id)->first();
        $sourcetype = DB::table('sourcetype')->orderBy('id', 'DESC')->get();
        return view('admin.master.sourcevalue')->with([
            'sourcevalue' => $sourcevalue,
            'sourcetype' => $sourcetype
        ]);
    }

		public function updatesourcevalue(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();

		  $updatedata = array(
            'source_type_id' => $savedata['source_type_id'],
			'sourcevalue'=>$savedata["sourcevalue"],


		);

		DB::table('sourcevalue')->where('id', $savedata["editid"])->update($updatedata);

         Session::flash('sucmessage2', "Congratulations, Source Value has been Updated");

		return redirect('/admin/source');
    }





    public function Accountind()
    {

		$accounttype = DB::table('accounttype')->orderBy('id', 'DESC')->get();

		$industrytype = DB::table('industrytype')->orderBy('id', 'DESC')->get();


        return view('admin.master.accountind')->with('accounttype',$accounttype)->with('industrytype',$industrytype);
    }


	public function saveaccount(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(

				'accounttype'=>$savedata["accounttype"],
				'createdby'=>$savedata['userid'],
				'createdtime'=>date("Y-m-d h:i:s"),

		);

		DB::table('accounttype')->insert($updatedata);

        Session::flash('sucmessage', "Congratulations, Account Type has been Save");

		return Redirect::back();
    }


	public function saveindustry(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();


		  $updatedata = array(

				'industrytype'=>$savedata["industrytype"],
				'createdby'=>$savedata['userid'],
				'createdtime'=>date("Y-m-d h:i:s"),

		);

		DB::table('industrytype')->insert($updatedata);

        Session::flash('sucmessage2', "Congratulations, Industry has been Save");

		return Redirect::back();
    }


	public function editaccount($id)
    {
		$accounttype = DB::table('accounttype')->where('id', $id)->first();

        return view('admin.master.editaccount')->with('accounttype',$accounttype);
    }


	public function updateaccount(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();

		  $updatedata = array(
				'accounttype'=>$savedata["accounttype"],

		);

		DB::table('accounttype')->where('id', $savedata["editid"])->update($updatedata);

        Session::flash('sucmessage', "Congratulations, Account Type has been Updated");

		return redirect('/admin/accountind');
    }

	public function editindustry($id)
    {
		$industrytype = DB::table('industrytype')->where('id', $id)->first();

        return view('admin.master.editindustrytype')->with('industrytype',$industrytype);
    }

	public function updateindustry(Request $request)
    {
		 date_default_timezone_set("Asia/Calcutta");
		 $savedata =  $request->all();

		  $updatedata = array(
				'industrytype'=>$savedata["industrname"],

		);

		DB::table('industrytype')->where('id', $savedata["editid"])->update($updatedata);

        Session::flash('sucmessage2', "Congratulations, Industry Type has been Updated");

		return redirect('/admin/accountind');
    }



}
?>
