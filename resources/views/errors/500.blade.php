@include('errors.layout', [
    'code' => '500',
    'color' => 'tomato',
    'title' => 'Błąd serwera',
    'message' => 'Coś się wykrzaczyło po naszej stronie. Spróbuj ponownie za chwilę.',
])
