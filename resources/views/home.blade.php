{{-- resources/views/index.blade.php --}}
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Инструменты.Про | Магазин строй инструментов</title>

    {{-- Windows 11 шрифт и иконки --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    {{-- Bootstrap для сетки (чтобы работали col-md-6) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Основные стили --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about.css') }}"> --}}
</head>
    <body>

        @include('layouts.header')

        @yield('content')

        @include('layouts.footer')

        {{-- Скрипты --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/main.js') }}"></script>
        <script src="{{ asset('js/catalog.js') }}"></script>
        <script src="{{ asset('js/about.js') }}"></script>
        <script src="{{ asset('js/contacts.js') }}"></script>
        <script src="{{ asset('js/products.js') }}"></script>
        <script src="{{ asset('js/delivery.js') }}"></script>
        <script src="{{ asset('js/select-all.js') }}"></script>
        <script src="{{ asset('js/product.js') }}"></script>
        <script>
            window.productData = {
                id: {{ $product->id ?? 0 }},
                name: '{{ $product->name ?? '' }}',
                price: {{ $product->price ?? 0 }},
                quantity: {{ $product->quantity ?? 0 }}
            };
    </script>
    </body>
</html>
