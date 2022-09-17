<?php

namespace App\Http\Controllers\admin;

use App\LeadData;
use App\ActivityData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Quotation;
use DateTime;
use Illuminate\Support\Facades\Date;
use Session;
use App\Service\ReportService;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeadsExport;
use App\Jobs\LeadCreatedJob;
use App\Jobs\PreSalesJob;

class LeadController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addlead() {
        return view('admin.lead.addlead');
    }

    public function leadmanagement(Request $request) {
        // Get logged in user id
        $user_id = auth()->user()->id;
        // Get logged in user role
        $user_role = auth()->user()->role;
        // Get logged in user states and convert them into an array
        $user_state = explode(",", auth()->user()->stateid);
        // Get logged in user Zone and convert them into an array
        $user_zone = auth()->user()->zone;
        // Initialize own leads
        $own_leads = collect();
        // Initialize employee leads
        $employee_leads = collect();
        // Initialize sales person
        $sales_person = collect();
        // Initialize sales person
        $members = collect();
        // Initialize sales person
        $person = collect();
        // Get from date
        $from_date = $request->get('from-search');
        // Get to date
        $to_date = $request->get('to-search');

        // If logged in user is Admin then redirect to dashboard
        if ($user_role == 'Admin') {
            return redirect()->route('dashboard');
        } else {
            // If logged in user is Admin then get all leads
            if ($user_role == 'Country Head') {
                //$leads = DB::table('leads')->orderBy('id', 'DESC')->get();
                $leads = DB::table('leads')
                            ->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city',
                            'designation.designationname AS designation_name', 'producttype.producttype as product_type',
                            'subproducttype.producttype as subproduct_type','users.name as sales_person',
                            'sourcetype.sourcetype as source_type','sourcevalue.sourcevalue as source_value',
                            'states.name AS state','industrytype.industrytype AS industry_type')
                            ->leftJoin('departmentdata', 'departmentdata.id', '=', 'leads.department')
                            ->leftJoin('cities', 'cities.id', '=', 'leads.city_id')
                            ->leftJoin('states', 'states.id', '=', 'leads.state_id')
                            ->leftJoin('designation', 'designation.id', '=', 'leads.designation')
                            ->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')
                            ->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                            ->leftJoin('users', 'users.id', '=', 'leads.sales_person')
                            ->leftJoin('industrytype', 'industrytype.id', '=', 'leads.industry_type')
                            ->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')
                            ->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value');

                // Apply date filters
                if($from_date) {
                    $leads = $leads->where(function($query) use ($from_date, $to_date){
                                        $query->whereDate('lead_date', '>=', $from_date);
                                        $query->whereDate('lead_date', '<=', $to_date);
                                    });
                }
                // Apply lead status filter
                if($request->get('status-filter')) {
                    $status = $request->get('status-filter');
                    $leads = $leads->where(function($query) use ($status){
                        $query->where('lead_status', '=', $status);

                    });
                }
                // Apply product type filter
                if($request->get('product-filter')) {
                    $status = $request->get('product-filter');
                    $leads = $leads->where(function($query) use ($status){
                        $query->where('leads.product_type', '=', $status);

                    });
                }

                $leads = $leads->orderBy('leads.id', 'DESC')->get();

            } else {
                // Get leads of logged in user
                $own_leads = DB::table('leads')
                            ->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city',
                            'designation.designationname AS designation_name', 'producttype.producttype as product_type',
                            'subproducttype.producttype as subproduct_type','users.name as sales_person',
                            'sourcetype.sourcetype as source_type','sourcevalue.sourcevalue as source_value',
                            'states.name AS state','industrytype.industrytype AS industry_type')
                            ->leftJoin('departmentdata', 'departmentdata.id', '=', 'leads.department')
                            ->leftJoin('cities', 'cities.id', '=', 'leads.city_id')
                            ->leftJoin('states', 'states.id', '=', 'leads.state_id')
                            ->leftJoin('designation', 'designation.id', '=', 'leads.designation')
                            ->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')
                            ->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                            ->leftJoin('users', 'users.id', '=', 'leads.sales_person')
                            ->leftJoin('industrytype', 'industrytype.id', '=', 'leads.industry_type')
                            ->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')
                            ->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value')
                            ->where('leads.sales_person', '=', $user_id)
                            ->orderBy('leads.id', 'DESC');

                // Apply date filters
                if($from_date) {
                    $own_leads = $own_leads->where(function($query) use ($from_date, $to_date){
                                        $query->whereDate('lead_date', '>=', $from_date);
                                        $query->whereDate('lead_date', '<=', $to_date);
                                    });
                }
                // Apply lead status filter
                if($request->get('status-filter')) {
                    $status = $request->get('status-filter');
                    $own_leads = $own_leads->where(function($query) use ($status){
                        $query->where('lead_status', '=', $status);

                    });
                }
                // Apply product type filter
                if($request->get('product-filter')) {
                    $status = $request->get('product-filter');
                    $own_leads = $own_leads->where(function($query) use ($status){
                        $query->where('leads.product_type', '=', $status);

                    });
                }

                $leads = $own_leads = $own_leads->get();

                if ($user_role == 'Zonal Level Admin') {
                    // Get all members of the zone level admin's state
                    foreach ($user_state as $key=>$state) {
                        $person = DB::table('users')
                                ->whereRaw('FIND_IN_SET("'.$state.'",users.stateid)')
                                ->whereIn('users.role', array('Member', 'State Level Admin'))
                                ->orderBy('name', 'ASC')
                                ->get();
                        $members = $members->merge($person);
                        $members->all();
                    }
                    // Get only unique sales person
                    $members = $members->unique('id');
                    // Get all user ids from member collection
                    $userids = array();

                    foreach ($members as $member) {
                        $userids[] = $member->id;
                    }

                    $employee_leads = DB::table('leads')
                        ->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city',
                        'designation.designationname AS designation_name', 'producttype.producttype as product_type',
                        'subproducttype.producttype as subproduct_type','saleperson.name as sales_person',
                        'sourcetype.sourcetype as source_type','sourcevalue.sourcevalue as source_value',
                        'states.name AS state','industrytype.industrytype AS industry_type')
                        ->leftJoin('departmentdata', 'departmentdata.id', '=', 'leads.department')
                        ->leftJoin('cities', 'cities.id', '=', 'leads.city_id')
                        ->leftJoin('states', 'states.id', '=', 'leads.state_id')
                        ->leftJoin('designation', 'designation.id', '=', 'leads.designation')
                        ->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')
                        ->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                        ->leftJoin('users AS saleperson', 'saleperson.id', '=', 'leads.sales_person')
                        ->leftJoin('industrytype', 'industrytype.id', '=', 'leads.industry_type')
                        ->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')
                        ->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value')
                        ->whereIn('leads.sales_person', $userids);

                    // Apply date filters
                    if($from_date) {
                        $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date){
                                            $query->whereDate('lead_date', '>=', $from_date);
                                            $query->whereDate('lead_date', '<=', $to_date);
                                        });
                    }
                    // Apply lead status filter
                    if($request->get('status-filter')) {
                        $status = $request->get('status-filter');
                        $employee_leads = $employee_leads->where(function($query) use ($status){
                            $query->where('lead_status', '=', $status);

                        });
                    }
                    // Apply product type filter
                    if($request->get('product-filter')) {
                        $status = $request->get('product-filter');
                        $employee_leads = $employee_leads->where(function($query) use ($status){
                            $query->where('leads.product_type', '=', $status);

                        });
                    }

                    $employee_leads = $employee_leads->orderBy('leads.id', 'DESC')->get();
                    $own_leads = $own_leads->merge($employee_leads);
                    $own_leads->all();

                    // Get only unique leads
                    $leads = $own_leads->unique('id');

                } elseif ($user_role == 'State Level Admin') {
                    // Get all members of the state level admin's state
                    foreach ($user_state as $key=>$state) {
                        $person = DB::table('users')
                                ->whereRaw('FIND_IN_SET("'.$state.'",users.stateid)')
                                ->whereIn('users.role', array('Member', 'State Level Admin'))
                                ->orderBy('name', 'ASC')
                                ->get();
                        $members = $members->merge($person);
                        $members->all();
                    }
                    // Get only unique sales person
                    $members = $members->unique('id');
                    // Get all user ids from member collection
                    $userids = array();

                    foreach ($members as $member) {
                        $userids[] = $member->id;
                    }

                    $employee_leads = DB::table('leads')
                            ->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city',
                            'designation.designationname AS designation_name', 'producttype.producttype as product_type',
                            'subproducttype.producttype as subproduct_type','saleperson.name as sales_person',
                            'sourcetype.sourcetype as source_type','sourcevalue.sourcevalue as source_value',
                            'states.name AS state','industrytype.industrytype AS industry_type')
                            ->leftJoin('departmentdata', 'departmentdata.id', '=', 'leads.department')
                            ->leftJoin('cities', 'cities.id', '=', 'leads.city_id')
                            ->leftJoin('states', 'states.id', '=', 'leads.state_id')
                            ->leftJoin('designation', 'designation.id', '=', 'leads.designation')
                            ->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')
                            ->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                            ->leftJoin('users AS saleperson', 'saleperson.id', '=', 'leads.sales_person')
                            ->leftJoin('industrytype', 'industrytype.id', '=', 'leads.industry_type')
                            ->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')
                            ->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value')
                            ->whereIn('leads.sales_person', $userids);

                    // Apply date filters
                    if($from_date) {
                        $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date){
                                            $query->where('lead_date', '>=', $from_date);
                                            $query->where('lead_date', '<=', $to_date);
                                        });
                    }
                    // Apply lead status filter
                    if($request->get('status-filter')) {
                        $status = $request->get('status-filter');
                        $employee_leads = $employee_leads->where(function($query) use ($status){
                            $query->where('lead_status', '=', $status);

                        });
                    }
                    // Apply product type filter
                    if($request->get('product-filter')) {
                        $status = $request->get('product-filter');
                        $employee_leads = $employee_leads->where(function($query) use ($status){
                            $query->where('leads.product_type', '=', $status);

                        });
                    }

                    $employee_leads = $employee_leads->orderBy('leads.id', 'DESC')->get();
                    // Merge both resultsets
                    $own_leads = $own_leads->merge($employee_leads);
                    $own_leads->all();
                    // Get only unique leads
                    $leads = $own_leads->unique('id');
                }
            }

            // Get account types
            $account_types = DB::table('accounttype')->orderBy('accounttype', 'ASC')->get();
            // Get industry types
            $industry_types = DB::table('industrytype')->orderBy('industrytype', 'ASC')->get();
            // Get designations
            $designations = DB::table('designation')->orderBy('designationname', 'ASC')->get();
            // Get departments
            $departments = DB::table('departmentdata')->orderBy('name', 'ASC')->get();

            // Get sales person
            if($user_role != "Member") {
                if ($user_role == 'Country Head') {
                    $sales_person = DB::table('users')->whereIn('role', array('Zonal Level Admin', 'State Level Admin', 'Member'))->orderBy('name', 'ASC')->get();
                } elseif ($user_role == 'Zonal Level Admin') {
                    foreach ($user_state as $key=>$state) {
                        $person = DB::table('users')
                                ->whereRaw('FIND_IN_SET("'.$state.'",users.stateid)')
                                ->orderBy('name', 'ASC')
                                ->get();
                        $sales_person = $sales_person->merge($person);
                        $sales_person->all();
                    }
                    // Get only unique sales person
                    $sales_person = $sales_person->unique('id');
                    $sales_person = $sales_person->sortBy('username');                    
                } elseif ($user_role == 'State Level Admin') {
                    foreach ($user_state as $key=>$state) {
                        $person = DB::table('users')
                                ->whereRaw('FIND_IN_SET("'.$state.'",users.stateid)')
                                ->where('users.role', '=', 'Member')
                                ->orWhere('users.id', '=', $user_id)
                                ->orderBy('name', 'ASC')
                                ->get();
                        $sales_person = $sales_person->merge($person);
                        $sales_person->all();
                    }
                    // Get only unique sales person
                    $sales_person = $sales_person->unique('id');
                    $sales_person = $sales_person->sortBy('username');                    
                }
            } else {
                $sales_person = DB::table('users')->where('id', auth()->user()->id)->orderBy('name', 'ASC')->get();
            }

            // Get source types
            $source_types = DB::table('sourcetype')->orderBy('sourcetype', 'ASC')->get();
            // Get source values
            $source_values = DB::table('sourcevalue')->orderBy('sourcevalue', 'ASC')->get();
            // Get product types
            $product_types = DB::table('producttype')->where('prolevel', 1)->orderBy('producttype', 'ASC')->get();
            // Get states
            $states = $sataedata = DB::table('states')->where('country_id', 101)->orderBy('name', 'ASC')->get();

            if ($request->action == '' || $request->action == 'Submit') {            
                return view('admin.lead.leadmanagement')->with(array(
                'leads' => $leads,'userid' => $user_id,'account_types' => $account_types,'industry_types' => $industry_types,
                'designations' => $designations,'departments' => $departments,'sales_person' => $sales_person,
                'source_types' => $source_types,'source_values' => $source_values,'product_types' => $product_types,
                'states' => $states));
            } else {
                return Excel::download(new LeadsExport($leads), 'leads.xlsx');
            }
        }
    }

    public function editlead($id) {
        // Get logged in user id
        $user_id = auth()->user()->id;
        // Get logged in user role
        $user_role = auth()->user()->role;
        // Get logged in user states and convert them into an array
        $user_state = explode(",", auth()->user()->stateid);
        // Get logged in user Zone and convert them into an array
        $user_zone = auth()->user()->zone;
        // Initialize sales person
        $sales_person = collect();
        // Initialize sales person
        $person = collect();

        // Get leads
        $leadeditata = DB::table('leads')->where('id', '=', $id)->orderBy('id', 'desc')->first();

        // Get sales person
        if($user_role != "Member") {
            if ($user_role == 'Country Head') {
                $sales_person = DB::table('users')->whereIn('role', array('Zonal Level Admin', 'State Level Admin', 'Member'))->orderBy('name', 'ASC')->get();
            } elseif ($user_role == 'Zonal Level Admin') {
                foreach ($user_state as $key=>$state) {
                    $person = DB::table('users')
                            ->whereRaw('FIND_IN_SET("'.$state.'",users.stateid)')
                            ->orderBy('name', 'ASC')
                            ->get();
                    $sales_person = $sales_person->merge($person);
                    $sales_person->all();
                }
                // Get only unique sales person
                $sales_person = $sales_person->unique('id');
            } elseif ($user_role == 'State Level Admin') {
                foreach ($user_state as $key=>$state) {
                    $person = DB::table('users')
                            ->whereRaw('FIND_IN_SET("'.$state.'",users.stateid)')
                            ->where('users.role', '=', 'Member')
                            ->orWhere('users.id', '=', $user_id)
                            ->orderBy('name', 'ASC')
                            ->get();
                    $sales_person = $sales_person->merge($person);
                    $sales_person->all();
                }
                // Get only unique sales person
                $sales_person = $sales_person->unique('id');
            }
        } else {
            $sales_person = DB::table('users')->where('id', auth()->user()->id)->orderBy('name', 'ASC')->get();
        }

        // Get account types
        $account_types = DB::table('accounttype')->orderBy('accounttype', 'ASC')->get();
        // Get industry types
        $industry_types = DB::table('industrytype')->orderBy('industrytype', 'ASC')->get();
        // Get designations
        $designations = DB::table('designation')->orderBy('designationname', 'ASC')->get();
        // Get departments
        $departments = DB::table('departmentdata')->orderBy('name', 'ASC')->get();
        // Get source types
        $source_types = DB::table('sourcetype')->orderBy('sourcetype', 'ASC')->get();
        // Get source values
        $source_values = DB::table('sourcevalue')->where('source_type_id', '=', $leadeditata->source_type)->orderBy('sourcevalue', 'ASC')->get();
        // Get product types
        $product_types = DB::table('producttype')->where('prolevel', 1)->orderBy('producttype', 'ASC')->get();
        // Get sub product types
        $sub_products = DB::table('producttype')->where('prolevel', 2)->where('proparentid', $leadeditata->product_type)->orderBy('producttype', 'ASC')->get();
        // Get states
        $states = DB::table('states')->where('country_id', 101)->orderBy('name', 'ASC')->get();
        // Get cities
        $cities = DB::table('cities')->where('state_id', $leadeditata->state_id)->orderBy('name', 'ASC')->get();

        return view('admin.lead.editlead')->with(array('leadeditata' => $leadeditata, 'sales_person' => $sales_person,
                    'account_types' => $account_types,'industry_types' => $industry_types, 'designations' => $designations,
                    'departments' => $departments,'source_types' => $source_types,'source_values' => $source_values,
                    'product_types' => $product_types, 'sub_products' => $sub_products, 'states' => $states, 'cities' => $cities));
    }

    /**
     * 	Save Data Category
     */
    public function savelead(Request $request) {
        $savedata = $request->all();
        $TimeStamp = DateTime::createFromFormat('d/m/Y H:i a', $savedata['lead_date'])->format('Y-m-d H:i:s');

        // Start database transactions
        DB::beginTransaction();

        try {
            $insertdata = array(
                'organisation' => ucwords(strtolower($savedata['organisation'])),
                'salutation' => $savedata['salutation'],
                'first_name' => ucwords(strtolower($savedata['first_name'])),
                'last_name' => ucwords(strtolower($savedata['last_name'])),
                'contact_number' => $savedata['mobile'],
                'email_id' => isset($savedata['youremail']) ? $savedata['youremail'] : NULL,
                'competitor_name' => $savedata['competitor'],
                'account_type' => $savedata['account_type'],
                'industry_type' => $savedata['industrytypoe'],
                'designation' => $savedata['designatoin'],
                'department' => $savedata['department'],
                'sales_person' => $savedata['saleperson'],
                'lead_details' => $savedata['lead_details'],
                'source_type' => $savedata['source_type'],
                'source_value' => $savedata['source_value'],
                'product_type' => $savedata['product_type'],
                'lead_status' => $savedata['lead_status'],
                'sub_product' => isset($savedata['sub_product']) ? $savedata['sub_product'] : null,
                'payment_type' => $savedata['payment_type'],
                'channel_partner' => $savedata['channel_partner'],
                'channel_competitor' => ucwords(strtolower($savedata['channel_competitor'])),
                'lead_type' => $savedata['lead_type'],
                'address' => $savedata['address'],
                'state_id' => $savedata['state_id'],
                'city_id' => $savedata['city_id'],
                'pincode' => isset($savedata['pincode']) ? $savedata['pincode'] : NULL,
                'lead_date' => $TimeStamp,
                'created_by' => $savedata['userid'],
                'created_time' => date("Y-m-d H:i:s"),
            );

            // Save data
            $ID = DB::table('leads')->insertGetId($insertdata);
            // Commit database transaction
            DB::commit();
            // Set success message now
            Session::flash('sucmessage', "Lead has been created successfully!");

            // Send Email
            dispatch(new LeadCreatedJob(LeadData::where('id', $ID)->first()));

            // Get Lead Details
            $lead = DB::table('leads')
                            ->select('leads.*', 'users.name as salesperson', 'producttype.producttype as product_type',
                            'subproducttype.producttype as subproduct_type', 'cities.name AS city', 'states.name AS state',
                            'users.mobileno', 'zonal.mobileno as zonal_mobileno')
                            ->join('cities', 'cities.id', '=', 'leads.city_id')
                            ->join('states', 'states.id', '=', 'leads.state_id')                    
                            ->join('producttype', 'producttype.id', '=', 'leads.product_type')
                            ->join('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                            ->join('users', 'users.id', '=', 'leads.sales_person')
                            ->join('users as zonal', function($join) {
                                $join->on('zonal.zone', '=', 'users.zone');
                                $join->where('zonal.role','=', 'Zonal Level Admin');
                            })
                            ->where('leads.id', '=', $ID)
                            ->orderBy('leads.id', 'desc')
                            ->first();

            // Check lead type
            if ($lead->lead_type == 1) {
                $lead_type = "New";
            } elseif ($lead->lead_type == 2) {
                $lead_type = "Existing";
            } else {
                $lead_type = "";
            }

            // Check lead status
            if ($lead->lead_status == 1) {
                $lead_status = "New";
            } elseif ($lead->lead_status == 2) {
                $lead_status = "Existing";
            } elseif ($lead->lead_status == 3) {
                $lead_status = "Existing";
            } else {
                $lead_status = "";
            }

            // Send SMS
            $sms_content = "Lead Action, Client:".$lead->first_name." ".$lead->last_name. ",Company:".$lead->organisation.",Mobile:".$lead->contact_number.",City/State:".$lead->city."/".$lead->state. ",Product:".$lead->product_type.",Date:". date('d/m/Y', strtotime($lead->created_time)) .",Time:". date('h:i a', strtotime($lead->created_time)) .",KAM:".$lead->salesperson. ",Lead Attended On:". date('d/m/Y', strtotime($lead->lead_date)) .",KAM Remarks:".$lead->lead_details.",Closure Status:".$lead_status.",Link:http://crm.smartping.in/login/";

            // Send SMS to Sales Person
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://pgapi.vispl.in/fe/api/v1/send?");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "username=smartrpg.trans&password=1zPcv&unicode=true&from=VSPCRM&to=".$lead->mobileno."&dltContentId=1307163766260265693&text=".$sms_content);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close ($ch);

            // Send SMS to Regional Head
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://pgapi.vispl.in/fe/api/v1/send?");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "username=smartrpg.trans&password=1zPcv&unicode=true&from=VSPCRM&to=".$lead->zonal_mobileno."&dltContentId=1307163766260265693&text=".$sms_content);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close ($ch);

            // Send SMS to Priyanka
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://pgapi.vispl.in/fe/api/v1/send?");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "username=smartrpg.trans&password=1zPcv&unicode=true&from=VSPCRM&to=9318499948&dltContentId=1307163766260265693&text=".$sms_content);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close ($ch);

        } catch (\Exception $e) {
            DB::rollback(); // something went wrong
            // Set success message now
            Session::flash('failure', "Lead creation failed. Please contact administrator!");
        }

        return Redirect::back();
    }

    public function updatelead(Request $request) {

        $savedata = $request->all();

        // Check Duplicate with combination of organisation and product name
        $count = DB::table('leads')
                ->where('organisation', ucwords(strtolower($savedata['organisation'])))
                ->where('product_type', '=', $savedata['product_type'])
                ->where('id', '!=', $savedata['editid'])
                ->count();

        if ($count > 0) {
            Session::flash('failure', "Lead already exists with same organisation and product name!");
            return Redirect::back();
        } else {
            $TimeStamp = DateTime::createFromFormat('d/m/Y H:i a', $savedata['lead_date'])->format('Y-m-d H:i:s');

            // Start database transactions
            DB::beginTransaction();

            try {
                $insertdata = array(
                    'organisation' => ucwords(strtolower($savedata['organisation'])),
                    'salutation' => $savedata['salutation'],
                    'first_name' => ucwords(strtolower($savedata['first_name'])),
                    'last_name' => ucwords(strtolower($savedata['last_name'])),
                    'contact_number' => $savedata['mobile'],
                    'email_id' => isset($savedata['youremail']) ? $savedata['youremail'] : NULL,
                    'competitor_name' => $savedata['competitor'],
                    'account_type' => $savedata['account_type'],
                    'industry_type' => $savedata['industrytypoe'],
                    'designation' => $savedata['designatoin'],
                    'department' => $savedata['department'],
                    'sales_person' => $savedata['saleperson'],
                    'lead_details' => $savedata['lead_details'],
                    'source_type' => $savedata['source_type'],
                    'source_value' => $savedata['source_value'],
                    'product_type' => $savedata['product_type'],
                    'lead_status' => $savedata['lead_status'],
                    'sub_product' => $savedata['sub_product'],
                    'payment_type' => $savedata['payment_type'],
                    'channel_partner' => $savedata['channel_partner'],
                    'channel_competitor' => ucwords(strtolower($savedata['channel_competitor'])),
                    'lead_type' => $savedata['lead_type'],
                    'address' => $savedata['address'],
                    'state_id' => $savedata['state_id'],
                    'city_id' => $savedata['city_id'],
                    'pincode' => isset($savedata['pincode']) ? $savedata['pincode'] : NULL,
                    'lead_date' => $TimeStamp
                );
                DB::table('leads')->where('id', $savedata['editid'])->update($insertdata);

                // Capture activity if status is changed
                if ($savedata['oldstatus'] != $savedata['lead_status']) {
                    if ($savedata['lead_status'] == 1) {
                        $lead_status = 'Hot';
                    } elseif ($savedata['lead_status'] == 2) {
                        $lead_status = 'Warm';
                    } elseif ($savedata['lead_status'] == 3) {
                        $lead_status = 'Cold';
                    }

                    $activitydata['leadid'] = $savedata['editid'];
                    $activitydata['createdby'] = auth()->user()->id;
                    $activitydata['createdtime'] = date("Y-m-d H:i:s");
                    $activitydata['activity_type_id'] = 4;
                    $activitydata['sale_person_id'] = $savedata['saleperson'];
                    $activitydata['link_to'] = 'Existing';
                    $activitydata['lead_status'] = $lead_status;

                    // Add data in activity data
                    ActivityData::create($activitydata);
                }
                // Commit database transaction
                DB::commit();

                Session::flash('sucmessage', "Lead has been updated successfully!");

            } catch (\Exception $e) {            
                DB::rollback(); // something went wrong
                // Set success message now
                Session::flash('failure', "Lead updation failed. Please contact administrator!");
            }

            return Redirect('admin/leadmanagement');
        }
    }

    //////////
    // Delete Data
    ////////////

    public function leadtatus($id) {
        $stauts = $_REQUEST['stauts'];
        $lead_status = '';

        if ($_REQUEST['stauts'] == 1) {
            $lead_status = 'Hot';
        } elseif ($_REQUEST['stauts'] == 2) {
            $lead_status = 'Warm';
        } elseif ($_REQUEST['stauts'] == 3) {
            $lead_status = 'Cold';
        }
        $insertdata = array(
            'lead_status' => $stauts,
            'created_time' => date("Y-m-d H:i:s"),
        );

        DB::table('leads')->where('id', $id)->update($insertdata);

        // Get leads
        $leadeditata = DB::table('leads')->where('id', '=', $id)->orderBy('id', 'desc')->first();

        $ActivityData = new ActivityData;
        $ActivityData->leadid = $id;
        $ActivityData->lead_status = $lead_status;
        $ActivityData->activity_type_id = 4;
        $ActivityData->sale_person_id = $leadeditata->sales_person;
        $ActivityData->activity_details = 'Lead Status Changed to '.$lead_status;
        $ActivityData->link_to = 'Existing';
        $ActivityData->createdby = auth()->user()->id;
        $ActivityData->createdtime = date("Y-m-d H:i:s");
        $ActivityData->from_date = date("Y-m-d H:i:s");
        $ActivityData->save();
        Session::flash('sucmessage', "Successful, Lead Status has been Changed");

        return Redirect::back();
    }

    /*     * ************* */

    /// Video Section
    /*     * ******************* */


    public function addvideo() {
        return view('admin.page.addvideo');
    }

    public function managevideo() {
        $posts = DB::table('Youtubevideos')->orderBy('id', 'desc')->paginate(100);
        return view('admin.page.managevideo')->with('pages', $posts);
    }

    public function editvideo($id) {
        $pincode = DB::table('Youtubevideos')->where('id', '=', $id)->get();
        return view('admin.page.editvideo')->with('pagecontent', $pincode);
    }

    /**
     * 	Save Data Category
     */
    public function savevideo(Request $request) {

        $savedata = $request->all();

        $insertdata = array(
            'videoname' => $savedata["pagetitle"],
            'videourl' => $savedata["videolink"],
            'created' => date("Y-m-d H:i:s"),
        );
        DB::table('Youtubevideos')->insert($insertdata);

        Session::flash('sucmessage', "Successful, New Video has been added!");

        return Redirect::back();
    }

    ///////////////
    // Update Section Of Pages
    /////////////////

    public function updatevideo(Request $request) {

        $savedata = $request->all();


        $updatedata = array(
            'videoname' => $savedata["pagetitle"],
            'videourl' => $savedata["videolink"],
        );


        DB::table('Youtubevideos')->where('id', $savedata['editpageid'])->update($updatedata);

        Session::flash('sucmessage', "Successful, Video has been updated!");

        return Redirect::back();
    }

    //////////
    // Delete Data
    ////////////

    public function daletevideo($id) {
        DB::table('Youtubevideos')->where('id', $id)->delete();

        Session::flash('sucmessage', "Successful, Video has been Deleted");

        return Redirect::back();
    }

    //////////
    // Email Manangement Data
    ////////////

    public function emailmenagement() {


        return view('admin.page.emailmanagement');
    }

    ///////////////
    // Update Email Setting
    /////////////////

    public function emailsending(Request $request) {

        $savedata = $request->all();


        $users = DB::table('users')->where('usertype', '=', 4)->get();

        foreach ($users as $sendmsgar) {

            $mobile = $sendmsgar->mobileno;

            $number = urlencode("$mobile");

            $message = $savedata['emailcontent'];

            $message = rawurlencode("$message");



            $ch = curl_init('http://msg.smscluster.com/rest/services/sendSMS/sendGroupSms?AUTH_KEY=1cfd18e7aa8d8aeb92866479c1e736b6&message=' . $message . '&senderId=WDMSKS&routeId=1&mobileNos=' . $number . '&smsContentType=english');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            curl_close($ch);
        }

        Session::flash('sucmessage', "Successful, SMS has been Sent..");

        return Redirect::back();
    }

    public function duplicatelead($source) {
        // Get logged in user id
        $user_id = auth()->user()->id;
        // Get logged in user role
        $user_role = auth()->user()->role;
        // Get logged in user states and convert them into an array
        $user_state = explode(",", auth()->user()->stateid);
        // Get logged in user Zone and convert them into an array
        $user_zone = auth()->user()->zone;
        // Initialize sales person
        $sales_person = collect();
        // Initialize sales person
        $person = collect();

        // Get leads
        $leadeditata = DB::table('leads')->where('id', '=', $source)->orderBy('id', 'desc')->first();

        // Get sales person
        if($user_role != "Member") {
            if ($user_role == 'Country Head') {
                $sales_person = DB::table('users')->whereIn('role', array('Zonal Level Admin', 'State Level Admin', 'Member'))->orderBy('name', 'ASC')->get();
            } elseif ($user_role == 'Zonal Level Admin') {
                foreach ($user_state as $key=>$state) {
                    $person = DB::table('users')
                            ->whereRaw('FIND_IN_SET("'.$state.'",users.stateid)')
                            ->orderBy('name', 'ASC')
                            ->get();
                    $sales_person = $sales_person->merge($person);
                    $sales_person->all();
                }
                // Get only unique sales person
                $sales_person = $sales_person->unique('id');
            } elseif ($user_role == 'State Level Admin') {
                foreach ($user_state as $key=>$state) {
                    $person = DB::table('users')
                            ->whereRaw('FIND_IN_SET("'.$state.'",users.stateid)')
                            ->where('users.role', '=', 'Member')
                            ->orWhere('users.id', '=', $user_id)
                            ->orderBy('name', 'ASC')
                            ->get();
                    $sales_person = $sales_person->merge($person);
                    $sales_person->all();
                }
                // Get only unique sales person
                $sales_person = $sales_person->unique('id');
            }
        } else {
            $sales_person = DB::table('users')->where('id', auth()->user()->id)->orderBy('name', 'ASC')->get();
        }

        // Get account types
        $account_types = DB::table('accounttype')->orderBy('accounttype', 'ASC')->get();
        // Get industry types
        $industry_types = DB::table('industrytype')->orderBy('industrytype', 'ASC')->get();
        // Get designations
        $designations = DB::table('designation')->orderBy('designationname', 'ASC')->get();
        // Get departments
        $departments = DB::table('departmentdata')->orderBy('name', 'ASC')->get();
        // Get source types
        $source_types = DB::table('sourcetype')->orderBy('sourcetype', 'ASC')->get();
        // Get source values
        $source_values = DB::table('sourcevalue')->where('source_type_id', '=', $leadeditata->source_type)->orderBy('sourcevalue', 'ASC')->get();
        // Get product types
        $product_types = DB::table('producttype')->where('prolevel', 1)->orderBy('producttype', 'ASC')->get();
        // Get sub product types
        $sub_products = DB::table('producttype')->where('prolevel', 2)->where('proparentid', $leadeditata->product_type)->orderBy('producttype', 'ASC')->get();
        // Get states
        $states = DB::table('states')->where('country_id', 101)->orderBy('name', 'ASC')->get();
        // Get cities
        $cities = DB::table('cities')->where('state_id', $leadeditata->state_id)->orderBy('name', 'ASC')->get();

        return view('admin.lead.duplicatelead')->with(array('leadeditata' => $leadeditata, 'sales_person' => $sales_person,
                    'account_types' => $account_types,'industry_types' => $industry_types, 'designations' => $designations,
                    'departments' => $departments,'source_types' => $source_types,'source_values' => $source_values,
                    'product_types' => $product_types, 'sub_products' => $sub_products, 'states' => $states, 'cities' => $cities));
    }

    public function updateduplicate(Request $request) {
        $savedata = $request->all();
        $TimeStamp = DateTime::createFromFormat('d/m/Y H:i a', $savedata['lead_date'])->format('Y-m-d H:i:s');

        // Check Duplicate with combination of organisation and product name
        $count = DB::table('leads')->where('organisation', ucwords(strtolower($savedata['organisation'])))->where('product_type', '=', $savedata['product_type'])->count();

        if ($count > 0) {
            Session::flash('failure', "Lead already exists with same organisation and product name");
            return Redirect::back();
        } else {
            // Start database transactions
            DB::beginTransaction();

            try {
                $insertdata = array(
                    'organisation' => ucwords(strtolower($savedata['organisation'])),
                    'salutation' => $savedata['salutation'],
                    'first_name' => ucwords(strtolower($savedata['first_name'])),
                    'last_name' => ucwords(strtolower($savedata['last_name'])),
                    'contact_number' => $savedata['mobile'],
                    'email_id' => isset($savedata['youremail']) ? $savedata['youremail'] : NULL,
                    'competitor_name' => $savedata['competitor'],
                    'account_type' => $savedata['account_type'],
                    'industry_type' => $savedata['industrytypoe'],
                    'designation' => $savedata['designatoin'],
                    'department' => $savedata['department'],
                    'sales_person' => $savedata['saleperson'],
                    'lead_details' => $savedata['lead_details'],
                    'source_type' => $savedata['source_type'],
                    'source_value' => $savedata['source_value'],
                    'product_type' => $savedata['product_type'],
                    'lead_status' => $savedata['lead_status'],
                    'sub_product' => $savedata['sub_product'],
                    'payment_type' => $savedata['payment_type'],
                    'channel_partner' => $savedata['channel_partner'],
                    'channel_competitor' => ucwords(strtolower($savedata['channel_competitor'])),
                    'lead_type' => $savedata['lead_type'],
                    'address' => $savedata['address'],
                    'state_id' => $savedata['state_id'],
                    'city_id' => $savedata['city_id'],
                    'pincode' => isset($savedata['pincode']) ? $savedata['pincode'] : NULL,
                    'lead_date' => $TimeStamp,
                    'created_by' => $savedata['userid'],
                    'created_time' => date("Y-m-d H:i:s"),
                );

                // Save data
                DB::table('leads')->insert($insertdata);
                // Commit database transaction
                DB::commit();
                // Set success message now
                Session::flash('sucmessage', "Duplicate lead has been created successfully!");
                return Redirect('admin/leadmanagement');

            } catch (\Exception $e) {
                DB::rollback(); // something went wrong
                // Set success message now
                Session::flash('failure', "Lead creation failed. Please contact administrator!");
                return Redirect::back();
            }
        }

    }

    public function sendemail(Request $request) {
        dispatch(new PreSalesJob(LeadData::where('id', 7)->first()));
    }
}
