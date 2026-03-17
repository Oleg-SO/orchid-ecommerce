@extends('home')

@section('title', 'Главная страница')

@section('content')

    <section class="hero-section">
        <div class="win-container hero-container">
            <div class="hero-content">
                <span class="hero-badge">
                    <i class="fas fa-bolt"></i> Доставка за 2 часа
                </span>
                <h1>Инструменты для <span class="gradient-text">профессионалов</span> и дома</h1>
                <p class="hero-desc">Более 5000 наименований от топовых брендов. Гарантия 3 года на весь инструмент.</p>

                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-value">15+</div>
                        <div class="stat-label">лет на рынке</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">8000+</div>
                        <div class="stat-label">товаров</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">25000</div>
                        <div class="stat-label">клиентов</div>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="/catalog" class="btn-primary">
                        <i class="fas fa-arrow-right"></i> Перейти в каталог
                    </a>
                    <a href="#" class="btn-secondary">
                        <i class="fas fa-play"></i> Смотреть видео
                    </a>
                </div>
            </div>

            <div class="hero-image">
                <div class="glass-card tools-showcase">
                    <img src="https://via.placeholder.com/400x300/0067c0/ffffff?text=Инструменты" alt="Tools">
                    <div class="floating-badge discount">-25%</div>
                    <div class="floating-badge new">New</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Поисковая строка в стиле Windows 11 --}}
    <section class="search-section">
        <div class="win-container">
            <div class="search-panel glass">
                <div class="search-filters">
                    <button class="filter-btn active">Все</button>
                    <button class="filter-btn">Электро</button>
                    <button class="filter-btn">Ручной</button>
                    <button class="filter-btn">Измерение</button>
                    <button class="filter-btn">Крепеж</button>
                </div>
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Найди свой инструмент...">
                    <button class="btn-search">Найти</button>
                </div>
            </div>
        </div>
    </section>
    <section class="featured-section">
        <div class="win-container">
            <div class="section-header">
                <h2>Новинки <span class="accent">каталога</span></h2>
                <a href="/catalog" class="view-all">Все товары <i class="fas fa-chevron-right"></i></a>
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
                                    <a href="/product/{{ $product->slug }}" class="btn btn-sm btn-outline-primary">Подробнее</a>
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

    {{-- !!! ЗДЕСЬ ВАША ЛОГИКА КАТЕГОРИЙ --}}
    <section class="categories-section">
        <div class="win-container">
            <div class="section-header">
                <h2>Популярные <span class="accent">категории</span></h2>
                <a href="/catalog" class="view-all">Все категории <i class="fas fa-chevron-right"></i></a>
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

    {{-- Хиты продаж (слайдер) --}}
    <section class="featured-section">
        <div class="win-container">
            <div class="section-header">
                <h2>Хиты <span class="accent">продаж</span></h2>
                <div class="slider-controls">
                    <button class="slider-prev" id="prevBtn"><i class="fas fa-chevron-left"></i></button>
                    <button class="slider-next" id="nextBtn"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>

            <div class="products-slider" id="productsSlider">
                <div class="slider-track">
                    @foreach($hitProducts as $product)
                        <div class="product-card">
                            @php $productImage = $product->attachment()->first(); @endphp
                            @if($productImage)
                                <img src="{{ $productImage->url() }}" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/260x180/0067c0/ffffff?text={{ urlencode($product->name) }}" alt="{{ $product->name }}">
                            @endif
                            <div class="product-title">{{ $product->name }}</div>
                            <div class="product-price">{{ number_format($product->price, 0, '.', ' ') }} ₽</div>
                            <div class="product-rating">
                                @php $rating = $product->rating ?? 4.5; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($rating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $rating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span>({{ $product->reviews_count ?? 0 }})</span>
                            </div>
                            {{-- TODO: Реализовать добавление в корзину --}}
                            <button class="btn-add" data-product-id="{{ $product->id }}">
                                <i class="fas fa-cart-plus"></i> В корзину
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Преимущества в стиле Windows 11 Widgets --}}
    <section class="features-section">
        <div class="win-container">
            <div class="features-grid">
                <div class="feature-card glass">
                    <i class="fas fa-truck feature-icon"></i>
                    <h4>Бесплатная доставка</h4>
                    <p>При заказе от 10 000 ₽</p>
                </div>

                <div class="feature-card glass">
                    <i class="fas fa-shield-alt feature-icon"></i>
                    <h4>Гарантия 3 года</h4>
                    <p>На все электроинструменты</p>
                </div>

                <div class="feature-card glass">
                    <i class="fas fa-undo-alt feature-icon"></i>
                    <h4>Возврат 30 дней</h4>
                    <p>Без проблем и вопросов</p>
                </div>

                <div class="feature-card glass">
                    <i class="fas fa-credit-card feature-icon"></i>
                    <h4>Удобная оплата</h4>
                    <p>Наличные, карта, рассрочка</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Бренды --}}
    <section class="brands-section">
        <div class="win-container">
            <h2 class="brands-title">Популярные бренды</h2>
            @forelse($brands as $brand)
                <div class="brand-item glass">{{ $brand->name }}</div>
            @empty
                <div class="brand-item glass">Бренды пока не добавлены</div>
            @endforelse
        </div>
    </section>

    {{-- Аккордеон с вопросами (Windows 11 стиль) --}}
    <section class="faq-section">
        <div class="win-container">
            <h2 class="faq-title">Часто задаваемые <span class="accent">вопросы</span></h2>

            <div class="accordion">
                <div class="accordion-item glass">
                    <button class="accordion-header">
                        <span>Как получить скидку при первом заказе?</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p>Подпишитесь на нашу рассылку и получите промокод на 5% скидку. Также дарим скидку за регистрацию на сайте.</p>
                    </div>
                </div>

                <div class="accordion-item glass">
                    <button class="accordion-header">
                        <span>Сколько времени занимает доставка?</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p>По городу доставляем в день заказа (при наличии). В области 1-2 дня, в регионы 3-7 дней.</p>
                    </div>
                </div>

                <div class="accordion-item glass">
                    <button class="accordion-header">
                        <span>Есть ли самовывоз?</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        <p>Да, 5 точек самовывоза по городу. Забрать заказ можно через час после оформления.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
