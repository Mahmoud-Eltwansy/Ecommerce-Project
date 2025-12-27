<div class="form-group">
    <x-form.input name="name" :value="$role->name" label="Role Name" />
</div>

<fieldset>
    <legend>{{ __('Abilities') }}</legend>
    @foreach (app('abilities') as $ability_code => $ability_name)
        <div class="row mb-2">
            <div class="col-md-6">
                {{ $ability_name }}
            </div>
            <div class="col-md-2">
                <input type="radio" name="abilities[{{ $ability_code }}]" value="allow" @checked(($role_abilities[$ability_code] ?? '') == 'allow')>
                Allow
            </div>
            <div class="col-md-2">
                <input type="radio" name="abilities[{{ $ability_code }}]" value="deny" @checked(($role_abilities[$ability_code] ?? '') == 'deny')>
                Deny
            </div>
            <div class="col-md-2">
                <input type="radio" name="abilities[{{ $ability_code }}]" value="inherit"
                    @checked(($role_abilities[$ability_code] ?? '') == 'inherit')>
                Inherit
            </div>
        </div>
    @endforeach
</fieldset>
<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $btnText ?? 'Save' }}</button>
</div>
