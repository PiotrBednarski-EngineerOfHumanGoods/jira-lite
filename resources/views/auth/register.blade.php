<x-guest-layout>
    <div class="mb-5">
        <h2 class="text-xl font-bold uppercase tracking-tight">Załóż konto</h2>
        <p class="text-sm mt-1 opacity-70">Wypełnij formularz, żeby się zarejestrować.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Imię i nazwisko" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Jan Kowalski" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="adres@example.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Hasło" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Powtórz hasło" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="flex justify-end pt-2 border-t-2 border-ink mt-4">
            <x-primary-button>Załóż konto &rarr;</x-primary-button>
        </div>
    </form>

    <p class="text-sm mt-6 pt-4 border-t-2 border-ink">
        Masz konto? <a href="{{ route('login') }}" class="link">Zaloguj się</a>
    </p>
</x-guest-layout>
