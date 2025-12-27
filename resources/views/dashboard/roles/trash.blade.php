@extends('layouts.dashboard')

@section('title', 'Trashed Roles')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active"><a href="{{ route('dashboard.roles.index') }}">Roles</a></li>
    <li class="breadcrumb-item active">Trash</li>

@endsection
@section('content')

    {{-- Back Btn --}}
    <div class="mb-5">
        <a href="{{ route('dashboard.roles.index') }}" class="btn btn-outline-primary">Back</a>
    </div>
    {{-- End Back Btn --}}


    {{-- Alerts --}}
    <x-alert type="success" />
    <x-alert type="info" />
    {{-- End Alerts --}}


    {{-- Search Form --}}
    <form action="{{ URL::current() }}" method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name of the role" class="mx-2" :value="request('name')" />
        <select name="status" class="form-control mx-2">
            <option value="">All</option>
            <option value="active" @selected(request('status') == 'active')>Active</option>
            <option value="archived" @selected(request('status') == 'archived')>Archived</option>
        </select>
        <button class="btn btn-dark mx-2">Search</button>
    </form>
    {{-- End Search Form --}}


    {{-- Trashed Roles Table --}}
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Deleted At</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr>
                    <td><img src="{{ asset('storage/' . $role->image) }}" alt="" height="50px"></td>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->status }}</td>
                    <td>{{ $role->deleted_at }}</td>
                    <td>
                        <form action="{{ route('dashboard.roles.restore', [$role->id]) }}" method="post">
                            @csrf
                            @method('put')
                            <button type="submit" class="btn btn-sm btn-success">Restore</button>
                        </form>

                    </td>
                    <td>
                        <form action="{{ route('dashboard.roles.force-delete', [$role->id]) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-danger">Force Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No Roles defined.</td>
                </tr>
            @endforelse

        </tbody>
    </table>
    {{ $roles->withQueryString()->links() }}
    {{-- End Trashed Roles Table --}}

@endsection
