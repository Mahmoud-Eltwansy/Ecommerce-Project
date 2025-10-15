@extends('layouts.dashboard')

@section('title', 'Products')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Products</li>

@endsection
@section('content')

    {{-- New Category & Trash Btns --}}
    <div class="d-flex justify-content-start">
        <div class="mb-5 ">
            <a href="{{ route('dashboard.products.create') }}" class="btn btn-outline-primary">Create</a>
        </div>

        <div class="mb-5 mx-4">
            <a href="{{ route('dashboard.products.trash') }}" class="btn btn-outline-danger">Trash Products</a>
        </div>
    </div>
    {{-- End  New Category & Trash Btns --}}


    {{-- Alerts --}}
    <x-alert type="success" />
    <x-alert type="info" />
    {{-- End Alerts --}}


    {{-- Search Form --}}
    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name of the product" class="mx-2" :value="request('name')" />
        <select name="status" class="form-control mx-2">
            <option value="">All</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="draft" @selected(request('status') == 'draft')>Draft</option>
            <option value="archived" @selected(request('status') == 'archived')>Archived</option>
        </select>
        <button class="btn btn-dark mx-2">Search</button>
    </form>
    {{-- End Search Form --}}


    {{-- Products Table --}}
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Store</th>
                <th>Status</th>
                <th>Created At</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td><img src="{{ asset('storage/' . $product->image) }}" alt="" height="50px"></td>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td><a
                            href="{{ route('dashboard.categories.show', $product->category->id) }}">{{ $product->category->name }}</a>
                    </td>
                    <td>{{ $product->store->name }}</td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->created_at }}</td>
                    <td>
                        <a href="{{ route('dashboard.products.edit', [$product->id]) }}"
                            class="btn btn-sm btn-success">Edit</a>

                    </td>
                    <td>
                        <form action="{{ route('dashboard.products.destroy', [$product->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No Products defined.</td>
                </tr>
            @endforelse

        </tbody>
    </table>
    {{ $products->withQueryString()->links() }}
    {{-- End Products Table --}}

@endsection
