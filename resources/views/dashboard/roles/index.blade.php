@extends('layouts.dashboard')

@section('title', 'Roles')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Roles</li>

@endsection
@section('content')

    {{-- New Role Btn --}}
    <div class="d-flex justify-content-start">

        <div class="mb-5">
            @can('create', App\Models\Role::class)
                <a href="{{ route('dashboard.roles.create') }}" class="btn btn-outline-primary">Create</a>
            @endcan
        </div>
    </div>
    {{-- End Role Btn --}}


    {{-- Alerts --}}
    <x-alert type="success" />
    <x-alert type="info" />
    {{-- End Alerts --}}


    {{-- Roles Table --}}
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Role Name</th>
                <th>Created At</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td><a href="{{ route('dashboard.roles.edit', $role->id) }}">{{ $role->name }}</a></td>
                    <td>{{ $role->created_at }}</td>
                    <td>
                        @can('update', $role)
                            <a href="{{ route('dashboard.roles.edit', [$role->id]) }}" class="btn btn-sm btn-success">Edit</a>
                        @endcan

                    </td>
                    <td>
                        @can('delete', $role)
                            <form action="{{ route('dashboard.roles.destroy', [$role->id]) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No Roles defined.</td>
                </tr>
            @endforelse

        </tbody>
    </table>
    {{ $roles->withQueryString()->links() }}
    {{-- End Roles Table --}}

@endsection
