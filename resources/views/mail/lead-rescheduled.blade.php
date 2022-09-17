<table class="table table-bordered">
    <tbody>
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
            <th style="text-align:right;width:30%;vertical-align: top;">Activity Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->activity_name ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Activity Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ date('d-m-Y', strtotime($lead->from_date)) ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Follow Up Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ date('d-m-Y', strtotime($lead->followup_date)) ?? "N/A" }}</td>
        </tr>
        <tr>
            <th style="text-align:right;width:30%;vertical-align: top;">Remarks:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <td style="width:70%;">{{ $lead->activity_details ?? "N/A" }}</td>
        </tr>
    </tbody>
</table>
