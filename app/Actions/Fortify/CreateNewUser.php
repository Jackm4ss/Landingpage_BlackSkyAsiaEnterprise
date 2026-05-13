<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    private const REGISTRATION_SOURCES = [
        'direct',
        'instagram',
        'facebook',
        'tiktok',
        'google',
        'newsletter',
        'partner',
        'other',
    ];

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        $email = Str::lower((string) $input['email']);

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'terms' => ['accepted'],
            'country_code' => ['required', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
            'registration_source' => ['nullable', 'string', Rule::in(self::REGISTRATION_SOURCES)],
            'registration_referrer' => ['nullable', 'string', 'max:2048'],
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $email,
            'password' => Hash::make($input['password']),
            'registration_source' => $input['registration_source'] ?? 'direct',
            'registration_country_code' => Str::upper((string) $input['country_code']),
            'registration_referrer' => $input['registration_referrer'] ?? null,
        ]);

        $user->assignRole(Role::findOrCreate('user'));

        return $user;
    }
}
