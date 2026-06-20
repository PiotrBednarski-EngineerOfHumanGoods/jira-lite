@if (session('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
         class="mb-4 bg-lime border-2 border-ink p-3 flex items-start gap-3" style="box-shadow: 4px 4px 0 0 #0A0A0A">
        <span class="mono text-xs font-bold uppercase tracking-wider shrink-0">OK</span>
        <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
        <button @click="show = false" class="font-bold text-lg leading-none px-1">×</button>
    </div>
@endif
@if (session('error'))
    <div class="mb-4 bg-tomato text-white border-2 border-ink p-3 flex items-start gap-3" style="box-shadow: 4px 4px 0 0 #0A0A0A">
        <span class="mono text-xs font-bold uppercase tracking-wider shrink-0">ERR</span>
        <div class="flex-1 text-sm font-medium">{{ session('error') }}</div>
    </div>
@endif
@if ($errors->any())
    <div class="mb-4 bg-tomato text-white border-2 border-ink p-4" style="box-shadow: 4px 4px 0 0 #0A0A0A">
        <p class="font-bold uppercase tracking-wider text-xs mono mb-2">Formularz zawiera błędy</p>
        <ul class="text-sm font-medium space-y-1">
            @foreach ($errors->all() as $err)
                <li>— {{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
