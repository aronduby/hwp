@php
$enabled = isset($instance) ? $instance['enabled'] : false;
$tmpId = isset($instance) ? $instance['id'] : str_random(8);
$idPrefix = 'mwpa-rankings.'.$tmpId;

$gender = isset($instance) ? $instance['settings']['gender'] : '';
$week = isset($instance) ? $instance['settings']['week'] : 0;
$name = isset($instance) ? $instance['settings']['name'] : '';
@endphp

<form>
    <fieldset>
        <div class="field-group">
            <label for="{{$idPrefix}}.gender">Gender</label>
            <select id="{{$idPrefix}}.gender" name="gender" class="form-control" required>
                <option></option>
                <option value="B" selected @if($gender === 'B') selected @endif>Boys</option>
                <option value="G" @if($gender === 'G') selected @endif>Girls</option>
            </select>
            <p class="help-block">The gender of the team, according to the URL on the MWPA site.</p>
        </div>

        <div class="field-group">
            <label for="{{$idPrefix}}.week">Current Week</label>
            <input id="{{$idPrefix}}.week" name="week" type="number" class="form-control" value="{{$week}}" required />
            <p class="help-block">What week to check. This auto-updates, but if they skip a week you can update it manually here.</p>
        </div>

        <div class="field-group">
            <label for="{{$idPrefix}}.name">Team Name</label>
            <input id="{{$idPrefix}}.name" value="Hudsonville" name="name" type="text" class="form-control" value="{{$name}}" required />
            <p class="help-block">What MWPA lists your team name as in the ranking table.</p>
        </div>

        <p>
            <button
                type="submit"
                title="@lang('misc.save')"
                class="btn btn--save"
            >
                <i class="fa fa-floppy-o"></i> @lang('misc.save')
            </button>
        </p>

    </fieldset>
</form>