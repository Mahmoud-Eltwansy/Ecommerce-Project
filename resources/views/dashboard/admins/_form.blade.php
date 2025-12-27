<div class="form-group">
    <x-form.input label="Name" class="form-control-lg" name="name" :value="old('name', $admin->name)" />
</div>
<div class="form-group">
    <x-form.input label="Username" name="username" :value="old('username', $admin->username)" />
</div>
<div class="form-group">
    <x-form.input label="Email" type="email" name="email" :value="old('email', $admin->email)" />
</div>
<div class="form-group">
    <x-form.input label="Phone Number" name="phone_number" :value="old('phone_number', $admin->phone_number)" />
</div>

<div class="form-group">
    <x-form.input type="password" label="Password" name="password" />
    @if ($admin->exists)
        <small class="form-text text-muted">Leave password blank to keep the current password.</small>
    @endif
</div>
<div class="form-group">
    <x-form.input type="password" label="Confirm Password" name="password_confirmation" />
</div>

<fieldset>
    <legend>{{ __('Roles') }}</legend>

    @foreach ($roles as $role)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}"
                @checked(in_array($role->id, old('roles', $admin_roles)))>
            <label class="form-check-label">
                {{ $role->name }}
            </label>
        </div>
    @endforeach
</fieldset>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
</div>
