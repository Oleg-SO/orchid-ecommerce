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

    <!-- Categories Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5 fade-in-up">
                <h2 class="fw-bold">Популярные категории</h2>
                <p class="text-muted">Выберите то, что вам нужно</p>
            </div>

            <div class="row">
                @foreach(['Эко-товары', 'Для дома', 'Кухня', 'Сад'] as $category)
                <div class="col-md-6 col-lg-3 mb-4 fade-in-up">
                    <div class="card category-card h-100">
                        <img src="https://ecofire.kz/images/landing/home3/electrokamin.png?text={{ urlencode($category) }}" 
                             class="card-img-top" alt="{{ $category }}">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $category }}</h5>
                            <p class="card-text text-muted">Натуральные и безопасные продукты</p>
                            <a href="/category/{{ strtolower(str_replace(' ', '-', $category)) }}" 
                               class="btn btn-outline-primary">Смотреть</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
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