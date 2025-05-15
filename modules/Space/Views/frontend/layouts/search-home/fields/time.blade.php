<?php
$allDayTimeSlots = \App\Helpers\Constants::getAllDayTimeSlot();
?>
<style>
    .select-time-dropdown {
        transform: none !important;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        top: 100% !important;
        margin-top: 0;
        right: 0;
        border-color: #dee2e6;
        width: 100%;
    }

    .select-time-dropdown .dropdown-item-row {
        display: flex;
        align-items: center;
        margin: 10px 15px;
    }

    .select-time-dropdown .dropdown-item-row .val {
        margin-right: 0;
        margin-left: auto;
    }
</style>
<div class="form-select-time">
    <div class="form-group">
        <i class="field-icon icofont-wall-clock"></i>
        <div class="form-content dropdown-toggle" data-toggle="dropdown">
            <div class="wrapper-more">
                <label>{{ $field['title'] ?? "" }}</label>
                @php
                    $timeFrom = request()->query('from_hour', 'Any');
                    $timeTo = request()->query('to_hour', 'Any');
                    if($timeFrom==null){
                        $timeFrom = 'Any';
                    }
                    if($timeTo==null){
                        $timeTo = 'Any';
                    }
                @endphp
                <div class="render">
                    <span class="from_hour">
                        <span class="multi"
                              data-html="{{__(':fromHourData')}}">{{__(':fromHourData',['fromHourData'=>$timeFrom])}}</span></span>
                    -
                    <span class="to_hour">
                        <span class="multi"
                              data-html="{{__(':toHourData')}}">{{__(':toHourData',['toHourData'=>$timeTo])}}</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="dropdown-menu select-time-dropdown">
            <div class="dropdown-item-row">
                <div class="label">{{__('From')}}</div>
                <div class="val">
                    <select name="from_hour" id="from_hour" class="form-control">
                        <option value="">From</option>
                        @foreach($allDayTimeSlots as $slot)
                            <option @if($slot==$timeFrom) selected="" @endif value="{{$slot}}">{{$slot}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="dropdown-item-row">
                <div class="label">{{__('To')}}</div>
                <div class="val">
                    <select name="to_hour" id="to_hour" class="form-control">
                        <option value="">To</option>
                        @foreach($allDayTimeSlots as $slot)
                            <option @if($slot==$timeTo) selected="" @endif value="{{$slot}}">{{$slot}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
