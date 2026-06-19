<x-guest-layout>
    <h2 class="text-lg font-semibold text-slate-800 mb-1">Zaloguj się</h2>
    <p class="text-sm text-slate-500 mb-5">Podaj email i hasło, aby kontynuować.</p>

    <x-auth-session-status class="mb-4 text-sm text-green-600" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="adres@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="password" value="Hasło" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <label for="remember_me" class="flex items-center gap-2 text-sm text-slate-600">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-blue-500 focus:ring-blue-500">
            Nie wylogowuj mnie
        </label>

        <div class="flex items-center justify-between pt-2">
            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">Nie pamiętam hasła</a>
            @endif
            <x-primary-button>Zaloguj</x-primary-button>
        </div>
    </form>

    <p class="text-sm text-slate-500 text-center mt-6 pt-4 border-t border-slate-200">
        Nie masz konta? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Załóż konto</a>
    </p>
</x-guest-layout>
