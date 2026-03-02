@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section fade-in-up">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">Экологичные товары для вашего дома</h1>
                    <p class="lead mb-4">Откройте для себя качественные и безопасные продукты для всей семьи</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="/catalog" class="btn btn-primary btn-lg me-md-2">В каталог</a>
                        <a href="/about" class="btn btn-outline-primary btn-lg">О нас</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- СЕКЦИЯ: Последние товары -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5 fade-in-up">
                <h2 class="fw-bold">Новинки</h2>
                <p class="text-muted">Свежие поступления в нашем магазине</p>
            </div>

            <div class="row">
                @forelse($latestProducts as $product)
                <div class="col-md-6 col-lg-3 mb-4 fade-in-up">
                    <div class="card h-100 product-card">
                        <div class="product-image" style="height: 200px; overflow: hidden;">
                            @if($product->attachment()->first())
                                <img src="{{ $product->attachment()->first()->url() }}"
                                     class="card-img-top"
                                     alt="{{ $product->name }}"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/300x200?text=Нет+фото"
                                     class="card-img-top"
                                     alt="Нет изображения">
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted small">
                                @foreach($product->categories as $category)
                                    {{ $category->name }}@if(!$loop->last), @endif
                                @endforeach
                            </p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0 text-primary">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                                    <a href="/catalog/{{ $product->slug }}" class="btn btn-sm btn-outline-primary">Подробнее</a>
                                </div>
                                @if($product->old_price)
                                    <small class="text-muted text-decoration-line-through">
                                        Старая цена: {{ number_format($product->old_price, 0, '.', ' ') }} ₽
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Пока нет товаров в каталоге</p>
                </div>
                @endforelse
            </div>

            <div class="text-center mt-4">
                <a href="/catalog" class="btn btn-primary">Все товары</a>
            </div>
        </div>
    </section>

    <!-- СЕКЦИЯ: Категории -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5 fade-in-up">
                <h2 class="fw-bold">Популярные категории</h2>
                <p class="text-muted">Выберите то, что вам нужно</p>
            </div>

            <div class="row">
                @forelse($popularCategories as $category)
                <div class="col-md-6 col-lg-3 mb-4 fade-in-up">
                    <div class="card category-card h-100">
                        @if($category->attachment()->first())
                            <img src="{{ $category->attachment()->first()->url() }}"
                                 class="card-img-top"
                                 alt="{{ $category->name }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/300x200?text={{ urlencode($category->name) }}"
                                 class="card-img-top"
                                 alt="{{ $category->name }}">
                        @endif
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text text-muted">{{ $category->products_count ?? 0 }} товаров</p>
                            <a href="/catalog/category/{{ $category->slug }}"
                               class="btn btn-outline-primary">Смотреть</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Категории пока не добавлены</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- СЕКЦИЯ: Преимущества -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center mb-4 fade-in-up">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-truck fa-3x text-primary"></i>
                    </div>
                    <h5>Бесплатная доставка</h5>
                    <p class="text-muted">При заказе от 3000 рублей</p>
                </div>
                <div class="col-md-4 text-center mb-4 fade-in-up">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-leaf fa-3x text-success"></i>
                    </div>
                    <h5>Экологично</h5>
                    <p class="text-muted">Все товары прошли сертификацию</p>
                </div>
                <div class="col-md-4 text-center mb-4 fade-in-up">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-warning"></i>
                    </div>
                    <h5>Гарантия качества</h5>
                    <p class="text-muted">Возврат в течение 14 дней</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('styles')
<style>
.product-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
.category-card {
    transition: transform 0.2s;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.category-card:hover {
    transform: translateY(-5px);
}
</style>
@endsection
