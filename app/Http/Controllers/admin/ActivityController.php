<?php

namespace App\Http\Controllers\admin;

use App\ActivityData;
use App\Http\Controllers\Controller;
use App\Jobs\LeadCreatedJob;
use App\Jobs\ManageActivityJob;
use App\Jobs\PreSalesJob;
use App\LeadData;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Quotation;
use DateTime;
use Illuminate\Support\Facades\Date;
use Session;


class ActivityController extends Controller {

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
    public function addactivity() {
        return view('admin.activity.addactivity');
    }

    public function activitymanagement(Request $request) {
        // Get logged in user id
        $user_id = auth()->user()->id;
        // Get logged in user role
        $user_role = auth()->user()->role;
        // Get logged in user states and convert them into an array
        $user_state = explode(",", auth()->user()->stateid);
        // Get logged in user Zone and convert them into an array
        $user_zone = auth()->user()->zone;
        // Initialize sales person
        $members = collect();
        // Initialize sales person
        $sales_person = collect();
        // Initialize employee leads
        $employee_leads = collect();
        // Existing leads
        $existing_leads = collect();
        // Existing leads
        $temp_leads1 = collect();
        $temp_leads2 = collect();
        $temp_leads3 = collect();
        // Get from date
        $from_date = $request->get('from-search');
        // Get to date
        $to_date = $request->get('to-search');
        // Get filtered managers
        $filtered_managers = $request->get('managers');
        // Get filtered managers
        $activity_filter = $request->get('activity-filter');
        // Get filtered managers
        $lead_filter = $request->get('lead-filter');
        // Initialize $condition
        $condition = "";

        // If logged in user is Admin then redirect to dashboard
        if ($user_role == 'Admin') {
            return redirect()->route('dashboard');
        } else {
            // If logged in user is Admin then get all leads
            if ($user_role == 'Country Head') {
                //$leads = DB::table('leads')->orderBy('id', 'DESC')->get();
                $leads = DB::table('leads')
                        ->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city', 'designation.designationname AS designation_name', 'producttype.producttype as product_type', 'subproducttype.producttype as subproduct_type', 'users.name as salesperson', 'sourcetype.sourcetype as source_type', 'sourcevalue.sourcevalue as source_value', 'states.name AS state', 'industrytype.industrytype AS industry_type', 'activities.followup_date as latest_date', 'activitytype.activity_name', 'activities.activity_type_id', 'activities.from_date')
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
                        ->groupBy('leads.id', 'activities.leadid');

                // Apply date filter
                if($from_date) {
                    $leads = $leads->where(function($query) use ($from_date, $to_date){
                                        $query->whereDate('activities.from_date', '>=', $from_date);
                                        $query->whereDate('activities.from_date', '<=', $to_date);
                                    });
                }

                // Apply activity filter
                if($activity_filter) {
                    $leads = $leads->where('activities.activity_type_id', '=', $activity_filter);
                }

                // Apply lead filter
                if($lead_filter != '') {
                    $leads = $leads->where('leads.is_opportunity', '=', $lead_filter);
                }

                // Apply user filter
                if ($filtered_managers) {
                    $leads->whereIn('leads.sales_person', $filtered_managers);
                }

                if (!$from_date) {
                    $leads = $leads->leftJoin('activities', function($join) {
                            $join->on('activities.leadid', '=', 'leads.id');
                            $join->where('activities.followup_date','=', date('Y-m-d'));
                        });
                    $leads = $leads->leftJoin('activitytype', 'activitytype.id', '=', 'activities.activity_type_id');
                    $leads->orderByRaw("CASE "
                            . "WHEN activities.followup_date IS NULL THEN 2 "
                            . "WHEN activities.followup_date >= '".date('Y-m-d')."' THEN 1 "
                            . "END ASC");
                } else {
                    $leads = $leads->leftJoin('activities', function($join) {
                            $join->on('activities.leadid', '=', 'leads.id');
                            $join->where('activities.followup_date','>=', date('Y-m-d'));
                        });
                    $leads = $leads->leftJoin('activitytype', 'activitytype.id', '=', 'activities.activity_type_id');
                    $leads->orderBy('activities.from_date', 'asc');
                    $leads->orderBy('activities.followup_date', 'asc');
                }
                //echo $leads->toSQL()."<BR><BR>";
                $leads = $leads->get();

                // Get Existing Leads for Country Head
                $existing_leads = DB::table('leads')
                                ->select('leads.*','producttype.producttype','subproducttype.producttype as subproduct')
                                ->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')
                                ->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                                ->orderBy('leads.id', 'desc')
                                ->get();
            } else {
                // Get leads of logged in user
                $own_leads = DB::table('leads')->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city', 'designation.designationname AS designation_name', 'producttype.producttype as product_type', 'subproducttype.producttype as subproduct_type', 'users.name as salesperson', 'sourcetype.sourcetype as source_type', 'sourcevalue.sourcevalue as source_value', 'states.name AS state', 'industrytype.industrytype AS industry_type', 'activities.followup_date as latest_date', 'activitytype.activity_name', 'activities.activity_type_id', 'activities.from_date')
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
                                ->groupBy('leads.id', 'activities.leadid');

                // Apply date filter
                if($from_date) {
                    $own_leads = $own_leads->where(function($query) use ($from_date, $to_date){
                                        $query->whereDate('activities.from_date', '>=', $from_date);
                                        $query->whereDate('activities.from_date', '<=', $to_date);
                                    });
                }

                // Apply activity filter
                if($activity_filter) {
                    $own_leads = $own_leads->where('activities.activity_type_id', '=', $activity_filter);
                }

                // Apply lead filter
                if($lead_filter != '') {
                    $own_leads = $own_leads->where('leads.is_opportunity', '=', $lead_filter);
                }

                // Apply user filter
                if ($filtered_managers) {
                    $own_leads->whereIn('leads.sales_person', $filtered_managers);
                }

                if (!$from_date) {
                    $own_leads = $own_leads->leftJoin('activities', function($join) {
                            $join->on('activities.leadid', '=', 'leads.id');
                            $join->where('activities.followup_date','=', date('Y-m-d'));
                        });
                    $own_leads = $own_leads->leftJoin('activitytype', 'activitytype.id', '=', 'activities.activity_type_id');
                    //$own_leads->orderBy('activities.from_date', 'asc');
                    //$own_leads->orderBy('activities.followup_date', 'asc');
                    $own_leads->orderByRaw("CASE WHEN activities.followup_date IS NULL THEN 2 WHEN activities.followup_date >= '".date('Y-m-d')."' THEN 1 END ASC");
                } else {
                    $own_leads = $own_leads->leftJoin('activities', function($join) {
                            $join->on('activities.leadid', '=', 'leads.id');
                            $join->where('activities.followup_date','>=', date('Y-m-d'));
                        });
                    $own_leads = $own_leads->leftJoin('activitytype', 'activitytype.id', '=', 'activities.activity_type_id');
                    $own_leads->orderBy('activities.from_date', 'asc');
                    $own_leads->orderBy('activities.followup_date', 'asc');
                    //$own_leads->orderByRaw("CASE WHEN activities.followup_date IS NULL THEN 2 WHEN activities.followup_date >= '".date('Y-m-d')."' THEN 1 END ASC");
                }
//                echo "Sales Person : ".$user_id."<BR><BR>";
//                echo "Own Leads : ".$own_leads->toSQL()."<BR><BR>";
//                echo "Follow up Date : ".date('Y-m-d')."<BR><BR>";
                $existing_leads = DB::table('leads')
                                    ->select('leads.*','producttype.producttype','subproducttype.producttype as subproduct')
                                    ->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')
                                    ->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                                    ->where('leads.sales_person', '=', $user_id)
                                    ->orderBy('leads.id', 'desc');

                $leads = $own_leads = $own_leads->get();
                $existing_leads = $existing_leads->get();

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

                    // Get Existing Leads for Country Head
                    $existing_leads = DB::table('leads')
                                    ->select('leads.*','producttype.producttype','subproducttype.producttype as subproduct')
                                    ->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')
                                    ->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                                    ->whereIn('leads.sales_person', $userids)
                                    ->orderBy('leads.id', 'desc')->get();

                    $employee_leads = DB::table('leads')
                            ->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city', 'designation.designationname AS designation_name', 'producttype.producttype as product_type', 'subproducttype.producttype as subproduct_type', 'saleperson.name as salesperson', 'sourcetype.sourcetype as source_type', 'sourcevalue.sourcevalue as source_value', 'states.name AS state', 'industrytype.industrytype AS industry_type', 'activities.followup_date as latest_date', 'activitytype.activity_name', 'activities.activity_type_id', 'activities.from_date')
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
                            ->whereIn('leads.sales_person', $userids)
                            ->groupBy('leads.id', 'activities.leadid');

                    // Apply date filter
                    if($from_date) {
                        $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date){
                                            $query->whereDate('activities.from_date', '>=', $from_date);
                                            $query->whereDate('activities.from_date', '<=', $to_date);
                                        });
                    }

                    // Apply activity filter
                    if($activity_filter) {
                        $employee_leads = $employee_leads->where('activities.activity_type_id', '=', $activity_filter);
                    }

                    // Apply lead filter
                    if($lead_filter != '') {
                        $employee_leads = $employee_leads->where('leads.is_opportunity', '=', $lead_filter);
                    }

                    // Apply user filter
                    if ($filtered_managers) {
                        $employee_leads->whereIn('leads.sales_person', $filtered_managers);
                    }

                    if (!$from_date) {
                        $employee_leads = $employee_leads->leftJoin('activities', function($join) {
                                $join->on('activities.leadid', '=', 'leads.id');
                                $join->where('activities.followup_date','=', date('Y-m-d'));
                            });
                        $employee_leads = $employee_leads->leftJoin('activitytype', 'activitytype.id', '=', 'activities.activity_type_id');
                        //$employee_leads->orderBy('salesperson', 'asc');
                        $employee_leads->orderByRaw("CASE WHEN activities.followup_date IS NULL THEN 2 WHEN activities.followup_date >= '".date('Y-m-d')."' THEN 1 END ASC");
                    } else {
                        $employee_leads = $employee_leads->leftJoin('activities', function($join) {
                                $join->on('activities.leadid', '=', 'leads.id');
                                $join->where('activities.followup_date','>=', date('Y-m-d'));
                            });
                        $employee_leads = $employee_leads->leftJoin('activitytype', 'activitytype.id', '=', 'activities.activity_type_id');
                        $employee_leads->orderBy('activities.from_date', 'asc');
                        $employee_leads->orderBy('activities.followup_date', 'asc');
                    }
//                    echo "<pre>";
//                    print_r($userids);
//                    echo "</pre>";
//                    echo "Employee Leads : ".$employee_leads->toSQL()."<BR><BR>";
                    $employee_leads = $employee_leads->get();
                    $own_leads = $own_leads->merge($employee_leads);
                    $own_leads->all();

                    // Get only unique leads
                    $leads = $own_leads->unique('id');
                } elseif ($user_role == 'State Level Admin') {
                    // Get all members of the state level admin's state
                    foreach ($user_state as $key => $state) {
                        $person = DB::table('users')
                                ->whereRaw('FIND_IN_SET("' . $state . '",users.stateid)')
                                ->whereIn('users.role', array('Member', 'State Level Admin'))
                                ->orderBy('name', 'ASC');

                        $person = $person->get();
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

                    // Get Existing Leads for Country Head
                    $existing_leads = DB::table('leads')
                                    ->select('leads.*','producttype.producttype','subproducttype.producttype as subproduct')
                                    ->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')
                                    ->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                                    ->whereIn('leads.sales_person', $userids)
                                    ->orderBy('leads.id', 'desc')->get();

                    $employee_leads = DB::table('leads')
                            ->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city', 'designation.designationname AS designation_name', 'producttype.producttype as product_type', 'subproducttype.producttype as subproduct_type', 'saleperson.name as salesperson', 'sourcetype.sourcetype as source_type', 'sourcevalue.sourcevalue as source_value', 'states.name AS state', 'industrytype.industrytype AS industry_type', 'activities.followup_date as latest_date', 'activitytype.activity_name', 'activities.activity_type_id', 'activities.from_date')
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
                            ->whereIn('leads.sales_person', $userids)
                            ->groupBy('leads.id', 'activities.leadid');

                    // Apply date filter
                    if($from_date) {
                        $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date){
                                            $query->whereDate('activities.from_date', '>=', $from_date);
                                            $query->whereDate('activities.from_date', '<=', $to_date);
                                        });
                    }

                    // Apply activity filter
                    if($activity_filter) {
                        $employee_leads = $employee_leads->where('activities.activity_type_id', '=', $activity_filter);
                    }

                    // Apply lead filter
                    if($lead_filter != '') {
                        $employee_leads = $employee_leads->where('leads.is_opportunity', '=', $lead_filter);
                    }

                    // Apply user filter
                    if ($filtered_managers) {
                        $employee_leads->whereIn('leads.sales_person', $filtered_managers);
                    }

                    if (!$from_date) {
                        $employee_leads = $employee_leads->leftJoin('activities', function($join) {
                                $join->on('activities.leadid', '=', 'leads.id');
                                $join->where('activities.followup_date','=', date('Y-m-d'));
                            });
                        $employee_leads = $employee_leads->leftJoin('activitytype', 'activitytype.id', '=', 'activities.activity_type_id');
                        //$employee_leads->orderBy('salesperson', 'asc');
                        $employee_leads->orderByRaw("CASE WHEN activities.followup_date IS NULL THEN 2 WHEN activities.followup_date >= '".date('Y-m-d')."' THEN 1 END ASC");
                    } else {
                        $employee_leads = $employee_leads->leftJoin('activities', function($join) {
                                $join->on('activities.leadid', '=', 'leads.id');
                                $join->where('activities.followup_date','>=', date('Y-m-d'));
                            });
                        $employee_leads = $employee_leads->leftJoin('activitytype', 'activitytype.id', '=', 'activities.activity_type_id');
                        $employee_leads->orderBy('activities.from_date', 'asc');
                        $employee_leads->orderBy('activities.followup_date', 'asc');
                    }

                    $employee_leads = $employee_leads->get();
                    $own_leads = $own_leads->merge($employee_leads);
                    $own_leads->all();

                    // Get only unique leads
                    $leads = $own_leads->unique('id');
                }
            }
//            echo "<pre>";
//            print_r($leads);
//            echo "</pre>";
            // Iterating leads
            foreach($leads as $lead) {
                if ($lead->latest_date >= date('Y-m-d')) {
                    //echo $lead->latest_date." >> ". date('Y-m-d')."<BR>";
                    $temp_leads1 = $temp_leads1->push($lead);
                }
            }
//            echo "<pre>";
//            print_r($temp_leads1);
//            echo "</pre>";
            // Get activity types
            $activity_types = DB::table('activitytype')->orderBy('activity_name', 'ASC')->get();
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
            $source_values = DB::table('sourcevalue')->orderBy('sourcevalue', 'ASC')->get();
            // Get product types
            $product_types = DB::table('producttype')->where('prolevel', 1)->orderBy('producttype', 'ASC')->get();
            // Get states
            $states = $sataedata = DB::table('states')->where('country_id', 101)->orderBy('name', 'ASC')->get();

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
        // Initialize person
        $zones = collect();

        /******************************* Get Zones ******************************** */
        if ($user_role == 'Country Head') {
            $zones = DB::table('regiondata')->where('regioblavel',1)->orderBy('id', 'DESC')->get();
        } elseif ($user_role == 'Zonal Level Admin' || $user_role == 'State Level Admin' || $user_role == 'Member') {
            $zones = DB::table('users')
                    ->join('regiondata', 'regiondata.id', '=', 'users.zone')
                    ->orWhere('users.id', '=', $user_id)
                    ->orderBy('regionname', 'ASC')
                    ->get();
        }
            return view('admin.activity.activitymanagement')
                            ->with(['leads' => $leads, 'user_id' => $user_id, 'sales_person' => $sales_person,
                                    'account_types' => $account_types,'industry_types' => $industry_types,
                                    'designations' => $designations,'departments' => $departments,
                                    'source_types' => $source_types,'source_values' => $source_values,
                                    'product_types' => $product_types,'states' => $states,
                                    'activity_types' => $activity_types, 'filtered_managers' => $filtered_managers,
                                    'zones' => $zones, 'existing_leads' => $existing_leads
                                    ]);
        }
    }

    public function editlead($id) {
        $leadeditata = DB::table('leads')->where('id', '=', $id)->orderBy('id', 'desc')
                ->first();
        return view('admin.lead.editlead')
                        ->with('leadeditata', $leadeditata);
    }

    /**
     * 	Save Data Category
     */
    public function savelead(Request $request) {
        $savedata = $request->all();
        $LeadId = null;
        if ($request->radion == "Existing") {
            $LeadId = $request->get('existing-lead');
        } else if ($request->radion == "New") {
            $TimeStamp = DateTime::createFromFormat('d/m/Y H:i a', $savedata['lead_date'])->format('Y-m-d H:i:s');
            $insertdata = array(
                'organisation' => ucwords(strtolower($savedata['organisation'])),
                'salutation' => $savedata['salutation'],
                'first_name' => ucwords(strtolower($savedata['first_name'])),
                'last_name' => ucwords(strtolower($savedata['last_name'])),
                'contact_number' => $savedata['mobile'],
                'email_id' => $savedata['youremail'],
                'competitor_name' => $savedata['competitor'],
                'account_type' => isset($savedata['account_type']) ? $savedata['account_type'] : NULL,
                'industry_type' => $savedata['industrytypoe'],
                'designation' => $savedata['designatoin'],
                'department' => $savedata['department'],
                'sales_person' => $savedata['saleperson'],
                'lead_details' => $savedata['lead_details'],
                'source_type' => $savedata['source_type'],
                'source_value' => $savedata['source_value'],
                'product_type' => $savedata['product_type'],
                'lead_status' => $savedata['lead_status'],
                'sub_product' => isset($savedata['sub_product']) ? $savedata['sub_product'] : NULL,
                'payment_type' => isset($savedata['payment_type']) ? $savedata['payment_type'] : NULL,
                'channel_partner' => isset($savedata['channel_partner']) ? $savedata['channel_partner'] : NULL,
                'channel_competitor' => ucwords(strtolower($savedata['channel_competitor'])),
                'lead_type' => $savedata['lead_type'],
                'address' => $savedata['address'],
                'state_id' => $savedata['state_id'],
                'city_id' => $savedata['city_id'],
                'pincode' => $savedata['pincode'],
                'lead_date' => $TimeStamp,
                'created_by' => $savedata['userid'],
                'created_time' => date("Y-m-d h:i:s")
            );
            $leadData = DB::table('leads')->insertGetId($insertdata);
            $LeadId = $leadData;

            // Send Email
            dispatch(new LeadCreatedJob(LeadData::where('id', $LeadId)->first()));

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
                            ->where('leads.id', '=', $LeadId)
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

        } else {
            $LeadData = new LeadData();
            $LeadData->organisation = ucwords(strtolower($savedata['organisation_']));
            $LeadData->salutation = $savedata['salutation_'];
            $LeadData->first_name = ucwords(strtolower($savedata['first_name_']));
            $LeadData->last_name = ucwords(strtolower($savedata['last_name_']));
            $LeadData->lead_type = 1;
            $LeadData->contact_number = $savedata['mobile_'];
            $LeadData->email_id = $savedata['youremail_'] ? $savedata['youremail_'] : NULL;
            $LeadData->sales_person = $savedata['sale_person_id'];
            $LeadData->created_by = auth()->user()->id;
            $LeadData->save();
            $LeadId = $LeadData->id;
            dispatch(new PreSalesJob(LeadData::where('id', $LeadId)->first()));
        }

        // Get leads
        $leadeditata = DB::table('leads')->where('id', '=', $LeadId)->orderBy('id', 'desc')->first();

        $lead_status = "";

        if ($leadeditata->lead_status == 1) {
            $lead_status = 'Hot';
        } elseif ($leadeditata->lead_status == 2) {
            $lead_status = 'Warm';
        } elseif ($leadeditata->lead_status == 3) {
            $lead_status = 'Cold';
        }

        $savedata['leadid'] = $LeadId;
        $savedata['createdtime'] = date("Y-m-d H:i:s", time());
        $savedata['createdby'] = auth()->user()->id;
        $savedata['link_to'] = $request->radion;
        $savedata['lead_status'] = $lead_status ? $lead_status : "";
        $FromDate = $savedata['from_date'] ? DateTime::createFromFormat('d/m/Y H:i a', $savedata['from_date'])->format('Y-m-d H:i:s') : NULL;
        $ToDate = $savedata['to_date'] ? DateTime::createFromFormat('d/m/Y H:i a', $savedata['to_date'])->format('Y-m-d H:i:s') : NULL;
        $savedata['from_date'] = $FromDate;
        $savedata['to_date'] = $ToDate;

        // Add data in activity data
        ActivityData::create($savedata);

        Session::flash('sucmessage', "Successful, New Lead has been added!");

        return Redirect::back();
    }

    public function updatelead(Request $request) {

        $savedata = $request->all();

        $insertdata = array(
            'organisation' => ucwords(strtolower($savedata['organisation'])),
            'salutation' => $savedata['salutation'],
            'first_name' => ucwords(strtolower($savedata['first_name'])),
            'last_name' => ucwords(strtolower($savedata['last_name'])),
            'contact_number' => $savedata['mobile'],
            'email_id' => $savedata['youremail'],
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
            'pincode' => $savedata['pincode'],
            'lead_date' => $savedata['lead_date'],
            'created_by' => $savedata['userid'],
            'created_time' => date("Y-m-d h:i:s"),
        );
        DB::table('leads')->where('id', $savedata['editid'])->update($insertdata);

        Session::flash('sucmessage', "Successful,  Lead has been update!");

        return Redirect('admin/leadmanagement');
    }

    //////////
    // Delete Data
    ////////////
    public function leadtatus($id) {
        $stauts = $_REQUEST['stauts'];

        $insertdata = array(
            'lead_status' => $stauts,
            'created_time' => date("Y-m-d h:i:s"),
        );

        DB::table('leads')->where('id', $id)->update($insertdata);

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
        $posts = DB::table('Youtubevideos')->orderBy('id', 'desc')
                ->paginate(100);
        return view('admin.page.managevideo')
                        ->with('pages', $posts);
    }

    public function editvideo($id) {
        $pincode = DB::table('Youtubevideos')->where('id', '=', $id)->get();
        return view('admin.page.editvideo')
                        ->with('pagecontent', $pincode);
    }

    /**
     * 	Save Data Category
     */
    public function savevideo(Request $request) {

        $savedata = $request->all();

        $insertdata = array(
            'videoname' => $savedata["pagetitle"],
            'videourl' => $savedata["videolink"],
            'created' => date("Y-m-d h:i:s"),
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

    public function savemanageactivity(Request $request) {

        $savedata = $request->all();
        // Get leads
        $leadeditata = DB::table('leads')->where('id', '=', $savedata["leadid"])->orderBy('id', 'desc')->first();

        $lead_status = "";
        if ($leadeditata->lead_status == 1) {
            $lead_status = 'Hot';
        } elseif ($leadeditata->lead_status == 2) {
            $lead_status = 'Warm';
        } elseif ($leadeditata->lead_status == 3) {
            $lead_status = 'Cold';
        }

        // Closed
        if ($savedata['leadfollow'] == 3) {

            $updatedata = array(
                'activity_status' => $savedata['leadfollow'],
                'lead_closed_by' => $savedata["userid"],
                'lead_closed_time' => date("Y-m-d h:i:s"),
                'status' => 'Closed'
            );
            DB::table('leads')->where('id', $savedata['leadid'])->update($updatedata);

            $activity = array(
                'leadid' => $savedata["leadid"],
                'activity_details' => $savedata["narretiondata"],
                'activity_type_id' => 17,
                'from_date' => date("Y-m-d h:i:s"),
                'sale_person_id' => $leadeditata->sales_person,
                'createdby' => $savedata["userid"],
                'createdtime' => date("Y-m-d h:i:s"),
                'lead_status' => $lead_status,
            );
            $activity_id = DB::table('activities')->insertGetId($activity);
        }
        // Reschedule
        else if ($savedata['leadfollow'] == 2) {
            // Update Activity data
            $activity['leadid'] = $savedata["leadid"];
            $activity['createdby'] = $savedata["userid"];
            $activity['createdtime'] = date("Y-m-d h:i:s");
            $activity['activity_type_id'] = $savedata['activity_type_id'];
            $activity['activity_details'] = $savedata['activity_details'];
            $activity['from_date'] = date("Y-m-d h:i:s");
            $activity['sale_person_id'] = $savedata['sale_person_id'];
            $activity['followup_date'] = $savedata['followup_date'];
            $activity['link_to'] = 'Existing';
            $activity['lead_status'] = $lead_status;

            $activity_id = DB::table('activities')->insertGetId($activity);
        }
        // Transfer
        else if ($savedata['leadfollow'] == 1) {
            $activity = array(
                'leadid' => $savedata["leadid"],
                'activity_details' => $savedata["narretiondata"],
                'from_date' => date("Y-m-d h:i:s"),
                'activity_type_id' => 5, // Lead Transfer Activity ID
                'link_to' => 'Existing',
                'createdby' => $savedata["userid"],
                'createdtime' => date("Y-m-d h:i:s"),
                'sale_person_id' => $savedata['sale_person_transfer'],
                'lead_status' => $lead_status
            );

            $activity_id = DB::table('activities')->insertGetId($activity);

            // Update lead deatils in case of transfer lead
            $update_lead = array(
                'sales_person' => $savedata['sale_person_transfer'],
                'created_by' => $savedata["userid"]
            );

            DB::table('leads')->where('id', $savedata["leadid"])->update($update_lead);
        }

        dispatch(
            new ManageActivityJob(LeadData::where('id', $savedata["leadid"])->first(), $savedata['leadfollow'], $activity_id)
        );

        Session::flash('sucmessage', "Successful, Activity Has been managed");

        return Redirect::back();
    }

    public function activityleadtatus($id) {
        $stauts = $_REQUEST['stauts'];
        $natry = '';
        if ($stauts == 1) {
            $natry = 'User has been ransfer lead to Hot Status';
        }
        if ($stauts == 2) {
            $natry = 'User has been ransfer lead to Warm Status';
        }
        if ($stauts == 3) {
            $natry = 'User has been ransfer lead to Cold Status';
        }

        $insertdata = array(
            'lead_status' => $stauts,
            'created_time' => date("Y-m-d h:i:s"),
        );

        DB::table('leads')->where('id', $id)->update($insertdata);

        $updatedata = array(
            'leadid' => $id,
            'activity_details' => $natry,
            'createdby' => auth()->user()->id,
            'createdtime' => date("Y-m-d h:i:s"),
        );
        DB::table('activities')
                ->insert($updatedata);

        Session::flash('sucmessage', "Successful, Lead Status has been Changed");

        return Redirect::back();
    }

    public function history(Request $request, $id) {
        // Initialize leads leads
        $leads = collect();
        // Initialize employee leads
        $lead_counts = collect();

        if ($id > 0) {
            // Get from date
            $from_date = $request->get('history-from');
            // Get to date
            $to_date = $request->get('history-to');
            // Get filtered managers
            $history_activity = $request->get('history-activity');
            // Get activity types
            $activity_types = DB::table('activitytype')->orderBy('activity_name', 'ASC')->get();

            $leads = DB::table('leads')
                    ->select('leads.lead_type', 'activities.from_date', 'activitytype.activity_name',
                            'activities.id','saleperson.name as sales_person', 'activities.accompanied_by_rh',
                            'activities.accompanied_by_sh', 'activities.accompanied_by_others',
                            'activities.accompanied_by_ph', 'activities.link_to','leads.first_name',
                            'leads.last_name','leads.contact_number', 'leads.email_id','states.name AS state',
                            'leads.lead_status','activities.activity_details','leads.is_opportunity', 'leads.opp_status',
                            'activities.lead_status', 'activities.opportunity_status', 'activities.createdtime'
                            )
                    ->join('activities', 'activities.leadid', '=', 'leads.id')
                    ->leftJoin('activitytype', 'activitytype.id', '=', 'activities.activity_type_id')
                    ->leftJoin('users AS saleperson', 'saleperson.id', '=', 'activities.sale_person_id')
                    ->leftJoin('cities', 'cities.id', '=', 'leads.city_id')
                    ->leftJoin('states', 'states.id', '=', 'leads.state_id')
                    ->where('leads.id', '=', $id)
                    ->orderBy('activities.createdtime', 'desc');

            // Apply date filter
            if($from_date) {
                $leads = $leads->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('from_date', '>=', $from_date);
                                    $query->whereDate('from_date', '<=', $to_date);
                                });
            }

            // Apply activity filter
            if($history_activity) {
                $leads = $leads->where('activities.activity_type_id', '=', $history_activity);
            }

            $leads = $leads->get();

            $lead_counts = DB::select('SELECT leads.id, leads.organisation, leads.lead_date,
                            (SELECT COUNT(atd.activity_type_id) FROM activities as atd WHERE atd.leadid = leads.id AND atd.activity_type_id=2) as calls,
                            (SELECT COUNT(atd.activity_type_id) FROM activities as atd WHERE atd.leadid = leads.id AND atd.activity_type_id=4) as status,
                            (SELECT COUNT(atd.activity_type_id) FROM activities as atd WHERE atd.leadid = leads.id AND atd.activity_type_id=5) as transferred,
                            (SELECT COUNT(atd.activity_type_id) FROM activities as atd WHERE atd.leadid = leads.id AND atd.activity_type_id=6) as meetings,
                            (SELECT COUNT(atd.activity_type_id) FROM activities as atd WHERE atd.leadid = leads.id AND atd.activity_type_id=10) as emails
                            FROM leads
                            WHERE leads.id = '.$id.'');

        }
        return view('admin.activity.history')->with(array('leads' => $leads, 'lead_counts' => $lead_counts, 'activity_types' => $activity_types));
    }

}
