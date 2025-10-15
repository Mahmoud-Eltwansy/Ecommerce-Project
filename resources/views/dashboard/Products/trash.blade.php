@extends('layouts.dashboard')

@section('title', 'Trashed Products')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active"><a href="{{ route('dashboard.products.index') }}">Products</a></li>
    <li class="breadcrumb-item active">Trash</li>

@endsection
@section('content')

    {{-- Back Btn --}}
    <div class="mb-5">
        <a href="{{ route('dashboard.products.index') }}" class="btn btn-outline-primary">Back</a>
    </div>
    {{-- End Back Btn --}}


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


    {{-- Trashed Products Table --}}
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Store</th>
                <th>Price</th>
                <th>Status</th>
                <th>Deleted At</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td><img src="{{ asset('storage/' . $product->image) }}" alt="" height="50px"></td>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category_id }}</td>
                    <td>{{ $product->store_id }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->deleted_at }}</td>
                    <td>
                        <form action="{{ route('dashboard.products.restore', [$product->id]) }}" method="post">
                            @csrf
                            @method('put')
                            <button type="submit" class="btn btn-sm btn-success">Restore</button>
                        </form>

                    </td>
                    <td>
                        <form action="{{ route('dashboard.products.force-delete', [$product->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-danger">Force Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No Products defined.</td>
                </tr>
            @endforelse

        </tbody>
    </table>
    {{ $products->withQueryString()->links() }}
    {{-- End Trashed Products Table --}}

@endsection
