<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use Illuminate\View\View;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | EDIT PROFILE
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request): View
    {
        return view('profile.edit', [

            'user' => $request->user(),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PROFILE
    |--------------------------------------------------------------------------
    */

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | FILL USER DATA
        |--------------------------------------------------------------------------
        */

        $user->fill($request->validated());

        /*
        |--------------------------------------------------------------------------
        | RESET EMAIL VERIFICATION
        |--------------------------------------------------------------------------
        */

        if ($user->isDirty('email')) {

            $user->email_verified_at = null;

        }

        /*
        |--------------------------------------------------------------------------
        | AUTO BMI CALCULATION
        |--------------------------------------------------------------------------
        */

        if ($request->height && $request->weight) {

            $heightInMeters = $request->height / 100;

            $bmi = $request->weight / ($heightInMeters * $heightInMeters);

            $user->bmi = round($bmi, 2);

        }

        /*
        |--------------------------------------------------------------------------
        | SAVE USER
        |--------------------------------------------------------------------------
        */

        $user->save();

        return Redirect::route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE ACCOUNT
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [

            'password' => ['required', 'current_password'],

        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}