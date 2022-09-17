<div id="convertModel{{ $lead->id }}" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <form method="post" action="{{ SITEURL }}admin/saveOpportunity" class="pb-3 form-fields">
        <div class="modal-content">            
                <div class="modal-header d-inline">
                    <h4 class="modal-title d-flex justify-content-between">
                        <span>Convert Lead To Opportunity</span>
                        <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                    </h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Remarks<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></td>
                                <td><textarea class="form-control" name="remarks" id="" cols="30" rows="10" required></textarea></td>
                            </tr>
                            <tr>
                                <td>Status<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></td>
                                <td>
                                    <select name="opp_status" id="opp_status" class="form-control" required>
                                        <option value="">Select Status</option>
                                        <option value="Win">Win</option>
                                        <option value="Shelved">Shelved</option>
                                        <option value="Lost">Lost</option>
                                        <option value="WIP">WIP</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Total Annual Budget</td>
                                <td class="d-flex justify-content-between">
                                    <input class="form-control" id="annual_budget" name="annual_budget" type="text" minlength="4" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')"  placeholder="Total Annual Budget in INR e.g. 50000 OR 150000" style="width: 90%"><span>INR</span>
                                </td>
                            </tr>                            
                            <tr>
                                <td>
                                    Expected Date of Closure<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span></td>
                                <td>
                                    <input class="form-control" name="expected_date_of_closure" type="date" id="expected_date_of_closure" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Order Closed date<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                                </td>
                                <td>
                                    <input class="form-control" name="order_closed_date" type="date" id="order_closed_date" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Expected Value<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                                </td>
                                <td class="d-flex justify-content-between">
                                    <input class="form-control" id="expected_value" name="expected_value" type="text" minlength="4" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')"  placeholder="Expected Value in INR e.g. 50000 OR 150000" style="width: 90%" required><span>INR</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Closed Value<span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                                </td>
                                <td class="d-flex justify-content-between">
                                    <input class="form-control" id="closed_value" type="text" name="closed_value" minlength="4" maxlength="10" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g, '')"  placeholder="Closed Value in INR e.g. 50000 OR 150000" style="width: 90%" required><span>INR</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Convert">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
                {!! csrf_field() !!}
                <input type="hidden" name="convertid" id="convertid" value="{{ $lead->id }}">
        </div>
        </form>
    </div>
</div>