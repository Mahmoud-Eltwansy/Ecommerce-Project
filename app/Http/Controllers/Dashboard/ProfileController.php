<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $locales = Languages::getNames();
        $countries = Countries::getNames();

        return view('dashboard.profile.edit', compact('user', 'locales', 'countries'));
    }
    public function update(Request $request)
    {
        // Validate the data
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birthday' => ['date', 'nullable', 'before:today'],
            'gender' => ['in:male,female', 'nullable'],
            'country' => ['required', 'string', 'size:2'],
        ]);

        // get the auth user and then get its profile and fill it with the request
        $user = $request->user();
        $user->profile->fill($request->all())->save();

        //Redirect With Success Message
        return redirect()->route('dashboard.profile.edit')
            ->with('success', 'Profile Updated!');
    }
}
