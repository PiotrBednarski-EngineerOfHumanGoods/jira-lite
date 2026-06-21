@include('errors.layout', [
    'code' => '419',
    'color' => 'sun',
    'title' => 'Sesja wygasła',
    'message' => 'Token CSRF stracił ważność. Odśwież stronę i spróbuj ponownie.',
])
