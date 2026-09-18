<?php

namespace App\Livewire\Auth;

use App\Events\Frontend\UserRegistered;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Register')]
#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = 'student';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        // A soft-deleted account still owns its email at the database level
        // (users_email_unique is a full unique index), so the generic
        // "email already taken" error would leave the person stuck with no
        // explanation. Tell them what happened and how to get help instead.
        if (User::onlyTrashed()->where('email', Str::lower($this->email))->exists()) {
            throw ValidationException::withMessages([
                'email' => __('This email belongs to a deactivated account. Please contact us so we can reactivate it.'),
            ]);
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:student,teacher,parent'],
        ]);

        // Role is handled via Spatie below, so keep it out of the User attributes.
        $userData = $validated;
        unset($userData['role']);

        $user = User::create($userData);

        $username = intval(config('app.initial_username')) + $user->id;
        $user->username = strval($username);
        $user->last_ip = optional(request())->getClientIp();
        $user->save();

        // Assign the selected role
        $user->assignRole($validated['role']);

        // Students get a permanent student number, e.g. SOHMC-2026-0001
        if ($validated['role'] === 'student') {
            $user->assignStudentNumber();
        }

        event(new Registered($user));
        event(new UserRegistered($user));

        Auth::login($user);

        log_activity('registered a new '.$validated['role'].' account', $user);

        $this->redirect(route('verification.notice', absolute: false), navigate: true);
    }
}
