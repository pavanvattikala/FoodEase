<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Helpers\ModuleHelper;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->filled('pin')
            ? ['pin' => ['required', 'string']]
            : [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = $this->filled('pin')
            ? $this->authenticateWithPin()
            : $this->authenticateWithEmail();

        $this->enforceRoleRestrictions($user);

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    protected function authenticateWithPin(): User
    {
        $user = User::where('pin', $this->input('pin'))->first();

        if (!$user) {
            $this->fail('pin', 'No user exists with this PIN.');
        }

        if ($user->hasPermission(UserRole::Admin)) {
            $this->fail('pin', 'Login via email and password for extra security.');
        }

        return $user;
    }

    protected function authenticateWithEmail(): User
    {
        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            $this->fail('email', trans('auth.failed'));
        }

        return Auth::user();
    }

    protected function enforceRoleRestrictions(User $user): void
    {
        if ($user->isWaiter() && !ModuleHelper::isWaiterModuleEnabled()) {
            $this->forceLogoutIfLoggedIn();
            $this->fail('email', 'Waiter login is currently disabled by the administrator.');
        }

        if ($user->isKitchen() && !ModuleHelper::isKitchenModuleEnabled()) {
            $this->forceLogoutIfLoggedIn();
            $this->fail('email', 'Kitchen login is currently disabled by the administrator.');
        }
    }

    protected function forceLogoutIfLoggedIn(): void
    {
        if (Auth::check()) {
            Auth::logout();
        }
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $this->fail('email', trans('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]));
    }

    protected function fail(string $field, string $message): void
    {
        throw ValidationException::withMessages([
            $field => [$message],
        ]);
    }

    protected function throttleKey(): string
    {
        $identifier = $this->filled('pin')
            ? $this->input('pin')
            : $this->input('email');

        return Str::lower($identifier) . '|' . $this->ip();
    }
}
