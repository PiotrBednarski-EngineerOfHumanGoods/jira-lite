@include('errors.layout', [
    'code' => '403',
    'color' => 'tomato',
    'title' => 'Brak uprawnień',
    'message' => $exception->getMessage() ?: 'Twoja rola nie pozwala na tę operację. Skontaktuj się z administratorem.',
])
