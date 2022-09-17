<table class="table table-bordered">
    <tbody>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Lead Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->lead_date ? date('d/m/Y h:i a', strtotime($lead->lead_date)) : "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Organisation:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->organisation ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Contact:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->first_name . " ".$lead->last_name }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Mobile Number/Landline Number:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->contact_number ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">User Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->email_id ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Designation:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->designation_name ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">User Department:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->department ?? "N/A"}}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Competitor Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->competitor_name ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Account Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->account_type ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Industry Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->industry_type ?? "N/A" }}</td>
        </tr>        
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Sale Person:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->sales_person ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Lead Details:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->lead_details ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Source Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->source_type ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Source Value:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->source_value ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Product:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->product_type ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Sub Product :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->subproduct_type ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Payment Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->payment_type ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Chennel Partner:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->channel_partner ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Chennel Competitor:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->channel_competitor ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Address:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->address ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">State:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->state ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">City:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->city ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Pincode:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->pincode ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Created Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->created_time ?? "N/A" }}</td>
        </tr>
    </tbody>
</table>
