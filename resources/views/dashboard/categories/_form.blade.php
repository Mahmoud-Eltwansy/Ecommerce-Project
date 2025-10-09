{{-- @if ($errors->any())
    <div class="alert alert-danger">
        <h3>Error Occured!</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

@endif --}}

<div class="form-group">
    <label for="">Category Name</label>
    <input type="text" name="name" value="{{ old('name', $category->name) }}" @class(['form-control', 'is-invalid' => $errors->has('name')])>
    @error('name')
        <div class="text-danger">{{ $message }}</div>
    @enderror
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
    <label for="">Description</label>
    <textarea name="description" @class(['form-control', 'is-invalid' => $errors->has('description')])>{{ old('description', $category->description) }}
    </textarea>
    @error('description')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="">Image</label>
    <input type="file" name="image" @class(['form-control', 'is-invalid' => $errors->has('image')]) accept="image/*" />
    @if ($category->image)
        <img src="{{ asset('storage/' . $category->image) }}" alt="" height="50px">
    @endif
    @error('image')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="">Status</label>
    <div>
        <div class="form-check ">
            <input class="form-check-input" type="radio" name="status" value="active" @checked(old('status', $category->status) == 'active')>
            <label class="form-check-label">
                Active
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="status"value="archived" @checked(old('status', $category->status) == 'archived')>
            <label class="form-check-label">
                Archived
            </label>
        </div>
    </div>
    @error('status')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $btnText ?? 'Save' }}</button>
</div>
