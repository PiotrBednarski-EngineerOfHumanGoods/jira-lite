@if (session('success'))
    <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded text-sm" x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded text-sm">
        {{ session('error') }}
    </div>
@endif
@if ($errors->any())
    <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-800 rounded text-sm">
        <p class="font-semibold mb-1">Formularz zawiera błędy:</p>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
