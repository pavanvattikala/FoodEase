<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div id="email-login">
                <!-- Email Address -->
                <div>
                    <x-label for="email" :value="__('Email')" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-label for="password" :value="__('Password')" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password"
                        autocomplete="current-password" />
                </div>
                <div class="mt-4">
                    <a id="loing-via-pin" class="underline text-sm text-gray-600 hover:text-gray-900">
                        {{ __('Biller login via pin') }}
                    </a>
                </div>
            </div>
            <div id="pin-login">
                <!-- PIN for Billers -->
                <div>
                    <x-label for="pin" :value="__('PIN')" />
                    <x-input id="pin" class="block mt-1 w-full" type="password" name="pin" />
                </div>
                <!-- Admin Login via Email and Password -->
                <div class="mt-4">
                    <a id="login-via-email" class="underline text-sm text-gray-600 hover:text-gray-900">
                        {{ __('Admin login via email and password') }}
                    </a>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-3">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
    <script>
        // DOM Load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email-login').style.display = 'none';
        });

        document.getElementById('loing-via-pin').addEventListener('click', function() {
            document.getElementById('email-login').style.display = 'none';
            document.getElementById('pin-login').style.display = 'block';

            // clear email and password fields
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';

            // focus on pin field
            document.getElementById('pin').focus();
        });
        document.getElementById('login-via-email').addEventListener('click', function() {
            document.getElementById('email-login').style.display = 'block';
            document.getElementById('pin-login').style.display = 'none';

            // clear pin field
            document.getElementById('pin').value = '';

            // focus on email field
            document.getElementById('email').focus();
        });
    </script>
</x-guest-layout>
