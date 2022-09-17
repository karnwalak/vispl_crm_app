<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\LeadService;

class LeadController extends Controller
{
    public function getLeads(Request $request) {
        return response()->json([
            'status' => true,
            'data' => collect(LeadService::getLeads($request))->values(),
        ]);
    }
}
