<x-guest-layout>
    <div class="mb-5">
        <h2 class="text-xl font-bold uppercase tracking-tight">Zaloguj się</h2>
        <p class="text-sm mt-1 opacity-70">Podaj email i hasło, żeby kontynuować.</p>
    </div>

    @if(session('status'))
        <div class="bg-lime border-2 border-ink p-3 mb-4 text-sm font-medium">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="adres@example.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Hasło" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <label for="remember_me" class="flex items-center gap-2 text-sm font-medium cursor-pointer">
            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 border-2 border-ink rounded-none accent-cobalt">
            Nie wylogowuj mnie
        </label>

        <div class="flex items-center justify-between pt-2 border-t-2 border-ink mt-4">
            @if (Route::has('password.request'))
                <a class="text-sm font-semibold link" href="{{ route('password.request') }}">Zapomniałem hasła</a>
            @endif
            <x-primary-button>Zaloguj &rarr;</x-primary-button>
        </div>
    </form>

    <p class="text-sm mt-6 pt-4 border-t-2 border-ink">
        Nie masz konta? <a href="{{ route('register') }}" class="link">Załóż konto</a>
    </p>
</x-guest-layout>
