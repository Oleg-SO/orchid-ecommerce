@extends('home')

@section('title', 'Каталог товаров')

@section('content')
    {{-- ХЛЕБНЫЕ КРОШКИ --}}
    <section class="breadcrumb-section">
        <div class="win-container">
            <div class="breadcrumb-container glass">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Главная</a>
                <i class="fas fa-chevron-right"></i>
                <span class="current">Каталог товаров</span>
            </div>
        </div>
    </section>

    {{-- Заголовок каталога --}}
    {{-- <section class="catalog-header-section">
        <div class="win-container">
            <div class="catalog-header glass">
                <h1>Каталог товаров</h1>
                <p class="text-muted">Найдено товаров: {{ $latestProducts->total() ?? 0 }}</p>
            </div>
        </div>
    </section> --}}

    {{-- Основной контент каталога --}}
    <section class="catalog-main-section">
        <div class="win-container">
            <div class="row">
                {{-- САЙДБАР С ФИЛЬТРАМИ --}}
                <div class="col-lg-3">
                    <div class="filters-sidebar glass">
                        <h3 class="filters-title">
                            <i class="fas fa-filter"></i> Фильтры
                            <button class="clear-filters-btn" id="clearFilters">Сбросить</button>
                        </h3>

                        {{-- ПОИСК ПО ТОВАРАМ --}}
                        <div class="filter-section">
                            <h4 class="filter-section-title">
                                Поиск
                                <i class="fas fa-chevron-down"></i>
                            </h4>
                            <div class="filter-section-content">
                                <div class="search-filter">
                                    <input type="text"
                                           class="search-filter-input"
                                           id="searchFilter"
                                           placeholder="Название товара..."
                                           value="{{ request('search', '') }}">
                                    <i class="fas fa-search search-filter-icon"></i>
                                </div>
                            </div>
                        </div>

                        {{-- ФИЛЬТР ПО КАТЕГОРИЯМ --}}
                        @if(isset($categories) && $categories->count() > 0)
                        <div class="filter-section">
                            <h4 class="filter-section-title">
                                Категории
                                <i class="fas fa-chevron-down"></i>
                            </h4>
                            <div class="filter-section-content">
                                <div class="categories-list">
                                    @foreach($categories as $category)
                                        @php
                                            $hasProducts = ($category->products_count ?? 0) > 0;
                                            $hasChildren = $category->children->count() > 0;
                                            $isActive = in_array($category->slug, request()->input('category', []));
                                        @endphp

                                        <div class="category-item" style="margin-left: {{ $category->depth * 20 }}px;">
                                            @if($hasChildren && !$hasProducts)
                                                {{-- Только подкатегории, без товаров --}}
                                                <span class="category-toggle collapsed" data-target="sub-{{ $category->id }}">
                                                    ▶
                                                </span>
                                                <span class="category-name">{{ $category->name }}</span>
                                                <span class="category-count">({{ $category->products_count ?? 0 }})</span>

                                                {{-- Подкатегории (скрыты по умолчанию) --}}
                                                <div class="subcategories" id="sub-{{ $category->id }}" style="display: none; margin-left: 20px;">
                                                    @foreach($category->children as $child)
                                                        <div class="category-item">
                                                            <a href="{{ route('catalog', ['category' => [$child->slug]]) }}"
                                                            class="category-link {{ in_array($child->slug, request()->input('category', [])) ? 'active' : '' }}">
                                                                {{ $child->name }}
                                                                <span class="category-count">({{ $child->products_count ?? 0 }})</span>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                {{-- Есть товары (с подкатегориями или без) --}}
                                                @if($hasChildren)
                                                    <span class="category-toggle collapsed" data-target="sub-{{ $category->id }}">
                                                        ▶
                                                    </span>
                                                @endif

                                                <a href="{{ route('catalog', ['category' => [$category->slug]]) }}"
                                                class="category-link {{ $isActive ? 'active' : '' }}">
                                                    {{ $category->name }}
                                                    <span class="category-count">({{ $category->products_count ?? 0 }})</span>
                                                </a>

                                                @if($hasChildren)
                                                    <div class="subcategories" id="sub-{{ $category->id }}" style="display: none; margin-left: 20px;">
                                                        @foreach($category->children as $child)
                                                            <div class="category-item">
                                                                <a href="{{ route('catalog', ['category' => [$child->slug]]) }}"
                                                                class="category-link {{ in_array($child->slug, request()->input('category', [])) ? 'active' : '' }}">
                                                                    {{ $child->name }}
                                                                    <span class="category-count">({{ $child->products_count ?? 0 }})</span>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- ФИЛЬТР ПО ЦЕНЕ --}}
                        <div class="filter-section">
                            <h4 class="filter-section-title">
                                Цена
                                <i class="fas fa-chevron-down"></i>
                            </h4>
                            <div class="filter-section-content">
                                <div class="price-slider-container">
                                    {{-- TODO: Реализовать слайдер цен через JS --}}
                                    <div id="priceRangeSlider" class="price-slider"></div>
                                    <div class="price-inputs">
                                        <div class="price-input-group">
                                            <span>от</span>
                                            <input type="number"
                                                   id="minPrice"
                                                   value="{{ request('min_price', 0) }}"
                                                   placeholder="0"
                                                   min="0">
                                        </div>
                                        <div class="price-input-group">
                                            <span>до</span>
                                            <input type="number"
                                                   id="maxPrice"
                                                   value="{{ request('max_price', 100000) }}"
                                                   placeholder="100000"
                                                   min="0">
                                        </div>
                                    </div>
                                    <div class="price-range-values">
                                        <span>0 ₽</span>
                                        <span>100 000 ₽</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ФИЛЬТР ПО НАЛИЧИЮ --}}
                        <div class="filter-section">
                            <h4 class="filter-section-title">
                                Наличие
                                <i class="fas fa-chevron-down"></i>
                            </h4>
                            <div class="filter-section-content">
                                <label class="in-stock-checkbox glass-option">
                                    <input type="checkbox"
                                           name="in_stock"
                                           value="1"
                                           {{ request()->has('in_stock') ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <span class="in-stock-text">Только в наличии</span>
                                </label>
                            </div>
                        </div>

                        {{-- ФИЛЬТР ПО БРЕНДАМ --}}
                        {{-- TODO: Добавить бренды после создания таблицы brands --}}
                        {{--
                        @if(isset($brands) && $brands->count() > 0)
                        <div class="filter-section">
                            <h4 class="filter-section-title">
                                Бренды
                                <i class="fas fa-chevron-down"></i>
                            </h4>
                            <div class="filter-section-content">
                                <div class="brands-list" id="brandsList">
                                    @foreach($brands as $brand)
                                    <label class="brand-checkbox glass-option">
                                        <input type="checkbox"
                                               name="brand[]"
                                               value="{{ $brand->slug ?? '' }}"
                                               {{ in_array($brand->slug ?? '', request()->input('brand', [])) ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                        <span class="brand-name">{{ $brand->name ?? '' }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        --}}

                        <button class="apply-filters-btn" id="applyFilters">
                            <i class="fas fa-check"></i> Применить фильтры
                        </button>
                    </div>
                </div>

                {{-- ОСНОВНАЯ ЧАСТЬ - ТОВАРЫ --}}
                <div class="col-lg-9">
                    {{-- Верхняя панель с сортировкой --}}
                    <div class="catalog-toolbar glass">
                        <div class="sort-section">
                            <label for="sortSelect">Сортировать:</label>
                            <select id="sortSelect" class="sort-select">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>По новизне</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>По цене (возрастание)</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>По цене (убывание)</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>По названию (А-Я)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>По названию (Я-А)</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>По популярности</option>
                            </select>
                        </div>

                        <div class="view-section">
                            <button class="view-btn active" data-view="grid">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button class="view-btn" data-view="list">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>

                    {{-- СПИСОК ТОВАРОВ --}}
                    @if(isset($latestProducts) && $latestProducts->count() > 0)
                        {{-- Режим плитки --}}
                        <div class="products-grid" id="productsGrid">
                            @foreach ($latestProducts as $product)
                                <div class="product-card-wrapper">
                                    <div class="product-card glass">
                                        <div class="product-image-wrapper">
                                            @php
                                                $productImage = $product->attachment()->first();
                                                $isNew = $product->is_new ?? false;
                                                $isHit = $product->is_hit ?? false;
                                                $hasDiscount = isset($product->old_price) && $product->old_price > $product->price;
                                                $discountPercent = $hasDiscount ? round((1 - $product->price/$product->old_price)*100) : 0;
                                            @endphp

                                            @if($productImage)
                                                <img src="{{ $productImage->url() }}"
                                                     class="card-img-top"
                                                     alt="{{ $product->name ?? 'Товар' }}"
                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <img src="https://via.placeholder.com/300x200?text=Нет+фото"
                                                     class="card-img-top"
                                                     alt="Нет изображения">
                                            @endif

                                            {{-- Бейджи --}}
                                            @if($isNew)
                                                <span class="product-badge new">Новинка</span>
                                            @endif

                                            @if($hasDiscount)
                                                <span class="product-badge discount">-{{ $discountPercent }}%</span>
                                            @endif

                                            @if($isHit)
                                                <span class="product-badge hit">Хит</span>
                                            @endif
                                        </div>

                                        <div class="product-info">
                                            <h3 class="product-title">
                                                <a href="{{ route('product.show', $product->slug ?? '') }}">{{ $product->name ?? 'Товар' }}</a>
                                            </h3>

                                            {{-- Категории товара --}}
                                            @if($product->categories && $product->categories->count() > 0)
                                                <div class="product-categories">
                                                    @foreach($product->categories as $category)
                                                        <span class="product-category">{{ $category->name ?? '' }}</span>@if(!$loop->last), @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Рейтинг (заглушка) --}}
                                            {{-- TODO: Добавить реальный рейтинг позже --}}
                                            <div class="product-rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <span class="rating-count">(124)</span>
                                            </div>

                                            {{-- Цена --}}
                                            <div class="product-price-block">
                                                <span class="current-price">{{ number_format($product->price ?? 0, 0, '.', ' ') }} ₽</span>
                                                @if($product->old_price)
                                                    <span class="old-price">{{ number_format($product->old_price, 0, '.', ' ') }} ₽</span>
                                                @endif
                                            </div>

                                            {{-- Кнопки действий (TODO) --}}
                                            {{-- TODO: Реализовать добавление в корзину позже --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Режим списка (скрыт по умолчанию) --}}
                        <div class="products-list" id="productsList" style="display: none;">
                            @foreach ($latestProducts as $product)
                                @php
                                    $productImage = $product->attachment()->first();
                                    $isNew = $product->is_new ?? false;
                                @endphp
                                <div class="product-list-item glass">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="product-list-image">
                                                @if($productImage)
                                                    <img src="{{ $productImage->url() }}" alt="{{ $product->name ?? 'Товар' }}">
                                                @else
                                                    <img src="https://via.placeholder.com/300x200?text=Нет+фото" alt="{{ $product->name ?? 'Товар' }}">
                                                @endif

                                                @if($isNew)
                                                    <span class="product-badge new">Новинка</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="product-list-info">
                                                <h3><a href="{{ route('product.show', $product->slug ?? '') }}">{{ $product->name ?? 'Товар' }}</a></h3>

                                                @if($product->categories && $product->categories->count() > 0)
                                                    <div class="product-list-category">
                                                        {{ $product->categories->first()->name ?? '' }}
                                                    </div>
                                                @endif

                                                {{-- Рейтинг (заглушка) --}}
                                                <div class="product-list-rating">
                                                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i> (124)
                                                </div>

                                                {{-- Описание (обрезанное) --}}
                                                <p class="product-list-description">{{ Str::limit($product->description ?? '', 150) }}</p>

                                                {{-- Цена --}}
                                                <div class="product-list-price">
                                                    <span class="current-price">{{ number_format($product->price ?? 0, 0, '.', ' ') }} ₽</span>
                                                    @if($product->old_price)
                                                        <span class="old-price">{{ number_format($product->old_price, 0, '.', ' ') }} ₽</span>
                                                    @endif
                                                </div>

                                                {{-- Кнопки действий (TODO) --}}
                                                {{-- TODO: Реализовать добавление в корзину позже --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- ПАГИНАЦИЯ --}}
                        @if(method_exists($latestProducts, 'links'))
                            <div class="pagination-section">
                                {{ $latestProducts->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>
                        @endif

                    @else
                        {{-- Нет товаров --}}
                        <div class="no-products glass text-center py-5">
                            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                            <h3>Товары не найдены</h3>
                            <p class="text-muted">Попробуйте изменить параметры фильтрации</p>
                            <a href="{{ route('catalog') }}" class="btn-primary">
                                <i class="fas fa-redo-alt"></i> Сбросить фильтры
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Сохраняем текущие параметры для JS
    window.catalogFilters = {
        minPrice: {{ request('min_price', 0) }},
        maxPrice: {{ request('max_price', 100000) }},
        sort: '{{ request('sort', 'newest') }}',
        view: localStorage.getItem('catalogView') || 'grid'
    };

    // TODO: Добавить обработчики для фильтров
    document.addEventListener('DOMContentLoaded', function() {
        // Переключение между плиткой и списком
        const viewBtns = document.querySelectorAll('.view-btn');
        const productsGrid = document.getElementById('productsGrid');
        const productsList = document.getElementById('productsList');

        if (viewBtns.length && productsGrid && productsList) {
            viewBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const view = this.dataset.view;
                    viewBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    if (view === 'grid') {
                        productsGrid.style.display = 'grid';
                        productsList.style.display = 'none';
                    } else {
                        productsGrid.style.display = 'none';
                        productsList.style.display = 'block';
                    }

                    localStorage.setItem('catalogView', view);
                });
            });
        }

        // Инициализация начального вида
        if (window.catalogFilters.view === 'list') {
            const listBtn = document.querySelector('.view-btn[data-view="list"]');
            if (listBtn) listBtn.click();
        }
    });
</script>
@endsection
