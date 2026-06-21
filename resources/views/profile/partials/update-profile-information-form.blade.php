<section>
    <header class="pb-3 border-b-2 border-ink mb-4">
        <h2 class="text-lg font-bold uppercase">Dane konta</h2>
        <p class="text-sm opacity-70 mt-1">Imię i adres email widoczny w aplikacji.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Imię i nazwisko" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2">
                        Adres email nie został zweryfikowany.
                        <button form="send-verification" class="link">Wyślij ponownie link weryfikacyjny</button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-cobalt">Nowy link został wysłany.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>Zapisz</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="mono text-xs font-bold uppercase tracking-wider text-cobalt">zapisano</p>
            @endif
        </div>
    </form>
</section>
