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
            <th style="text-align:right;width:30%;vertical-align: top;">Remarks:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->remarks ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Status:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->opp_status ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Total Annual Budget:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->annual_budget ? ($lead->annual_budget/100000)." Lakhs" : "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Expected Date of Closure:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->expected_date_of_closure ? date('d/m/Y',strtotime($lead->expected_date_of_closure)) : "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Expected Value:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->expected_value ? ($lead->expected_value/100000)." Lakhs": "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Order Closed date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->order_closed_date ? date('d/m/Y',strtotime($lead->order_closed_date)) : "N/A" }}</td>
        </tr>        
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Closed Value:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->closed_value ? ($lead->closed_value/100000)." Lakhs": "N/A" }}</td>
        </tr>
    </tbody>
</table>
