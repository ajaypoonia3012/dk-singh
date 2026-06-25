<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberProfileController extends Controller
{
    public function index()
    {
        return view(
            'member.profile',
            [
                'user' => auth()->user()
            ]
        );
    }

    public function update(Request $request)
    {
        $user = auth()->user();
$bmi = null;

if ($request->height && $request->weight) {

    $heightInMeters = $request->height / 100;

    $bmi = round(
        $request->weight /
        ($heightInMeters * $heightInMeters),
        1
    );
}
        $user->update([

            'phone' => $request->phone,
            'whatsapp_number' => $request->whatsapp_number,

            'gender' => $request->gender,
            'age' => $request->age,

            'height' => $request->height,
            'weight' => $request->weight,
            'bmi' => $bmi,

            'goal' => $request->goal,
            'activity_level' => $request->activity_level,

            'diet_preference' => $request->diet_preference,
            'allergies' => $request->allergies,
            'medical_conditions' => $request->medical_conditions,

            'city' => $request->city,
            'country' => $request->country,

            'bio' => $request->bio,
'profile_completed' => true,

        ]);

        return back()->with(
            'success',
            'Profile Updated Successfully'
        );
    }
}