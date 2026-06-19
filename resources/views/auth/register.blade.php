<x-guest-layout>
    <h2 class="text-lg font-semibold text-slate-800 mb-1">Załóż konto</h2>
    <p class="text-sm text-slate-500 mb-5">Wypełnij formularz, żeby się zarejestrować.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Imię i nazwisko" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Jan Kowalski" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="adres@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="password" value="Hasło" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Powtórz hasło" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex justify-end pt-2">
            <x-primary-button>Załóż konto</x-primary-button>
        </div>
    </form>

    <p class="text-sm text-slate-500 text-center mt-6 pt-4 border-t border-slate-200">
        Masz już konto? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Zaloguj się</a>
    </p>
</x-guest-layout>
