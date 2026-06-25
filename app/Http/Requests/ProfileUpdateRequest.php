<?php

namespace App\Http\Requests;

use App\Models\User;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | BASIC INFO
            |--------------------------------------------------------------------------
            */

            'name' => [

                'required',
                'string',
                'max:255',

            ],

            'email' => [

                'required',
                'string',
                'lowercase',
                'email',
                'max:255',

                Rule::unique(User::class)
                    ->ignore($this->user()->id),

            ],

            /*
            |--------------------------------------------------------------------------
            | CONTACT
            |--------------------------------------------------------------------------
            */

            'phone' => [

                'nullable',
                'string',
                'max:20',

            ],

            'whatsapp_number' => [

                'nullable',
                'string',
                'max:20',

            ],

            /*
            |--------------------------------------------------------------------------
            | HEALTH INFO
            |--------------------------------------------------------------------------
            */

            'gender' => [

                'nullable',
                'string',
                'max:50',

            ],

            'age' => [

                'nullable',
                'integer',
                'min:10',
                'max:100',

            ],

            'height' => [

                'nullable',
                'numeric',
                'min:50',
                'max:300',

            ],

            'weight' => [

                'nullable',
                'numeric',
                'min:20',
                'max:500',

            ],

            'goal' => [

                'nullable',
                'string',
                'max:255',

            ],

            'activity_level' => [

                'nullable',
                'string',
                'max:255',

            ],

            /*
            |--------------------------------------------------------------------------
            | NUTRITION
            |--------------------------------------------------------------------------
            */

            'diet_preference' => [

                'nullable',
                'string',
                'max:255',

            ],

            'allergies' => [

                'nullable',
                'string',

            ],

            'medical_conditions' => [

                'nullable',
                'string',

            ],

            /*
            |--------------------------------------------------------------------------
            | LOCATION
            |--------------------------------------------------------------------------
            */

            'city' => [

                'nullable',
                'string',
                'max:255',

            ],

            'country' => [

                'nullable',
                'string',
                'max:255',

            ],

            /*
            |--------------------------------------------------------------------------
            | BIO
            |--------------------------------------------------------------------------
            */

            'bio' => [

                'nullable',
                'string',

            ],

        ];
    }
}