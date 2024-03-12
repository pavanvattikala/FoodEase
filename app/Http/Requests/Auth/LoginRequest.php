<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // If 'pin' is provided, no need for 'email' and 'password'
        if ($this->filled('pin')) {
            return [
                'pin' => ['required', 'string'],
            ];
        }

        // Otherwise, require 'email' and 'password'
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {

        $this->ensureIsNotRateLimited();
        // If 'pin' is provided, authenticate with pin
        $this->filled('pin') ? $this->authenticateWithPin() : $this->authenticateWithEmail();

        RateLimiter::clear($this->throttleKey());
    }
    public function authenticateWithPin()
    {
        // Retrieve the user by the provided pin
        $user = User::where('pin', $this->input('pin'))->first();

        if ($user->hasPermission(1)) {
            $this->ensureIsNotRateLimited();
            throw ValidationException::withMessages([
                'email' => trans('login via email and password for extra secuirty'),
            ]);
        }

        // Check if the user exists and if the PIN matches
        if (!$user) {
            // If the user doesn't exist, or if the PIN doesn't match, throw a validation exception
            throw ValidationException::withMessages([
                'pin' => trans('auth.failed'),
            ]);
        }

        // Log in the user
        Auth::login($user, $this->boolean('remember'));
    }

    public function authenticateWithEmail()
    {
        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
    }


    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')) . '|' . $this->ip();
    }
}
