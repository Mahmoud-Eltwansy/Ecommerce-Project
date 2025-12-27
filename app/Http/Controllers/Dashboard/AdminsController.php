<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;

class AdminsController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Admin::class, 'admin');
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $admins = Admin::paginate();
        return view('dashboard.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('dashboard.admins.create', [
            'roles' => Role::all(),
            'admin' => new Admin(),
            'admin_roles' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins',
            'email' => 'required|email|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:20',
            'roles' => 'required|array',
        ]);

        $admin = Admin::create(array_merge(
            $request->only('name', 'username', 'email', 'phone_number'),
            ['password' => bcrypt($request->password)]
        ));
        $admin->roles()->attach($request->roles);

        return redirect()
            ->route('dashboard.admins.index')
            ->with('success', 'Admin created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit(Admin $admin)
    {
        $roles = Role::all();
        $admin_roles = $admin->roles()->pluck('id')->toArray();

        return view('dashboard.admins.edit', compact('admin', 'roles', 'admin_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $data = $request->only('name', 'username', 'email', 'phone_number');
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $admin->update($data);
        $admin->roles()->sync($request->roles);

        return redirect()
            ->route('dashboard.admins.index')
            ->with('success', 'Admin updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        Admin::destroy($id);
        return redirect()
            ->route('dashboard.admins.index')
            ->with('success', 'Admin deleted successfully');
    }
}
