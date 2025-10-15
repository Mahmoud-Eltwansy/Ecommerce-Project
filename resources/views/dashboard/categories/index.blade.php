@extends('layouts.dashboard')

@section('title', 'Categories')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>

@endsection
@section('content')

    {{-- New Category Btn --}}
    <div class="d-flex justify-content-start">
        <div class="mb-5 ">
            <a href="{{ route('dashboard.categories.create') }}" class="btn btn-outline-primary">Create</a>
        </div>

        <div class="mb-5 mx-4">
            <a href="{{ route('dashboard.categories.trash') }}" class="btn btn-outline-danger">Trash Categories</a>
        </div>
    </div>
    {{-- End Category Btn --}}


    {{-- Alerts --}}
    <x-alert type="success" />
    <x-alert type="info" />
    {{-- End Alerts --}}


    {{-- Search Form --}}
    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name of the category" class="mx-2" :value="request('name')" />
        <select name="status" class="form-control mx-2">
            <option value="">All</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="archived" @selected(request('status') == 'archived')>Archived</option>
        </select>
        <button class="btn btn-dark mx-2">Search</button>
    </form>
    {{-- End Search Form --}}


    {{-- Categories Table --}}
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Category Name</th>
                <th>Parent</th>
                <th>Num Of Products</th>
                <th>Status</th>
                <th>Created At</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td><img src="{{ asset('storage/' . $category->image) }}" alt="" height="50px"></td>
                    <td>{{ $category->id }}</td>
                    <td><a href="{{ route('dashboard.categories.show', $category->id) }}">{{ $category->name }}</a></td>
                    <td>{{ $category->parent->name }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td>{{ $category->status }}</td>
                    <td>{{ $category->created_at }}</td>
                    <td>
                        <a href="{{ route('dashboard.categories.edit', [$category->id]) }}"
                            class="btn btn-sm btn-success">Edit</a>

                    </td>
                    <td>
                        <form action="{{ route('dashboard.categories.destroy', [$category->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No Categories defined.</td>
                </tr>
            @endforelse

        </tbody>
    </table>
    {{ $categories->withQueryString()->links() }}
    {{-- End Categories Table --}}

@endsection
