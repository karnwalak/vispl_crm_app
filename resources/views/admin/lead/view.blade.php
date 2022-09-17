<!-- Modal -->
<div id="myModal{{ $lead->id }}" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header d-inline">
                <h4 class="modal-title d-flex justify-content-between">
                    <span>{{ $lead->first_name.' '.$lead->last_name }}</span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                </h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr> <th>Lead Date</th> <td> {{ $lead->lead_date ? date('d/m/Y h:i a', strtotime($lead->lead_date)) : "N/A" }}</td> </tr>
                        <tr> <th>Salutation</th> <td>{{ $lead->salutation ?? "N/A" }}</td> </tr>
                        <tr> <th>First Name</th> <td>{{ $lead->first_name ?? "N/A" }}</td> </tr>
                        <tr> <th>Last Name</th> <td>{{ $lead->last_name ?? "N/A" }}</td> </tr>
                        <tr> <th>Mobile Number/Landline Number</th> <td>{{ $lead->contact_number ?? "N/A" }}</td> </tr>
                        <tr> <th>User Email</th> <td>{{ $lead->email_id ?? "N/A" }}</td> </tr>
                        <tr> <th>Compittor Name</th> <td>{{ $lead->competitor_name ?? "N/A" }}</td> </tr>
                        <tr> <th>Organisation</th> <td>{{ $lead->organisation ?? "N/A" }}</td> </tr>
                        <tr> <th>Account Type</th> <td>{{ $lead->account_type ?? "N/A" }}</td> </tr>
                        <tr> <th>Industry Type</th> <td>{{ $lead->industry_type ?? "N/A" }}</td> </tr>
                        <tr> <th>Designation 222</th> <td>{{ $lead->designation_name ?? "N/A" }}</td> </tr>
                        <tr> <th>User Department</th> <td>{{ $lead->department ?? "N/A"}}</td> </tr>
                        <tr> <th>Sale Person</th> <td>{{ $lead->sales_person ?? "N/A" }}</td> </tr>
                        <tr> <th>Lead Details</th> <td>{{ $lead->lead_details ?? "N/A" }}</td> </tr>
                        <tr> <th>Source Type</th> <td>{{ $lead->source_type ?? "N/A" }}</td> </tr>
                        <tr> <th>Source Value</th> <td>{{ $lead->source_value ?? "N/A" }}</td> </tr>
                        <tr> <th>Product</th> <td>{{ $lead->product_type ?? "N/A" }}</td> </tr>
                        <tr> <th>Sub Product </th> <td>{{ $lead->subproduct_type ?? "N/A" }}</td> </tr>
                        <tr> <th>Lead Type</th> <td>@if($lead->lead_type == 1) New @elseif($lead->lead_status == 2) Existing @else N/A @endif</td> </tr>
                        <tr> <th>Payment Type</th> <td>{{ $lead->payment_type ?? "N/A" }}</td> </tr>
                        <tr> <th>Chennel Partner</th> <td>{{ $lead->channel_partner ?? "N/A" }}</td> </tr>
                        <tr> <th>Chennel Competitor</th> <td>{{ $lead->channel_competitor ?? "N/A" }}</td> </tr>
                        <tr> <th>Lead Status</th> <td>@if($lead->lead_status == 1) Hot @elseif($lead->lead_status == 2) Warm @elseif($lead->lead_status == 3) Cold @else N/A @endif</td> </tr>
                        <tr> <th>Address</th> <td>{{ $lead->address ?? "N/A" }}</td> </tr>
                        <tr> <th>State</th> <td>{{ $lead->state ?? "N/A" }}</td> </tr>
                        <tr> <th>City</th> <td>{{ $lead->city ?? "N/A" }}</td> </tr>
                        <tr> <th>Pincode</th> <td>{{ $lead->pincode ?? "N/A" }}</td> </tr>
                        <tr> <th>Created Time</th> <td>{{ $lead->created_time ?? "N/A" }}</td> </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>