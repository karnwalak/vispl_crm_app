<?php namespace App\Service;

use App\ActivityData;
use Illuminate\Support\Facades\DB;

class ReportService {

    public static function wcrReport($reportType=null, $week_type, $duration=null, $export_type=null, $from_export=null, $to_export=null) {
        $leads = DB::table('leads')
                ->select('leads.lead_type', 'leads.remarks', 'leads.organisation', 'activities.from_date', 'activitytype.activity_name',
                    'activities.id','saleperson.name as sales_person', 'activities.accompanied_by_rh', 'cities.name AS city',
                    'activities.accompanied_by_sh','mainproduct.producttype AS product', 'activities.accompanied_by_others',
                    'activities.accompanied_by_ph', 'leads.first_name', 'leads.last_name', 'leads.expected_value','leads.city_id',
                    'leads.last_name','leads.contact_number', 'leads.email_id','states.name AS state','regiondata.regionname',
                    'leads.lead_status','activities.activity_details','leads.is_opportunity', 'leads.opp_status',
                    'activities.lead_status', 'activities.opportunity_status', 'activities.createdtime', 'activities.*',
                    'subproducttype.producttype as subproduct_type','leads.remarks' ,'leads.expected_date_of_closure' ,
                    'designation.designationname AS designation_name', 'leads.annual_budget',
                    DB::raw('IF(activities.link_to = \'Nolink\', \'Pre-Sales\', activities.link_to) as link_to'),
                    DB::raw('(SELECT act.followup_date FROM activities act INNER JOIN activitytype AS acttype ON acttype.id = act.activity_type_id WHERE act.leadid = activities.leadid AND act.followup_date > activities.followup_date ORDER BY act.followup_date LIMIT 0,1) AS next_date  '),
                    DB::raw('(SELECT acttype.activity_name FROM activities act INNER JOIN activitytype AS acttype ON acttype.id = act.activity_type_id WHERE act.leadid = activities.leadid AND act.followup_date > activities.followup_date ORDER BY act.followup_date LIMIT 0,1) AS next_step ')
                )
                ->join('activities', 'activities.leadid', '=', 'leads.id')
                ->leftJoin('activitytype', 'activitytype.id', '=', 'activities.activity_type_id')
                ->leftJoin('users AS saleperson', 'saleperson.id', '=', 'activities.sale_person_id')
                ->leftJoin('regiondata', 'regiondata.id', '=', 'saleperson.zone')                
                ->leftJoin('cities', 'cities.id', '=', 'leads.city_id')
                ->leftJoin('states', 'states.id', '=', 'leads.state_id')
                ->leftJoin('producttype AS mainproduct', 'mainproduct.id', '=', 'leads.product_type')
                ->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                ->leftJoin('designation', 'designation.id', '=', 'leads.designation');

        // Report Type Filter
        if($reportType == "Oppurtunity") {
            $leads = $leads->where('leads.is_opportunity', '1');
        } elseif ($reportType == "Lead") {
            $leads = $leads->where('leads.is_opportunity', '0');
        }

        // Week Type Filter
        if($week_type == "This Week") {
            $leads = $leads->whereDate('activities.from_date', '>=', date("Y-m-d", strtotime('monday', strtotime('this week'))));
            $leads = $leads->whereDate('activities.from_date', '<=', date("Y-m-d"));
        } elseif ($week_type == "Previous Week") {
            $leads = $leads->whereDate('activities.from_date', '>=', date("Y-m-d", strtotime('monday', strtotime('previous week'))));
            $leads = $leads->whereDate('activities.from_date', '<=', date("Y-m-d", strtotime('sunday', strtotime('previous week'))));
        }

        // Date Filter
        if ($export_type == 'period') {
            if($duration == "3Months") {
                $leads = $leads->whereDate('activities.from_date', '>=', date('Y-m-d', strtotime('-3 months')));
                $leads = $leads->whereDate('activities.from_date', '<=', date("Y-m-d"));
            } else if($duration == "6Months") {
                $leads = $leads->whereDate('activities.from_date', '>=', date('Y-m-d', strtotime('-6 months')));
                $leads = $leads->whereDate('activities.from_date', '<=', date("Y-m-d"));
            } else if($duration == "1Year") {
                $leads = $leads->whereDate('activities.from_date', '>=', date('Y-m-d', strtotime('-1 year')));
                $leads = $leads->whereDate('activities.from_date', '<=', date("Y-m-d"));
            }
        } elseif ($export_type == 'date') {
            // Apply date filters
            if($from_export) {
                $leads = $leads->where(function($query) use ($from_export, $to_export){
                                    $query->whereDate('from_date', '>=', $from_export);
                                    $query->whereDate('from_date', '<=', $to_export);
                                });
            }
        }

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

        $users = $users->get();
        // Get only unique sales person
        $users = $users->unique('id');
        // Get all user ids from member collection
        $userids = array();

        foreach ($users as $user) {
            $userids[] = $user->id;
        }

        if ($userids) {
            $leads->WhereIn('leads.sales_person', $userids);
        }

        //echo $leads->orderBy('activities.from_date', 'ASC')->toSQL()."<BR>"; die;
        return $result = $leads->orderBy('activities.from_date', 'ASC')->get();

    }

    public static function wapReport($reportType=null, $week_type=null, $duration=null, $export_type=null, $from_export=null, $to_export=null) {
        // Get logged in user id
        $user_id = auth()->user()->id;
        // Get logged in user role
        $user_role = auth()->user()->role;
        // Initialize sales person
        $sales_person = collect();

        $leads = DB::table('leads')
                ->select('leads.lead_type', 'leads.remarks', 'leads.organisation', 'activities.from_date', 'activitytype.activity_name',
                    'activities.id','saleperson.name as sales_person', 'activities.accompanied_by_rh', 'cities.name AS city',
                    'activities.accompanied_by_sh', 'activities.accompanied_by_others', 'leads.city_id',
                    'activities.accompanied_by_ph', 'leads.first_name', 'leads.annual_budget',
                    'leads.last_name','leads.contact_number', 'leads.email_id','states.name AS state', 'regiondata.regionname',
                    'leads.lead_status','activities.activity_details','leads.is_opportunity', 'leads.opp_status',
                    'activities.lead_status', 'activities.opportunity_status', 'activities.createdtime', 'activities.*',
                    'subproducttype.producttype as subproduct_type', 'designation.designationname AS designation_name',
                    DB::raw('IF(activities.link_to = \'Nolink\', \'Pre-Sales\', activities.link_to) as link_to')
                )
                ->join('activities', 'activities.leadid', '=', 'leads.id')
                ->leftJoin('activitytype', 'activitytype.id', '=', 'activities.activity_type_id')
                ->leftJoin('users AS saleperson', 'saleperson.id', '=', 'activities.sale_person_id')
                ->leftJoin('regiondata', 'regiondata.id', '=', 'saleperson.zone')
                ->leftJoin('cities', 'cities.id', '=', 'leads.city_id')
                ->leftJoin('states', 'states.id', '=', 'leads.state_id')
                ->leftJoin('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
                ->leftJoin('designation', 'designation.id', '=', 'leads.designation');

        // Report Type Filter
        if($reportType == "Oppurtunity") {
            $leads = $leads->where('leads.is_opportunity', '1');
        } elseif ($reportType == "Lead") {
            $leads = $leads->where('leads.is_opportunity', '0');
        }

        // Week Type Filter
        if($week_type == "This Week") {
            $leads = $leads->whereDate('activities.followup_date', '>=', date("Y-m-d", strtotime('monday', strtotime('this week'))));
            $leads = $leads->whereDate('activities.followup_date', '<=', date("Y-m-d"));
        } elseif ($week_type == "Next Week") {
            $leads = $leads->whereDate('activities.followup_date', '>=', date("Y-m-d", strtotime('monday', strtotime('next week'))));
            $leads = $leads->whereDate('activities.followup_date', '<=', date("Y-m-d", strtotime('sunday', strtotime('next week'))));
        }

        // Date Filter
        if ($export_type == 'period') {
            if($duration == "3Months") {
                $leads = $leads->whereDate('activities.followup_date', '>=', date("Y-m-d"));
                $leads = $leads->whereDate('activities.followup_date', '<=', date('Y-m-d', strtotime('+3 months')));
            } else if($duration == "6Months") {
                $leads = $leads->whereDate('activities.followup_date', '>=', date("Y-m-d"));
                $leads = $leads->whereDate('activities.followup_date', '<=', date('Y-m-d', strtotime('+6 months')));
            } else if($duration == "1Year") {
                $leads = $leads->whereDate('activities.followup_date', '>=', date("Y-m-d"));
                $leads = $leads->whereDate('activities.followup_date', '<=', date('Y-m-d', strtotime('+1 year')));
            }
        } elseif ($export_type == 'date') {
            // Apply date filters
            if($from_export) {
                $leads = $leads->where(function($query) use ($from_export, $to_export){
                                    $query->whereDate('followup_date', '>=', $from_export);
                                    $query->whereDate('followup_date', '<=', $to_export);
                                });
            }
        }

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

        $users = $users->get();
        // Get only unique sales person
        $users = $users->unique('id');
        // Get all user ids from member collection
        $userids = array();

        foreach ($users as $user) {
            $userids[] = $user->id;
        }

        if ($userids) {
            $leads->WhereIn('leads.sales_person', $userids);
        }

        return $result = $leads->orderBy('activities.followup_date', 'ASC')->get();

    }

    public static function funnelReport($reportType=null, $week_type=null, $duration=null, $export_type=null, $from_export=null, $to_export=null) {
       $leads = DB::table('leads')
               ->select('leads.*', 'departmentdata.name AS department', 'cities.name AS city', 'designation.designationname AS designation_name', 
                       'producttype.producttype as product_type', 'subproducttype.producttype as subproduct_type', 'users.name as salesperson', 
                       'sourcetype.sourcetype as source_type', 'sourcevalue.sourcevalue as source_value', 'states.name AS state', 
                       'industrytype.industrytype AS industry_type', 'regiondata.regionname', 'users.role',
                       DB::raw('CASE WHEN leads.lead_type = 1 THEN "New" WHEN leads.lead_type = 2 THEN "Existing" END AS lead_type'),
                       DB::raw('CASE WHEN leads.lead_status = 1 THEN "Hot" WHEN leads.lead_status = 2 THEN "Warm" WHEN leads.lead_status = 3 THEN "Cold" END AS lead_status'),
                       DB::raw('(select activities.followup_date from activities where activities.leadid = leads.id and activities.followup_date > CURDATE() order by activities.followup_date asc limit 0,1) as latest_date'),
                       DB::raw('(select activities.activity_details from activities where activities.leadid = leads.id and activities.followup_date > CURDATE() order by activities.followup_date asc limit 0,1) as activity_details'),
                       DB::raw('(select activities.accompanied_by_sh from activities where activities.leadid = leads.id and activities.followup_date > CURDATE() order by activities.followup_date asc limit 0,1) as accompanied_by_sh'),
                       DB::raw('(select activities.accompanied_by_ph from activities where activities.leadid = leads.id and activities.followup_date > CURDATE() order by activities.followup_date asc limit 0,1) as accompanied_by_ph'),
                       DB::raw('(select activities.accompanied_by_rh from activities where activities.leadid = leads.id and activities.followup_date > CURDATE() order by activities.followup_date asc limit 0,1) as accompanied_by_rh'),
                       DB::raw('(select activities.accompanied_by_others from activities where activities.leadid = leads.id and activities.followup_date > CURDATE() order by activities.followup_date asc limit 0,1) as accompanied_by_others'),
                       DB::raw('(select activities.followup_date from activities where activities.leadid = leads.id and activities.followup_date < CURDATE() order by activities.followup_date desc limit 0,1) as previous_date'),
                       DB::raw('(select boss.name from users as boss where boss.zone = users.zone and boss.role="Zonal Level Admin" limit 0,1) as zonal_admin'),
                       DB::raw('(select boss.name from users as boss where boss.role="Country Head" limit 0,1) as country_head'))
               ->join('departmentdata', 'departmentdata.id', '=', 'leads.department')
               ->join('cities', 'cities.id', '=', 'leads.city_id')
               ->join('states', 'states.id', '=', 'leads.state_id')
               ->join('designation', 'designation.id', '=', 'leads.designation')
               ->join('producttype', 'producttype.id', '=', 'leads.product_type')
               ->join('producttype AS subproducttype', 'subproducttype.id', '=', 'leads.sub_product')
               ->join('users', 'users.id', '=', 'leads.sales_person')               
               ->join('regiondata', 'regiondata.id', '=', 'users.zone')
               ->join('industrytype', 'industrytype.id', '=', 'leads.industry_type')
               ->leftJoin('sourcetype', 'sourcetype.id', '=', 'leads.source_type')
               ->leftJoin('sourcevalue', 'sourcevalue.id', '=', 'leads.source_value')
               ->orderBy('leads.id', 'desc');

        // Report Type Filter
        if($reportType == "Oppurtunity") {
            $leads = $leads->where('leads.is_opportunity', '1');
        } elseif ($reportType == "Lead") {
            $leads = $leads->where('leads.is_opportunity', '0');
        }

        // Date Filter
        if ($export_type == 'period') {
            if($duration == "3Months") {
                $leads = $leads->whereDate('leads.lead_date', '>=', date('Y-m-d', strtotime('-3 months')));
            } else if($duration == "6Months") {
                $leads = $leads->whereDate('leads.lead_date', '>=', date('Y-m-d', strtotime('-6 months')));
            } else if($duration == "1Year") {
                $leads = $leads->whereDate('leads.lead_date', '>=', date('Y-m-d', strtotime('-1 year')));
            }
        } elseif ($export_type == 'date') {
            // Apply date filters
            if($from_export) {
                $leads = $leads->where(function($query) use ($from_export, $to_export){
                                    $query->whereDate('leads.lead_date', '>=', $from_export);
                                    $query->whereDate('leads.lead_date', '<=', $to_export);
                                });
            }            
        }

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

        $users = $users->get();
        // Get only unique sales person
        $users = $users->unique('id');
        // Get all user ids from member collection
        $userids = array();

        foreach ($users as $user) {
            $userids[] = $user->id;
        }

        if ($userids) {
            $leads->WhereIn('leads.sales_person', $userids);
        }

        return $result = $leads->orderBy('leads.lead_date', 'ASC')->get();
    }
}


?>
