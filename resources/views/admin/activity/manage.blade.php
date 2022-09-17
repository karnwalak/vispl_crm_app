<!-- Manage Activity Modal -->
<div id="mangeavtivity{{ $lead->id }}" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content p-3">
            <div class="modal-header">
                <h4 class="modal-title">Manage Activity</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ SITEURL }}admin/savemanageactivity" class="pb-3 form-fields">
                    <div class="row">
                        <div class="input-group" style="  margin-bottom:10px;  ">
                            <label style="display:block; margin-bottom:10px; width:100%;"> Follow Up </label>

                            <label class="form-control " >
                                <input type="radio" name="leadfollow"  class="leadrespooenu1" id="leadfollow1" value="3">
                                Closed
                            </label>
                            <label class="form-control " >
                                <input type="radio" name="leadfollow"  class="leadrespooenu2" checked id="leadfollow2" value="2">
                                Reschedule
                            </label>
                            @if(auth()->user()->role != "Member")
                            <label class="form-control " >
                                <input type="radio" name="leadfollow" id="leadfollow3" class="leadrespooenu3" value="1">
                                Transfer
                            </label>
                            @endif
                        </div>
                    </div>
                    <div class="row comment_area" style="display:none;margin-bottom:10px;" >
                        <label style="margin-bottom:10px; width:100%;">
                            <textarea name="narretiondata" id="narretiondata" class="form-control " placeholder="Add Comment"></textarea>
                        </label>
                    </div>
                    <div class="row transfer-to mb-2" style="display: none;">
                        <div class="input-group">
                            <select id="saleperson" name="sale_person_transfer" class="custom-select form-control">
                                @if(auth()->user()->role != "Member")<option value="">Select Sales Person</option>@endif
                                <?php foreach ($sales_person as $person) { ?>
                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row resheduled" style="display: block">
                        <div class="row">
                            <div class="form-group remove-botttom-space col-6">
                                <div class="input-group">
                                    <label for="inputfirst_name">Activity Type
                                        <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                                    </label>
                                    <div class="input-group">
                                        <select id="activitytype" required class="custom-select form-control" name="activity_type_id">
                                            <option value="">Select Activity Type</option>
                                            <?php foreach ($activity_types as $type) { ?>
                                                <option value="{{ $type->id }}" >{{ $type->activity_name }} </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group remove-botttom-space col-6">
                                <div class="input-group">
                                    <label for="inputfirst_name">Next Follow-up Date
                                        <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                                    </label>
                                    <div class="input-group">
                                        <input type="date" required class="form-control " id="followup_date" name="followup_date" placeholder="Followup Date">
                                        <!--<input type="time" class="form-control " id="formtime" name="from_time" placeholder="Form Time">-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group remove-botttom-space col-12">
                                <label for="inputfirst_name">Activity Description
                                    <span style="display:inline-block; font-size:16px; color:#f12605 "> * </span>
                                </label>
                                <div class="input-group">
                                    <textarea type="text" required class="form-control " id="activity_details" name="activity_details"  placeholder="Activity Description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <input type="submit" id="submotactinvet" class="btn btn-primary" value="Update">
                    </div>
                    <input type="hidden" name="userid" id="userid" value="{{ $user_id  }}">
                    <input type="hidden" name="leadid" id="leadid" value="{{ $lead->id  }}">
                    <input type="hidden" name="sale_person_id" id="sale_person_id" value="{{ $lead->sales_person  }}">
                    {!! csrf_field() !!}
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>