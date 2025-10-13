<div class="form-group">
    <x-form.input name="name" :value="$category->name" label="Category Name" />
</div>
<div class="form-group">
    <label for="">Parent Category</label>
    <select name="parent_id" @class([
        'form-control',
        'form-select',
        'is-invalid' => $errors->has('parent_id'),
    ])>
        <option value="">Primary Category</option>
        @foreach ($parents as $parent)
            <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
        @endforeach
    </select>
    @error('parent_id')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <x-form.textarea name="description" :value="$category->description" label="Description" />
</div>

<div class="form-group">
    <label for="">Image</label>
    <x-form.input type="file" name="image" accept="image/*" />

    @if ($category->image)
        <img src="{{ asset('storage/' . $category->image) }}" alt="" height="50px">
    @endif

</div>

<div class="form-group">

    <div>
        <x-form.radio name="status" :checked="$category->status" :options="['active' => 'Active', 'archived' => 'Archived']" label="Status" />
    </div>

</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $btnText ?? 'Save' }}</button>
</div>
