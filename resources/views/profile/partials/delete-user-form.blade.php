<section class="space-y-4">
    <header class="pb-3 border-b-2 border-ink">
        <h2 class="text-lg font-bold uppercase text-tomato">Usuń konto</h2>
        <p class="text-sm opacity-70 mt-1">Po usunięciu konta wszystkie powiązane dane zostaną trwale usunięte. Przed usunięciem pobierz wszystko, co chcesz zachować.</p>
    </header>

    <button type="button" class="btn btn-danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">Usuń moje konto</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold uppercase">Czy na pewno chcesz usunąć konto?</h2>
            <p class="mt-2 text-sm opacity-70">Operacja jest nieodwracalna. Wpisz hasło, żeby potwierdzić.</p>

            <div class="mt-4">
                <x-input-label for="password" value="Hasło" class="sr-only" />
                <x-text-input id="password" name="password" type="password" placeholder="hasło" />
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="mt-5 flex justify-end gap-3">
                <button type="button" class="btn" x-on:click="$dispatch('close')">Anuluj</button>
                <button class="btn btn-danger">Usuń konto</button>
            </div>
        </form>
    </x-modal>
</section>
