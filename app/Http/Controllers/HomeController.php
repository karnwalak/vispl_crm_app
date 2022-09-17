<?php

namespace App\Http\Controllers;

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

class HomeController extends Controller {

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
    public function index(Request $request) {
        // Get logged in user id
        $user_id = auth()->user()->id;
        // Get logged in user role
        $user_role = auth()->user()->role;
        // Get logged in user states and convert them into an array
        $user_state = explode(",", auth()->user()->stateid);
        // Initialize product summary
        $product_summary = collect();
        // Initialize wip summary
        $wip_summary = collect();
        // Initialize win summary
        $win_summary = collect();
        // Initialize lost summary
        $lost_summary = collect();
        // Initialize shelved summary
        $shelved_summary = collect();
        // Initialize opportunity lead summary
        $opportunity_lead_summary = collect();
        // Initialize opportunity account manager wip summary
        $manager_wip_summary = collect();
        // Initialize opportunity account manager win summary
        $manager_win_summary = collect();
        // Initialize opportunity account manager lost summary
        $manager_lost_summary = collect();
        // Initialize opportunity account manager shelved summary
        $manager_shelved_summary = collect();
        // Initialize accounts type summary
        $account_types_summary = collect();
        // Initialize accounts type wip summary
        $account_types_wip_summary = collect();
        // Initialize accounts type win summary
        $account_types_win_summary = collect();
        // Initialize accounts type lost summary
        $account_types_lost_summary = collect();
        // Initialize accounts type shelved summary
        $account_types_shelved_summary = collect();
        // Initialize customer type summary
        $customer_type_summary = collect();
        // Initialize customer wip type summary
        $customer_wip_summary = collect();
        // Initialize customer win type summary
        $customer_win_summary = collect();
        // Initialize customer lost type summary
        $customer_lost_summary = collect();
        // Initialize customer shelved type summary
        $customer_shelved_summary = collect();
        // Initialize manager type summary
        $account_manager_summary = collect();
        // Initialize manager wip type summary
        $account_manager_wip_summary = collect();
        // Initialize manager win type summary
        $account_manager_win_summary = collect();
        // Initialize manager lost type summary
        $account_manager_lost_summary = collect();
        // Initialize manager shelved type summary
        $account_manager_shelved_summary = collect();
        // Initialize sales person
        $account_managers = collect();
        // Initialize sales person
        $sales_person = collect();        
        // Initialize person
        $person = collect();
        // Initialize person
        $zones = collect();
        // Get filtered managers
        $filtered_managers = $request->get('managers');
        // Get from date
        $from_date = $request->get('from-search');
        // Get to date
        $to_date = $request->get('to-search');

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

        /******************************* Get Sales Person ******************************** */
        if ($user_role != "Member") {
            if ($user_role == 'Country Head') {
                $account_managers = DB::table('users')
                                    ->whereIn('role', array('Zonal Level Admin', 'State Level Admin', 'Member'))
                                    ->orderBy('name', 'ASC');
                if ($filtered_managers) {
                    $account_managers->whereIn('id', $filtered_managers);
                }
                $account_managers = $account_managers->get();
            } elseif ($user_role == 'Zonal Level Admin') {
                foreach ($user_state as $key => $state) {
                    $person = DB::table('users')
                            ->whereRaw('FIND_IN_SET("' . $state . '",users.stateid)')
                            ->orderBy('name', 'ASC');

                    if ($filtered_managers) {
                        $person->whereIn('id', $filtered_managers);
                    }

                    $person = $person->get();
                    $account_managers = $account_managers->merge($person);
                    $account_managers->all();
                }
                // Get only unique sales person
                $account_managers = $account_managers->unique('id');
            } elseif ($user_role == 'State Level Admin') {
                foreach ($user_state as $key => $state) {
                    $person = DB::table('users')
                            ->whereRaw('FIND_IN_SET("' . $state . '",users.stateid)')
                            ->where('users.role', '=', 'Member')
                            ->orWhere('users.id', '=', $user_id)
                            ->orderBy('name', 'ASC');

                    if ($filtered_managers) {
                        $person->whereIn('id', $filtered_managers);
                    }

                    $person = $person->get();
                    $account_managers = $account_managers->merge($person);
                    $account_managers->all();
                }
                // Get only unique sales person
                $account_managers = $account_managers->unique('id');
            }
        } else {
            $account_managers = DB::table('users')->where('id', auth()->user()->id)->orderBy('name', 'ASC')->get();
        }

        /******************************* Get Product Types ******************************** */
        $product_types = DB::table('producttype')->where('prolevel', 1)->orderBy('producttype', 'ASC')->get();

        /******************************* Product Wise Counts ******************************** */
        $query = "";
        // Get all product ids
        foreach ($product_types as $product) {
            $query .= "(SUM(IF(product_type = ".$product->id." and leads.is_opportunity = '1', leads.expected_value, 0))) As " . str_replace(" ", "_", strtolower($product->producttype)) . ", ";
        }
        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }
            //echo $data->toSQL()."<BR><BR>";
            $data = $data->get();

            $product_summary = $product_summary->merge($data);
        }

        /******************************* WIP Wise Counts ******************************** */
        $query = "";
        // Get all product ids
        foreach ($product_types as $product) {
            $query .= "SUM(CASE WHEN(product_type = " . $product->id . " AND is_opportunity = '1' AND opp_status = 'WIP') THEN expected_value ELSE 0 END) As " . trim(str_replace(" ", "_", strtolower($product->producttype))) . ", ";
        }
        // Remove trailing comma
        $query = substr($query, 0, -2);
        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $wip_summary = $wip_summary->merge($data);
        }

        /******************************* Win Wise Counts ******************************** */
        $query = "";
        // Get all product ids
        foreach ($product_types as $product) {
            $query .= "SUM(CASE WHEN(product_type = " . $product->id . " AND is_opportunity = '1' AND opp_status = 'Win') THEN expected_value ELSE 0 END) As " . str_replace(" ", "_", strtolower($product->producttype)) . ", ";
        }
        // Remove trailing comma
        $query = substr($query, 0, -2);
        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $win_summary = $win_summary->merge($data);
        }

        /******************************* Lost Wise Counts ******************************** */
        $query = "";
        // Get all product ids
        foreach ($product_types as $product) {
            $query .= "SUM(CASE WHEN(product_type = " . $product->id . " AND is_opportunity = '1' AND opp_status = 'Lost') THEN expected_value ELSE 0 END) As " . str_replace(" ", "_", strtolower($product->producttype)) . ", ";
        }
        // Remove trailing comma
        $query = substr($query, 0, -2);
        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $lost_summary = $lost_summary->merge($data);
        }


        /******************************* Shelved Wise Counts ******************************** */
        $query = "";
        // Get all product ids
        foreach ($product_types as $product) {
            $query .= "SUM(CASE WHEN(product_type = " . $product->id . " AND is_opportunity = '1' AND opp_status = 'Shelved') THEN expected_value ELSE 0 END) As " . str_replace(" ", "_", strtolower($product->producttype)) . ", ";
        }
        // Remove trailing comma
        $query = substr($query, 0, -2);
        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $shelved_summary = $shelved_summary->merge($data);
        }

        /**************************Opportunity and Lead Status Wise Counts ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.lead_status = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.lead_status, NULL))) as wip_hot_count, ";
        $query .= "(COUNT(IF(leads.lead_status = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.lead_status, NULL))) as wip_warm_count, "; 
        $query .= "(COUNT(IF(leads.lead_status = '3' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.lead_status, NULL))) as wip_cold_count, ";
        $query .= "(SUM(IF(leads.lead_status = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.expected_value, 0))) as wip_hot_order_value, ";
        $query .= "(SUM(IF(leads.lead_status = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.expected_value, 0))) as wip_warm_order_value, ";
        $query .= "(SUM(IF(leads.lead_status = '3' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.expected_value, 0))) as wip_cold_order_value ";

        // Get all user ids from member collection
        $userids = array();

        foreach ($account_managers as $manager) {
            $userids[] = $manager->id;
        }

        // Get product wise expected order value of each user
        $data = DB::table('users')
                ->select('users.id', 'users.name', 'leads.opp_status', DB::raw($query))
                ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                ->whereIn('leads.sales_person', $userids)
                ->where('leads.opp_status', '!=', NULL)
                ->groupBy('leads.opp_status');

        // Apply date filters
        if($from_date) {
            $data = $data->where(function($query) use ($from_date, $to_date){
                                $query->whereDate('lead_date', '>=', $from_date);
                                $query->whereDate('lead_date', '<=', $to_date);
                            });
        }
        //echo $data->toSQL()."<BR><BR>";
        $data = $data->get();

        $opportunity_lead_summary = $opportunity_lead_summary->merge($data);

        /******************************* Manager WIP Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.lead_status = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.lead_status, NULL))) as wip_hot_count, ";
        $query .= "(COUNT(IF(leads.lead_status = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.lead_status, NULL))) as wip_warm_count, "; 
        $query .= "(COUNT(IF(leads.lead_status = '3' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.lead_status, NULL))) as wip_cold_count, ";
        $query .= "(SUM(IF(leads.lead_status = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.expected_value, 0))) as wip_hot_expected_value, ";
        $query .= "(SUM(IF(leads.lead_status = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.expected_value, 0))) as wip_warm_expected_value, ";
        $query .= "(SUM(IF(leads.lead_status = '3' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.expected_value, 0))) as wip_cold_expected_value ";

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $manager_wip_summary = $manager_wip_summary->merge($data);
        }

        /******************************* Manager Win Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.lead_status = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.lead_status, NULL))) as wip_hot_count, ";
        $query .= "(COUNT(IF(leads.lead_status = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.lead_status, NULL))) as wip_warm_count, "; 
        $query .= "(COUNT(IF(leads.lead_status = '3' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.lead_status, NULL))) as wip_cold_count, ";
        $query .= "(SUM(IF(leads.lead_status = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.expected_value, 0))) as wip_hot_expected_value, ";
        $query .= "(SUM(IF(leads.lead_status = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.expected_value, 0))) as wip_warm_expected_value, ";
        $query .= "(SUM(IF(leads.lead_status = '3' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.expected_value, 0))) as wip_cold_expected_value ";

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $manager_win_summary = $manager_win_summary->merge($data);
        }

        /******************************* Manager Lost Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.lead_status = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.lead_status, NULL))) as wip_hot_count, ";
        $query .= "(COUNT(IF(leads.lead_status = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.lead_status, NULL))) as wip_warm_count, "; 
        $query .= "(COUNT(IF(leads.lead_status = '3' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.lead_status, NULL))) as wip_cold_count, ";
        $query .= "(SUM(IF(leads.lead_status = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.expected_value, 0))) as wip_hot_expected_value, ";
        $query .= "(SUM(IF(leads.lead_status = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.expected_value, 0))) as wip_warm_expected_value, ";
        $query .= "(SUM(IF(leads.lead_status = '3' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.expected_value, 0))) as wip_cold_expected_value ";

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $manager_lost_summary = $manager_lost_summary->merge($data);
        }


        /******************************* Manager Shelved Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.lead_status = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.lead_status, NULL))) as wip_hot_count, ";
        $query .= "(COUNT(IF(leads.lead_status = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.lead_status, NULL))) as wip_warm_count, "; 
        $query .= "(COUNT(IF(leads.lead_status = '3' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.lead_status, NULL))) as wip_cold_count, ";
        $query .= "(SUM(IF(leads.lead_status = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.expected_value, 0))) as wip_hot_expected_value, ";
        $query .= "(SUM(IF(leads.lead_status = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.expected_value, 0))) as wip_warm_expected_value, ";
        $query .= "(SUM(IF(leads.lead_status = '3' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.expected_value, 0))) as wip_cold_expected_value ";

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $manager_shelved_summary = $manager_shelved_summary->merge($data);
        }

        /******************************* Get Product Types ******************************** */
        $account_types = DB::table('accounttype')->orderBy('accounttype', 'ASC')->get();

        /******************************* Account Type Summary ******************************** */
        $query = "";
        // Get all product ids
        foreach ($account_types as $type) {
            $query .= "(COUNT(IF(leads.account_type = '".$type->accounttype."' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.account_type, NULL))) as " . str_replace(array(' ', '-'), "_", strtolower($type->accounttype)) . "_counts, ";
            $query .= "(SUM(IF(leads.account_type = '".$type->accounttype."' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.expected_value, 0))) as " . str_replace(array(' ', '-'), "_", strtolower($type->accounttype)) . "_expected_value, ";
        }
        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }
            //echo $data->toSQL()."<BR><BR>";
            $data = $data->get();

            $account_types_summary = $account_types_summary->merge($data);
        }

        /******************************* Account Type WIP Summary ******************************** */
        $query = "";
        // Get all product ids
        foreach ($account_types as $type) {
            $query .= "(COUNT(IF(leads.account_type = '".$type->accounttype."' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.account_type, NULL))) as " . str_replace(array(' ', '-'), "_", strtolower($type->accounttype)) . "_counts, ";
            $query .= "(SUM(IF(leads.account_type = '".$type->accounttype."' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.expected_value, 0))) as " . str_replace(array(' ', '-'), "_", strtolower($type->accounttype)) . "_expected_value, ";
        }
        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $account_types_wip_summary = $account_types_wip_summary->merge($data);
        }

        /******************************* Account Type Win Summary ******************************** */
        $query = "";
        // Get all product ids
        foreach ($account_types as $type) {
            $query .= "(COUNT(IF(leads.account_type = '".$type->accounttype."' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.account_type, NULL))) as " . str_replace(array(' ', '-'), "_", strtolower($type->accounttype)) . "_counts, ";
            $query .= "(SUM(IF(leads.account_type = '".$type->accounttype."' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.expected_value, 0))) as " . str_replace(array(' ', '-'), "_", strtolower($type->accounttype)) . "_expected_value, ";
        }
        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $account_types_win_summary = $account_types_win_summary->merge($data);
        }

        /******************************* Account Type Lost Summary ******************************** */
        $query = "";
        // Get all product ids
        foreach ($account_types as $type) {
            $query .= "(COUNT(IF(leads.account_type = '".$type->accounttype."' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.account_type, NULL))) as " . str_replace(array(' ', '-'), "_", strtolower($type->accounttype)) . "_counts, ";
            $query .= "(SUM(IF(leads.account_type = '".$type->accounttype."' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.expected_value, 0))) as " . str_replace(array(' ', '-'), "_", strtolower($type->accounttype)) . "_expected_value, ";
        }
        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $account_types_lost_summary = $account_types_lost_summary->merge($data);
        }

        /******************************* Account Type Shelved Summary ******************************** */
        $query = "";
        // Get all product ids
        foreach ($account_types as $type) {
            $query .= "(COUNT(IF(leads.account_type = '".$type->accounttype."' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.account_type, NULL))) as " . str_replace(array(' ', '-'), "_", strtolower($type->accounttype)) . "_counts, ";
            $query .= "(SUM(IF(leads.account_type = '".$type->accounttype."' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.expected_value, 0))) as " . str_replace(array(' ', '-'), "_", strtolower($type->accounttype)) . "_expected_value, ";
        }
        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $account_types_shelved_summary = $account_types_shelved_summary->merge($data);
        }

        /******************************* Customer Type Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.lead_type = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.lead_type, NULL))) as customer_new_count, ";
        $query .= "(COUNT(IF(leads.lead_type = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.lead_type, NULL))) as customer_existing_count, "; 
        $query .= "(SUM(IF(leads.lead_type = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.expected_value, 0))) as customer_new_value, ";
        $query .= "(SUM(IF(leads.lead_type = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.expected_value, 0))) as customer_existing_value, ";

        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $customer_type_summary = $customer_type_summary->merge($data);
        }

        /******************************* Customer Type WIP Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.lead_type = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.lead_type, NULL))) as customer_new_count, ";
        $query .= "(COUNT(IF(leads.lead_type = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.lead_type, NULL))) as customer_existing_count, "; 
        $query .= "(SUM(IF(leads.lead_type = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.expected_value, 0))) as customer_new_value, ";
        $query .= "(SUM(IF(leads.lead_type = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.expected_value, 0))) as customer_existing_value, ";

        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $customer_wip_summary = $customer_wip_summary->merge($data);
        }

        /******************************* Customer Type Win Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.lead_type = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.lead_type, NULL))) as customer_new_count, ";
        $query .= "(COUNT(IF(leads.lead_type = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.lead_type, NULL))) as customer_existing_count, "; 
        $query .= "(SUM(IF(leads.lead_type = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.expected_value, 0))) as customer_new_value, ";
        $query .= "(SUM(IF(leads.lead_type = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.expected_value, 0))) as customer_existing_value, ";

        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $customer_win_summary = $customer_win_summary->merge($data);
        }

        /******************************* Customer Type Lost Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.lead_type = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.lead_type, NULL))) as customer_new_count, ";
        $query .= "(COUNT(IF(leads.lead_type = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.lead_type, NULL))) as customer_existing_count, "; 
        $query .= "(SUM(IF(leads.lead_type = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.expected_value, 0))) as customer_new_value, ";
        $query .= "(SUM(IF(leads.lead_type = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.expected_value, 0))) as customer_existing_value, ";

        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $customer_lost_summary = $customer_lost_summary->merge($data);
        }

        /******************************* Customer Type Shelved Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.lead_type = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.lead_type, NULL))) as customer_new_count, ";
        $query .= "(COUNT(IF(leads.lead_type = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.lead_type, NULL))) as customer_existing_count, "; 
        $query .= "(SUM(IF(leads.lead_type = '1' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.expected_value, 0))) as customer_new_value, ";
        $query .= "(SUM(IF(leads.lead_type = '2' and leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.expected_value, 0))) as customer_existing_value, ";

        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $customer_shelved_summary = $customer_shelved_summary->merge($data);
        }

        /******************************* Account Manager Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.sales_person, NULL))) as customer_leads, "; 
        $query .= "(SUM(IF(leads.is_opportunity = '1' and leads.expected_value != 'NULL', leads.expected_value, 0))) as customer_existing_value, ";

        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $account_manager_summary = $account_manager_summary->merge($data);
        }

        /******************************* Account Manager WIP Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.sales_person, NULL))) as customer_leads, "; 
        $query .= "(SUM(IF(leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'WIP', leads.expected_value, 0))) as customer_existing_value, ";

        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $account_manager_wip_summary = $account_manager_wip_summary->merge($data);
        }

        /******************************* Account Manager Win Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.sales_person, NULL))) as customer_leads, "; 
        $query .= "(SUM(IF(leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Win', leads.expected_value, 0))) as customer_existing_value, ";

        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $account_manager_win_summary = $account_manager_win_summary->merge($data);
        }

        /******************************* Account Manager Lost Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.sales_person, NULL))) as customer_leads, "; 
        $query .= "(SUM(IF(leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Lost', leads.expected_value, 0))) as customer_existing_value, ";

        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $account_manager_lost_summary = $account_manager_lost_summary->merge($data);
        }

        /******************************* Account Manager Shelved Summary ******************************** */
        $query = "";
        $query .= "(COUNT(IF(leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.sales_person, NULL))) as customer_leads, "; 
        $query .= "(SUM(IF(leads.is_opportunity = '1' and leads.expected_value != 'NULL' and leads.opp_status = 'Shelved', leads.expected_value, 0))) as customer_existing_value, ";

        // Remove trailing comma
        $query = substr($query, 0, -2);

        // Get product wise expected order value of each user
        foreach ($account_managers as $manager) {
            $data = DB::table('users')
                    ->select('users.id', 'users.name', DB::raw($query))
                    ->leftJoin('leads', 'leads.sales_person', '=', 'users.id')
                    ->where('users.id', $manager->id)
                    ->groupBy('users.id');

            // Apply date filters
            if($from_date) {
                $data = $data->where(function($query) use ($from_date, $to_date){
                                    $query->whereDate('lead_date', '>=', $from_date);
                                    $query->whereDate('lead_date', '<=', $to_date);
                                });
            }

            $data = $data->get();

            $account_manager_shelved_summary = $account_manager_shelved_summary->merge($data);
        }

        return view('admin.home')->with(array('product_types' => $product_types, 'account_types' => $account_types,
                    'product_summary' => $product_summary, 'wip_summary' => $wip_summary, 'win_summary' => $win_summary,
                    'lost_summary' => $lost_summary, 'shelved_summary' => $shelved_summary,'zones' => $zones,
                    'opportunity_lead_summary' => $opportunity_lead_summary, 'manager_wip_summary' => $manager_wip_summary,
                    'manager_win_summary' => $manager_win_summary, 'manager_lost_summary' => $manager_lost_summary,
                    'manager_shelved_summary' => $manager_shelved_summary, 'account_types_summary' => $account_types_summary,
                    'account_types_wip_summary' => $account_types_wip_summary, 'account_types_win_summary' => $account_types_win_summary,
                    'account_types_lost_summary' => $account_types_lost_summary, 'account_types_shelved_summary' => $account_types_shelved_summary,
                    'customer_type_summary' => $customer_type_summary, 'customer_wip_summary' => $customer_wip_summary,
                    'customer_win_summary' => $customer_win_summary, 'customer_lost_summary' => $customer_lost_summary,
                    'customer_shelved_summary' => $customer_shelved_summary,'account_manager_summary' => $account_manager_summary,
                    'account_manager_wip_summary' => $account_manager_wip_summary, 'account_manager_win_summary' => $account_manager_win_summary,
                    'account_manager_lost_summary' => $account_manager_lost_summary, 'account_manager_shelved_summary' => $account_manager_shelved_summary,
                    'account_managers' => $account_managers, 'filtered_managers' => $filtered_managers, 'sales_person' => $sales_person,
        ));
    }

    //////////////// Company Dat Setting:$id


    public function notification() {
        $posts = DB::table('notifications')->get();
        return view('admin.notification.notification')->with('notifications', $posts);
    }

    //////////////// Company Dat Setting:

    public function profile() {

        $companyinfo = DB::table('personal_info')->first();

        return view('admin.siteedit')->with('companyinfo', $companyinfo);
    }

    //////////////// Company Dat Setting:

    public function updateprofile(Request $request) {
        $orgdata = $request->all();

        /* $target_file = $target_dir .$orgdata['image'];
          move_uploaded_file($_FILES['image']['tmp_name'], __DIR__.'/../../uploads/'.$orgdata['image']); */
        $status = 1;
        $createdby = 1;
        $modifiedby = 1;
        $createddate = date("Y-m-d H:i:s");
        $modifieddate = date("Y-m-d H:i:s");
        $insertdata = array(
            'name' => $orgdata['companyname'],
            'email' => $orgdata['companyemail'],
            'website' => $orgdata['website'],
            'contact1' => $orgdata['contactnumber'],
            'tin' => $orgdata['tinnumber'],
            'gst' => $orgdata['gstnumber'],
            'logo' => $orgdata['photoid'],
            'address' => $orgdata['adressdata'],
            'status' => $status,
            'modifiedby' => $modifiedby,
            'modifieddate' => $modifieddate,
        );
        DB::table('personal_info')->where('pinfo_id', '=', 1)->update($insertdata);

        Session::flash('sucmessage', "Congratulations, Information Updated!");

        return Redirect::back();
    }

}
