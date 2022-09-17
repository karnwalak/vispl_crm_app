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
use Illuminate\Support\Facades\Date;
use Session;
use Response;
use App\Jobs\OpportunityCreatedJob;
use App\Jobs\OppurtunityStatusUpdateJob;

class OpportunityController extends Controller {

    public function saveOpportunity(Request $request) {
        $savedata = $request->all();

        // Start database transactions
        DB::beginTransaction();

        try {
            // save data in leads table
            $opportuninty_data = array(
                'is_opportunity' => '1',
                'remarks' => $savedata["remarks"],
                'opp_status' => $savedata["opp_status"],
                'annual_budget' => $savedata["annual_budget"],
                'opportunity_creation_date' => date('Y-m-d H:i:s')
            );

            if (isset($savedata["expected_date_of_closure"])) {
                $opportuninty_data['expected_date_of_closure'] = $savedata["expected_date_of_closure"];
            }

            if (isset($savedata["order_closed_date"])) {
                $opportuninty_data['order_closed_date'] = $savedata["order_closed_date"];
            }

            if (isset($savedata["expected_value"])) {
                $opportuninty_data['expected_value'] = $savedata["expected_value"];
            }

            if (isset($savedata["closed_value"])) {
                $opportuninty_data['closed_value'] = $savedata["closed_value"];
            }

            // Opportunity closure date updated as per discussion with Gautam
            if ($savedata["opp_status"] == 'Win' || $savedata["opp_status"] == 'Lost' || $savedata["opp_status"] == 'Shelved') {
                $opportuninty_data['opportunity_closure_date'] = date('Y-m-d H:i:s');
            }

            DB::table('leads')->where('id', $savedata['convertid'])->update($opportuninty_data);

            $lead = DB::table('leads')->where('id', '=', $savedata['convertid'])->orderBy('id', 'desc')->first();

            if ($lead->lead_status == 1) {
                $lead_status = 'Hot';
            } elseif ($lead->lead_status == 2) {
                $lead_status = 'Warm';
            } elseif ($lead->lead_status == 3) {
                $lead_status = 'Cold';
            }

            $savedata['leadid'] = $savedata['convertid'];
            $savedata['activity_details'] = $savedata['remarks'];
            $savedata['createdby'] = auth()->user()->id;
            $savedata['createdtime'] = date("Y-m-d H:i:s");
            $savedata['from_date'] = date("Y-m-d H:i:s");
            $savedata['activity_type_id'] = 9;
            $savedata['sale_person_id'] = $lead->sales_person;
            $savedata['link_to'] = 'Existing';
            $savedata['lead_status'] = $lead_status;
            $savedata['opportunity_status'] = $savedata["opp_status"];
            // Add data in activity data
            ActivityData::create($savedata);

            Session::flash('sucmessage', "Lead: ".$lead->organisation." has been converted into opportunity successfully");

            // Commit database transaction
            DB::commit();

            // Send Email
            dispatch(new OpportunityCreatedJob(LeadData::where('id', $savedata['convertid'])->first()));

        } catch (\Exception $e) {
            DB::rollback(); // something went wrong
            // Set success message now
            Session::flash('failure', "Lead conversion into opportunity failed. Please contact administrator!");
        }

        return Redirect::back();
    }

    public function index(Request $request) {
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
        // Get EDCV Filter
        $edcv_filter = $request->get('edcv-filter');

        // If logged in user is Admin then redirect to dashboard
        if ($user_role == 'Admin') {
            return redirect()->route('dashboard');
        } else {
            // If logged in user is Country Head then get all leads
            if ($user_role == 'Country Head') {
                //$leads = DB::table('leads')->orderBy('id', 'DESC')->get();
                $leads = DB::table('leads')
                        ->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city', 'designation.designationname AS designation_name', 'producttype.producttype as product_type', 'subproducttype.producttype as subproduct_type', 'users.name as sales_person', 'sourcetype.sourcetype as source_type', 'sourcevalue.sourcevalue as source_value', 'states.name AS state', 'industrytype.industrytype AS industry_type')
                        ->join('departmentdata', 'departmentdata.id', '=', 'leads.department')
                        ->join('cities', 'cities.id', '=', 'leads.city_id')
                        ->join('states', 'states.id', '=', 'leads.state_id')
                        ->join('designation', 'designation.id', '=', 'leads.designation')
                        ->join('producttype', 'producttype.id', '=', 'leads.product_type')
                        ->join('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                        ->join('users', 'users.id', '=', 'leads.sales_person')
                        ->join('industrytype', 'industrytype.id', '=', 'leads.industry_type')
                        ->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')
                        ->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value');

                // Apply date filters
                if ($from_date) {
                    $leads = $leads->where(function($query) use ($from_date, $to_date) {
                        $query->whereDate('lead_date', '>=', $from_date);
                        $query->whereDate('lead_date', '<=', $to_date);
                    });
                }
                // Apply lead status filter
                if ($request->get('status-filter')) {
                    $status = $request->get('status-filter');
                    $leads = $leads->where(function($query) use ($status) {
                        $query->where('opp_status', '=', $status);
                    });
                }
                // Apply EDCV filter
                if ($edcv_filter) {
                    if ($edcv_filter == 'expected_date_of_closure') {
                        if ($from_date) {
                            $leads = $leads->where(function($query) use ($from_date, $to_date) {
                                $query->whereDate('expected_date_of_closure', '>=', $from_date);
                            });
                        }
                        if ($to_date) {
                            $leads = $leads->where(function($query) use ($from_date, $to_date) {
                                $query->whereDate('expected_date_of_closure', '<=', $to_date);
                            });
                        }
                    } elseif ($edcv_filter == 'order_closed_date') {
                        if ($from_date) {
                            $leads = $leads->where(function($query) use ($from_date, $to_date) {
                                $query->whereDate('order_closed_date', '>=', $from_date);
                            });
                        }
                        if ($to_date) {
                            $leads = $leads->where(function($query) use ($from_date, $to_date) {
                                $query->whereDate('order_closed_date', '<=', $to_date);
                            });
                        }
                    }
                }

                $leads = $leads->where('is_opportunity', '=', '1')->orderBy('leads.id', 'DESC')->get();
            } else {
                // Get leads of logged in user
                $own_leads = DB::table('leads')
                            ->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city',
                            'designation.designationname AS designation_name', 'producttype.producttype as product_type',
                            'subproducttype.producttype as subproduct_type','users.name as sales_person',
                            'sourcetype.sourcetype as source_type','sourcevalue.sourcevalue as source_value',
                            'states.name AS state','industrytype.industrytype AS industry_type')
                            ->join('departmentdata', 'departmentdata.id', '=', 'leads.department')
                            ->join('cities', 'cities.id', '=', 'leads.city_id')
                            ->join('states', 'states.id', '=', 'leads.state_id')
                            ->join('designation', 'designation.id', '=', 'leads.designation')
                            ->join('producttype', 'producttype.id', '=', 'leads.product_type')
                            ->join('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                            ->join('users', 'users.id', '=', 'leads.sales_person')
                            ->join('industrytype', 'industrytype.id', '=', 'leads.industry_type')
                            ->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')
                            ->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value')
                            ->where('leads.sales_person', '=', $user_id)
                            ->orderBy('leads.id', 'DESC');

                // Apply date filters
                if ($request->get('from-search')) {
                    $own_leads = $own_leads->where(function($query) use ($from_date, $to_date) {
                        $query->whereDate('lead_date', '>=', $from_date);
                        $query->whereDate('lead_date', '<=', $to_date);
                    });
                }
                // Apply lead status filter
                if ($request->get('status-filter')) {
                    $status = $request->get('status-filter');
                    $own_leads = $own_leads->where(function($query) use ($status) {
                        $query->where('opp_status', '=', $status);
                    });
                }
                // Apply EDCV filter
                if ($edcv_filter) {
                    if ($edcv_filter == 'expected_date_of_closure') {
                        if ($from_date) {
                            $own_leads = $own_leads->where(function($query) use ($from_date, $to_date) {
                                $query->whereDate('expected_date_of_closure', '>=', $from_date);
                            });
                        }
                        if ($to_date) {
                            $own_leads = $own_leads->where(function($query) use ($from_date, $to_date) {
                                $query->whereDate('expected_date_of_closure', '<=', $to_date);
                            });
                        }
                    } elseif ($edcv_filter == 'order_closed_date') {
                        if ($from_date) {
                            $own_leads = $own_leads->where(function($query) use ($from_date, $to_date) {
                                $query->whereDate('order_closed_date', '>=', $from_date);
                            });
                        }
                        if ($to_date) {
                            $own_leads = $own_leads->where(function($query) use ($from_date, $to_date) {
                                $query->whereDate('order_closed_date', '<=', $to_date);
                            });
                        }
                    }
                }

                $leads = $own_leads = $own_leads->where('is_opportunity', '=', '1')->get();

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
                        'subproducttype.producttype as subproduct_type','users.name as sales_person',
                        'sourcetype.sourcetype as source_type','sourcevalue.sourcevalue as source_value',
                        'states.name AS state','industrytype.industrytype AS industry_type')
                        ->join('departmentdata', 'departmentdata.id', '=', 'leads.department')
                        ->join('cities', 'cities.id', '=', 'leads.city_id')
                        ->join('states', 'states.id', '=', 'leads.state_id')
                        ->join('designation', 'designation.id', '=', 'leads.designation')
                        ->join('producttype', 'producttype.id', '=', 'leads.product_type')
                        ->join('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                        ->join('users', 'users.id', '=', 'leads.sales_person')
                        ->join('industrytype', 'industrytype.id', '=', 'leads.industry_type')
                        ->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')
                        ->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value')
                        ->whereIn('leads.sales_person', $userids);

                    // Apply date filters
                    if($request->get('from-search')) {
                        $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date){
                                            $query->whereDate('lead_date', '>=', $from_date);
                                            $query->whereDate('lead_date', '<=', $to_date);
                                        });
                    }
                    // Apply lead status filter
                    if ($request->get('status-filter')) {
                        $status = $request->get('status-filter');
                        $employee_leads = $employee_leads->where(function($query) use ($status) {
                            $query->where('opp_status', '=', $status);
                        });
                    }

                    // Apply EDCV filter
                    if ($edcv_filter) {
                        if ($edcv_filter == 'expected_date_of_closure') {
                            if ($from_date) {
                                $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date) {
                                    $query->whereDate('expected_date_of_closure', '>=', $from_date);
                                });
                            }
                            if ($to_date) {
                                $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date) {
                                    $query->whereDate('expected_date_of_closure', '<=', $to_date);
                                });
                            }
                        } elseif ($edcv_filter == 'order_closed_date') {
                            if ($from_date) {
                                $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date) {
                                    $query->whereDate('order_closed_date', '>=', $from_date);
                                });
                            }
                            if ($to_date) {
                                $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date) {
                                    $query->whereDate('order_closed_date', '<=', $to_date);
                                });
                            }
                        }
                    }

                    $employee_leads = $employee_leads->where('is_opportunity', '=', '1')->orderBy('leads.id', 'DESC')->get();
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
                            ->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city', 'designation.designationname AS designation_name', 'producttype.producttype as product_type', 'subproducttype.producttype as subproduct_type', 'users.name as sales_person', 'sourcetype.sourcetype as source_type', 'sourcevalue.sourcevalue as source_value', 'states.name AS state', 'industrytype.industrytype AS industry_type')
                            ->join('departmentdata', 'departmentdata.id', '=', 'leads.department')
                            ->join('cities', 'cities.id', '=', 'leads.city_id')
                            ->join('states', 'states.id', '=', 'leads.state_id')
                            ->join('designation', 'designation.id', '=', 'leads.designation')
                            ->join('producttype', 'producttype.id', '=', 'leads.product_type')
                            ->join('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                            ->join('users', 'users.id', '=', 'leads.sales_person')
                            ->join('industrytype', 'industrytype.id', '=', 'leads.industry_type')
                            ->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')
                            ->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value')
                            ->whereIn('leads.sales_person', $userids);

                    // Apply date filters
                    if ($request->get('from-search')) {
                        $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date) {
                            $query->whereDate('lead_date', '>=', $from_date);
                            $query->whereDate('lead_date', '<=', $to_date);
                        });
                    }
                    // Apply lead status filter
                    if ($request->get('status-filter')) {
                        $status = $request->get('status-filter');
                        $employee_leads = $employee_leads->where(function($query) use ($status) {
                            $query->where('opp_status', '=', $status);
                        });
                    }
                    // Apply EDCV filter
                    if ($edcv_filter) {
                        if ($edcv_filter == 'expected_date_of_closure') {
                            if ($from_date) {
                                $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date) {
                                    $query->whereDate('expected_date_of_closure', '>=', $from_date);
                                });
                            }
                            if ($to_date) {
                                $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date) {
                                    $query->whereDate('expected_date_of_closure', '<=', $to_date);
                                });
                            }
                        } elseif ($edcv_filter == 'order_closed_date') {
                            if ($from_date) {
                                $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date) {
                                    $query->whereDate('order_closed_date', '>=', $from_date);
                                });
                            }
                            if ($to_date) {
                                $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date) {
                                    $query->whereDate('order_closed_date', '<=', $to_date);
                                });
                            }
                        }
                    }

                    $employee_leads = $employee_leads->where('is_opportunity', '=', '1')->orderBy('leads.id', 'DESC')->get();
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
            if ($user_role != "Member") {
                if ($user_role == 'Country Head') {
                    $sales_person = DB::table('users')->whereIn('role', array('Zonal Level Admin', 'State Level Admin', 'Member'))->orderBy('name', 'ASC')->get();
                } elseif ($user_role == 'Zonal Level Admin') {
                    foreach ($user_state as $key => $state) {
                        $person = DB::table('users')
                                ->whereRaw('FIND_IN_SET("' . $state . '",users.stateid)')
                                ->orderBy('name', 'ASC')
                                ->get();
                        $sales_person = $sales_person->merge($person);
                        $sales_person->all();
                    }
                    // Get only unique sales person
                    $sales_person = $sales_person->unique('id');
                } elseif ($user_role == 'State Level Admin') {
                    foreach ($user_state as $key => $state) {
                        $person = DB::table('users')
                                ->whereRaw('FIND_IN_SET("' . $state . '",users.stateid)')
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
            }
            // Get source types
            $source_types = DB::table('sourcetype')->orderBy('sourcetype', 'ASC')->get();
            // Get source values
            $source_values = DB::table('sourcevalue')->orderBy('sourcevalue', 'ASC')->get();
            // Get product types
            $product_types = DB::table('producttype')->where('prolevel', 1)->orderBy('producttype', 'ASC')->get();
            // Get states
            $states = $sataedata = DB::table('states')->where('country_id', 101)->orderBy('name', 'ASC')->get();

            return view('admin.opportunity.index')->with(array(
                        'leads' => $leads, 'userid' => $user_id, 'account_types' => $account_types, 'industry_types' => $industry_types,
                        'designations' => $designations, 'departments' => $departments, 'sales_person' => $sales_person,
                        'source_types' => $source_types, 'source_values' => $source_values, 'product_types' => $product_types,
                        'states' => $states));
        }
    }

    public function updateOpportunity(Request $request) {
        $LeadData = LeadData::where('id', $request->id)->first();
        $LeadData->rm_remarks = $request->rm_remarks;
        $LeadData->opp_status = $request->opp_status;
        $LeadData->order_closed_date = $request->order_closed_date;
        $LeadData->closed_value = $request->closed_value;

        // Opportunity closure date updated as per discussion with Gautam
        if ($request->opp_status == 'Win' || $request->opp_status == 'Lost' || $request->opp_status == 'Shelved') {
            $LeadData->opportunity_closure_date = date('Y-m-d H:i:s');
        }

        $LeadData->save();

        if ($LeadData->lead_status == 1) {
            $lead_status = 'Hot';
        } elseif ($LeadData->lead_status == 2) {
            $lead_status = 'Warm';
        } elseif ($LeadData->lead_status == 3) {
            $lead_status = 'Cold';
        }

        $Activity = new ActivityData();
        $Activity->leadid = $request->id;
        $Activity->activity_details = $request->rm_remarks;
        $Activity->activity_type_id = 8;
        $Activity->createdby = auth()->user()->id;
        $Activity->sale_person_id = $LeadData->sales_person;
        $Activity->createdtime = date("Y-m-d H:i:s");
        $Activity->from_date = date("Y-m-d H:i:s");
        $Activity->link_to = 'Existing';
        $Activity->lead_status = $lead_status;
        $Activity->opportunity_status = $request->opp_status;
        $Activity->save();

        if($request->opp_status == 'Win' || $request->opp_status == 'Lost') {
            // Send Email
            dispatch(new OppurtunityStatusUpdateJob(LeadData::where('id', $request->id)->first()));

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
                            ->where('leads.id', '=', $request->id)
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

            // Send SMS
            $sms_content = "Lead Status, Client:".$lead->first_name." ".$lead->last_name. ",Company:".$lead->organisation.",Mobile:".$lead->contact_number.",City/State:".$lead->city."/".$lead->state. ",Product:".$lead->product_type.",Date:". date('d/m/Y', strtotime($lead->lead_date)) .",Time:". date('h:i a', strtotime($lead->lead_date)) .",KAM:".$lead->salesperson.",Lead Attended On:".date('d/m/Y', strtotime($lead->lead_date)).",KAM Remarks:".$lead->remarks.",Closure Status:".$lead->opp_status.",Expected Value:".($lead->expected_value/100000)." Lakhs".", Link:".$lead_type."";

            // Send SMS to Sales Person
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://pgapi.vispl.in/fe/api/v1/send?");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "username=smartrpg.trans&password=1zPcv&unicode=true&from=VSPCRM&to=".$lead->mobileno."&dltContentId=1307163766272314509&text=".$sms_content);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close ($ch);

            // Send SMS to Regional Head
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://pgapi.vispl.in/fe/api/v1/send?");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "username=smartrpg.trans&password=1zPcv&unicode=true&from=VSPCRM&to=".$lead->zonal_mobileno."&dltContentId=1307163766227070502&text=".$sms_content);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close ($ch);

            // Send SMS to Priyanka
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://pgapi.vispl.in/fe/api/v1/send?");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "username=smartrpg.trans&password=1zPcv&unicode=true&from=VSPCRM&to=9318499948&dltContentId=1307163766227070502&text=".$sms_content);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close ($ch);
            
        }

        Session::flash('sucmessage', "Opportunity updated successfuly.");
        return redirect()->back();
    }

    public function deleteOpportunity(Request $request) {
        $LeadData = LeadData::where('id', $request->id)->first();
        $opp_status = $LeadData->opp_status;
        $LeadData->is_opportunity = '0';
        $LeadData->opp_status = null;
        $LeadData->expected_date_of_closure = null;
        $LeadData->order_closed_date = null;
        $LeadData->expected_value = null;
        $LeadData->annual_budget = null;
        $LeadData->closed_value = null;
        $LeadData->remarks = null;
        $LeadData->rm_remarks = null;
        $LeadData->opportunity_creation_date = null;
        $LeadData->opportunity_closure_date = null;
        $LeadData->save();

        $lead_status = null;
        if ($LeadData->lead_status == 1) {
            $lead_status = 'Hot';
        } elseif ($LeadData->lead_status == 2) {
            $lead_status = 'Warm';
        } elseif ($LeadData->lead_status == 3) {
            $lead_status = 'Cold';
        }

        $activitydata['leadid'] = $request->id;
        $activitydata['activity_details'] = 'Opportunity Deleted';
        $activitydata['createdby'] = auth()->user()->id;
        $activitydata['createdtime'] = date("Y-m-d H:i:s");
        $activitydata['from_date'] = date("Y-m-d H:i:s");
        $activitydata['activity_type_id'] = 11;
        $activitydata['sale_person_id'] = $LeadData->sales_person;
        $activitydata['link_to'] = 'Existing';
        $activitydata['lead_status'] = $lead_status;
        $activitydata['opportunity_status'] = $opp_status;

        // Add data in activity data
        ActivityData::create($activitydata);

        Session::flash('sucmessage', "Opportunity Deleted successfuly.");
        return redirect()->back();
    }

    public function editOpportunity(Request $request) {
        $savedata = $request->all();
        // update data in leads table
        $opportuninty_data = array(
            'remarks' => $savedata["remarks"],
            'annual_budget' => $savedata["annual_budget"]
        );

        if (isset($savedata["opp_status"])) {
            $opportuninty_data['opp_status'] = $savedata["opp_status"];
        }

        if (isset($savedata["expected_date_of_closure"])) {
            $opportuninty_data['expected_date_of_closure'] = $savedata["expected_date_of_closure"];
        }

        if (isset($savedata["order_closed_date"])) {
            $opportuninty_data['order_closed_date'] = $savedata["order_closed_date"];
        }

        if (isset($savedata["expected_value"])) {
            $opportuninty_data['expected_value'] = $savedata["expected_value"];
        }

        if (isset($savedata["closed_value"])) {
            $opportuninty_data['closed_value'] = $savedata["closed_value"];
        }
        // Opportunity closure date updated as per discussion with Gautam
        if (isset($savedata["opp_status"]) && ($savedata["opp_status"] == 'Win' ||
                  $savedata["opp_status"] == 'Lost' ||
                  $savedata["opp_status"] == 'Shelved')) {
            $opportuninty_data['opportunity_closure_date'] = date('Y-m-d H:i:s');
        }

        DB::table('leads')->where('id', $savedata['convertid'])->update($opportuninty_data);

        $lead = DB::table('leads')->where('id', '=', $savedata['convertid'])->orderBy('id', 'desc')->first();

        if (isset($savedata["opp_status"]) && $savedata["opp_status"] !=  $savedata["oldstatus"]) {

            if ($lead->lead_status == 1) {
                $lead_status = 'Hot';
            } elseif ($lead->lead_status == 2) {
                $lead_status = 'Warm';
            } elseif ($lead->lead_status == 3) {
                $lead_status = 'Cold';
            }

            $activitydata['leadid'] = $savedata['convertid'];
            $activitydata['activity_details'] = $savedata['remarks'];
            $activitydata['createdby'] = auth()->user()->id;
            $activitydata['createdtime'] = date("Y-m-d H:i:s");
            $activitydata['from_date'] = date("Y-m-d H:i:s");
            $activitydata['activity_type_id'] = 8;
            $activitydata['sale_person_id'] = $lead->sales_person;
            $activitydata['link_to'] = 'Existing';
            $activitydata['lead_status'] = $lead_status;
            $activitydata['opportunity_status'] = $savedata["opp_status"];

            // Add data in activity data
            ActivityData::create($activitydata);

            if($savedata["opp_status"] == 'Win' || $savedata["opp_status"] == 'Lost') {
                dispatch(new OppurtunityStatusUpdateJob(LeadData::where('id', $savedata['convertid'])->first()));

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
                                ->where('leads.id', '=', $savedata['convertid'])
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

                // Send SMS
                $sms_content = "Lead Status, Client:".$lead->first_name." ".$lead->last_name. ",Company:".$lead->organisation.",Mobile:".$lead->contact_number.",City/State:".$lead->city."/".$lead->state. ",Product:".$lead->product_type.",Date:". date('d/m/Y', strtotime($lead->lead_date)) .",Time:". date('h:i a', strtotime($lead->lead_date)) .",KAM:".$lead->salesperson.",Lead Attended On:".date('d/m/Y', strtotime($lead->lead_date)).",KAM Remarks:".$lead->remarks.",Closure Status:".$lead->opp_status.",Expected Value:".($lead->expected_value/100000)." Lakhs".", Link:".$lead_type."";

                // Send SMS to Sales Person
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://pgapi.vispl.in/fe/api/v1/send?");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "username=smartrpg.trans&password=1zPcv&unicode=true&from=VSPCRM&to=".$lead->mobileno."&dltContentId=1307163766272314509&text=".$sms_content);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close ($ch);

                // Send SMS to Regional Head
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://pgapi.vispl.in/fe/api/v1/send?");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "username=smartrpg.trans&password=1zPcv&unicode=true&from=VSPCRM&to=".$lead->zonal_mobileno."&dltContentId=1307163766227070502&text=".$sms_content);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close ($ch);

                // Send SMS to Priyanka
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://pgapi.vispl.in/fe/api/v1/send?");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "username=smartrpg.trans&password=1zPcv&unicode=true&from=VSPCRM&to=9318499948&dltContentId=1307163766227070502&text=".$sms_content);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close ($ch);                            
            }
        }
        Session::flash('sucmessage', $lead->organisation." details have been updated");
        return Redirect::back();
    }
}
