<?php
namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadService
{
    public static function getLeads(Request $request)
    {
        // Get logged in user id
        $user_id        = auth()->user()->id;
        // Get logged in user role
        $user_role      = auth()->user()->role;
        // Get logged in user states and convert them into an array
        $user_state     = explode(",", auth()->user()->stateid);
        // Get logged in user Zone and convert them into an array
        $user_zone      = auth()->user()->zone;
        // Initialize own leads
        $own_leads      = collect();
        // Initialize employee leads
        $employee_leads = collect();
        // Initialize sales person
        $members        = collect();
        // Initialize sales person
        $person         = collect();
        // Get from date
        $from_date      = $request->get('from-search');
        // Get to date
        $to_date        = $request->get('to-search');

        // If logged in user is Admin then redirect to dashboard
        if ($user_role == 'Admin') {
            return redirect()->route('dashboard');
        } else {
            // If logged in user is Admin then get all leads
            if ($user_role == 'Country Head') {
                //$leads = DB::table('leads')->orderBy('id', 'DESC')->get();
                $leads = DB::table('leads')->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city', 'designation.designationname AS designation_name', 'producttype.producttype as product_type', 'subproducttype.producttype as subproduct_type', 'users.name as sales_person', 'sourcetype.sourcetype as source_type', 'sourcevalue.sourcevalue as source_value', 'states.name AS state', 'industrytype.industrytype AS industry_type')->leftJoin('departmentdata', 'departmentdata.id', '=', 'leads.department')->leftJoin('cities', 'cities.id', '=', 'leads.city_id')->leftJoin('states', 'states.id', '=', 'leads.state_id')->leftJoin('designation', 'designation.id', '=', 'leads.designation')->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')->leftJoin('users', 'users.id', '=', 'leads.sales_person')->leftJoin('industrytype', 'industrytype.id', '=', 'leads.industry_type')->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value');

                // Apply date filters
                if ($from_date) {
                    $leads = $leads->where(function($query) use ($from_date, $to_date)
                    {
                        $query->whereDate('lead_date', '>=', $from_date);
                        $query->whereDate('lead_date', '<=', $to_date);
                    });
                }
                // Apply lead status filter
                if ($request->get('status-filter')) {
                    $status = $request->get('status-filter');
                    $leads = $leads->where(function($query) use ($status)
                    {
                        $query->where('lead_status', '=', $status);

                    });
                }
                // Apply product type filter
                if ($request->get('product-filter')) {
                    $status = $request->get('product-filter');
                    $leads = $leads->where(function($query) use ($status)
                    {
                        $query->where('leads.product_type', '=', $status);

                    });
                }

                $leads = $leads->orderBy('leads.id', 'DESC')->get();

            } else {
                // Get leads of logged in user
                $own_leads = DB::table('leads')->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city', 'designation.designationname AS designation_name', 'producttype.producttype as product_type', 'subproducttype.producttype as subproduct_type', 'users.name as sales_person', 'sourcetype.sourcetype as source_type', 'sourcevalue.sourcevalue as source_value', 'states.name AS state', 'industrytype.industrytype AS industry_type')->leftJoin('departmentdata', 'departmentdata.id', '=', 'leads.department')->leftJoin('cities', 'cities.id', '=', 'leads.city_id')->leftJoin('states', 'states.id', '=', 'leads.state_id')->leftJoin('designation', 'designation.id', '=', 'leads.designation')->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')->leftJoin('users', 'users.id', '=', 'leads.sales_person')->leftJoin('industrytype', 'industrytype.id', '=', 'leads.industry_type')->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value')->where('leads.sales_person', '=', $user_id)->orderBy('leads.id', 'DESC');

                // Apply date filters
                if ($from_date) {
                    $own_leads = $own_leads->where(function($query) use ($from_date, $to_date)
                    {
                        $query->whereDate('lead_date', '>=', $from_date);
                        $query->whereDate('lead_date', '<=', $to_date);
                    });
                }
                // Apply lead status filter
                if ($request->get('status-filter')) {
                    $status = $request->get('status-filter');
                    $own_leads = $own_leads->where(function($query) use ($status)
                    {
                        $query->where('lead_status', '=', $status);

                    });
                }
                // Apply product type filter
                if ($request->get('product-filter')) {
                    $status = $request->get('product-filter');
                    $own_leads = $own_leads->where(function($query) use ($status)
                    {
                        $query->where('leads.product_type', '=', $status);

                    });
                }

                $leads = $own_leads = $own_leads->get();

                if ($user_role == 'Zonal Level Admin') {
                    // Get all members of the zone level admin's state
                    foreach ($user_state as $key => $state) {
                        $person  = DB::table('users')->whereRaw('FIND_IN_SET("' . $state . '",users.stateid)')->whereIn('users.role', array(
                            'Member',
                            'State Level Admin'
                        ))->orderBy('name', 'ASC')->get();
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

                    $employee_leads = DB::table('leads')->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city', 'designation.designationname AS designation_name', 'producttype.producttype as product_type', 'subproducttype.producttype as subproduct_type', 'saleperson.name as sales_person', 'sourcetype.sourcetype as source_type', 'sourcevalue.sourcevalue as source_value', 'states.name AS state', 'industrytype.industrytype AS industry_type')->leftJoin('departmentdata', 'departmentdata.id', '=', 'leads.department')->leftJoin('cities', 'cities.id', '=', 'leads.city_id')->leftJoin('states', 'states.id', '=', 'leads.state_id')->leftJoin('designation', 'designation.id', '=', 'leads.designation')->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')->leftJoin('users AS saleperson', 'saleperson.id', '=', 'leads.sales_person')->leftJoin('industrytype', 'industrytype.id', '=', 'leads.industry_type')->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value')->whereIn('leads.sales_person', $userids);

                    // Apply date filters
                    if ($from_date) {
                        $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date)
                        {
                            $query->whereDate('lead_date', '>=', $from_date);
                            $query->whereDate('lead_date', '<=', $to_date);
                        });
                    }
                    // Apply lead status filter
                    if ($request->get('status-filter')) {
                        $status = $request->get('status-filter');
                        $employee_leads = $employee_leads->where(function($query) use ($status)
                        {
                            $query->where('lead_status', '=', $status);

                        });
                    }
                    // Apply product type filter
                    if ($request->get('product-filter')) {
                        $status = $request->get('product-filter');
                        $employee_leads = $employee_leads->where(function($query) use ($status)
                        {
                            $query->where('leads.product_type', '=', $status);

                        });
                    }

                    $employee_leads = $employee_leads->orderBy('leads.id', 'DESC')->get();
                    $own_leads      = $own_leads->merge($employee_leads);
                    $own_leads->all();

                    // Get only unique leads
                    $leads = $own_leads->unique('id');

                } elseif ($user_role == 'State Level Admin') {
                    // Get all members of the state level admin's state
                    foreach ($user_state as $key => $state) {
                        $person  = DB::table('users')->whereRaw('FIND_IN_SET("' . $state . '",users.stateid)')->whereIn('users.role', array(
                            'Member',
                            'State Level Admin'
                        ))->orderBy('name', 'ASC')->get();
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

                    $employee_leads = DB::table('leads')->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city', 'designation.designationname AS designation_name', 'producttype.producttype as product_type', 'subproducttype.producttype as subproduct_type', 'saleperson.name as sales_person', 'sourcetype.sourcetype as source_type', 'sourcevalue.sourcevalue as source_value', 'states.name AS state', 'industrytype.industrytype AS industry_type')->leftJoin('departmentdata', 'departmentdata.id', '=', 'leads.department')->leftJoin('cities', 'cities.id', '=', 'leads.city_id')->leftJoin('states', 'states.id', '=', 'leads.state_id')->leftJoin('designation', 'designation.id', '=', 'leads.designation')->leftJoin('producttype', 'producttype.id', '=', 'leads.product_type')->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')->leftJoin('users AS saleperson', 'saleperson.id', '=', 'leads.sales_person')->leftJoin('industrytype', 'industrytype.id', '=', 'leads.industry_type')->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value')->whereIn('leads.sales_person', $userids);

                    // Apply date filters
                    if ($from_date) {
                        $employee_leads = $employee_leads->where(function($query) use ($from_date, $to_date)
                        {
                            $query->where('lead_date', '>=', $from_date);
                            $query->where('lead_date', '<=', $to_date);
                        });
                    }
                    // Apply lead status filter
                    if ($request->get('status-filter')) {
                        $status = $request->get('status-filter');
                        $employee_leads = $employee_leads->where(function($query) use ($status)
                        {
                            $query->where('lead_status', '=', $status);

                        });
                    }
                    // Apply product type filter
                    if ($request->get('product-filter')) {
                        $status = $request->get('product-filter');
                        $employee_leads = $employee_leads->where(function($query) use ($status)
                        {
                            $query->where('leads.product_type', '=', $status);

                        });
                    }

                    $employee_leads = $employee_leads->orderBy('leads.id', 'DESC')->get();
                    // Merge both resultsets
                    $own_leads      = $own_leads->merge($employee_leads);
                    $own_leads->all();
                    // Get only unique leads
                    $leads = $own_leads->unique('id');
                }
            }
        }

        return $leads;
    }
}
