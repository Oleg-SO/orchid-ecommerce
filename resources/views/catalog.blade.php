@extends('home')

@section('title', 'Каталог товаров')

@section('content')
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
                                           placeholder="Название товара...">
                                    <i class="fas fa-search search-filter-icon"></i>
                                </div>
                            </div>
                        </div>

                        {{-- ФИЛЬТР ПО КАТЕГОРИЯМ --}}
                        <div class="filter-section">
                            <h4 class="filter-section-title">
                                Категории
                                <i class="fas fa-chevron-down"></i>
                            </h4>
                            <div class="filter-section-content">
                                <div class="categories-list" id="categoriesList">
                                    {{-- Категории будут добавляться через JS --}}
                                    @foreach($popularCategories as $category)
                                        <label class="category-checkbox glass-option">
                                            <input type="checkbox" name="category" value="elektro">
                                            <span class="checkmark"></span>
                                            <span class="category-name">{{ $category->name }}</span>
                                            <span class="category-count">{{ $category->products_count ?? 0 }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- ФИЛЬТР ПО ЦЕНЕ --}}
                        <div class="filter-section">
                            <h4 class="filter-section-title">
                                Цена
                                <i class="fas fa-chevron-down"></i>
                            </h4>
                            <div class="filter-section-content">
                                <div class="price-slider-container">
                                    <div id="priceRangeSlider" class="price-slider"></div>
                                    <div class="price-inputs">
                                        <div class="price-input-group">
                                            <span>от</span>
                                            <input type="number" id="minPrice" value="0" placeholder="0">
                                        </div>
                                        <div class="price-input-group">
                                            <span>до</span>
                                            <input type="number" id="maxPrice" value="100000" placeholder="100000">
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
                                    <input type="checkbox" name="in_stock" value="1">
                                    <span class="checkmark"></span>
                                    <span class="in-stock-text">Только в наличии</span>
                                </label>
                            </div>
                        </div>

                        {{-- ФИЛЬТР ПО БРЕНДАМ --}}
                        <div class="filter-section">
                            <h4 class="filter-section-title">
                                Бренды
                                <i class="fas fa-chevron-down"></i>
                            </h4>
                            <div class="filter-section-content">
                                <div class="brands-list" id="brandsList">
                                    <label class="brand-checkbox glass-option">
                                        <input type="checkbox" name="brand" value="bosch">
                                        <span class="checkmark"></span>
                                        <span class="brand-name">BOSCH</span>
                                    </label>
                                    <label class="brand-checkbox glass-option">
                                        <input type="checkbox" name="brand" value="makita">
                                        <span class="checkmark"></span>
                                        <span class="brand-name">Makita</span>
                                    </label>
                                    <label class="brand-checkbox glass-option">
                                        <input type="checkbox" name="brand" value="dewalt">
                                        <span class="checkmark"></span>
                                        <span class="brand-name">DeWALT</span>
                                    </label>
                                    <label class="brand-checkbox glass-option">
                                        <input type="checkbox" name="brand" value="metabo">
                                        <span class="checkmark"></span>
                                        <span class="brand-name">Metabo</span>
                                    </label>
                                </div>
                            </div>
                        </div>

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
                                <option value="newest">По новизне</option>
                                <option value="price_asc">По цене (возрастание)</option>
                                <option value="price_desc">По цене (убывание)</option>
                                <option value="name_asc">По названию (А-Я)</option>
                                <option value="name_desc">По названию (Я-А)</option>
                                <option value="popular">По популярности</option>
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

                    {{-- СПИСОК ТОВАРОВ (плитка) --}}
                        <div class="products-grid" id="productsGrid">
                            @foreach ($latestProducts as $product)
                                {{-- Карточка товара 1 --}}
                                <div class="product-card-wrapper">
                                    <div class="product-card glass">
                                        <div class="product-image-wrapper">
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
                                            <span class="product-badge new">Новинка</span>
                                            <span class="product-badge discount">-15%</span>
                                        </div>
                                        <div class="product-info">
                                            <h3 class="product-title">
                                                <a href="/catalog/perforator-bosch">{{ $product->name }}</a>
                                            </h3>
                                            <div class="product-categories">
                                                <span class="product-category">{{ $category->name }}</span>
                                            </div>
                                            <div class="product-rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                                <span class="rating-count">(124)</span>
                                            </div>
                                            <div class="product-price-block">
                                                <span class="current-price">{{ $product->price }}</span>
                                                @if($product->old_price)
                                                    <span class="old-price">{{ $product->old_price }}</span>
                                                @endif
                                            </div>
                                            <div class="product-actions">
                                                <button class="btn-add-to-cart" data-product-id="1">
                                                    <i class="fas fa-cart-plus"></i> В корзину
                                                </button>
                                                <button class="btn-wishlist" data-product-id="1">
                                                    <i class="far fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- СПИСОК ТОВАРОВ (для list-режима) --}}
                                <div class="products-list" id="productsList" style="display: none;">
                                    {{-- Карточка товара в виде списка --}}
                                    <div class="product-list-item glass">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="product-list-image">
                                                    <img src="https://via.placeholder.com/300x200/0067c0/ffffff?text=Перфоратор" alt="Перфоратор BOSCH">
                                                    <span class="product-badge new">Новинка</span>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="product-list-info">
                                                    <h3><a href="/catalog/perforator-bosch">Перфоратор BOSCH GBH 2-28</a></h3>
                                                    <div class="product-list-category">Электроинструмент</div>
                                                    <div class="product-list-rating">
                                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i> (124)
                                                    </div>
                                                    <p class="product-list-description">Мощный перфоратор для профессионального использования. Три режима работы: сверление, удар, долбление.</p>
                                                    <div class="product-list-price">
                                                        <span class="current-price">12 490 ₽</span>
                                                        <span class="old-price">14 990 ₽</span>
                                                    </div>
                                                    <div class="product-list-actions">
                                                        <button class="btn-add-to-cart" data-product-id="1">
                                                            <i class="fas fa-cart-plus"></i> В корзину
                                                        </button>
                                                        <button class="btn-wishlist" data-product-id="1">
                                                            <i class="far fa-heart"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="product-list-item glass">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="product-list-image">
                                                    <img src="https://via.placeholder.com/300x200/d83b01/ffffff?text=Шуруповерт" alt="Шуруповерт Makita">
                                                    <span class="product-badge hit">Хит</span>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="product-list-info">
                                                    <h3><a href="/catalog/shurupovert-makita">Шуруповерт Makita DF457D</a></h3>
                                                    <div class="product-list-category">Электроинструмент</div>
                                                    <div class="product-list-rating">
                                                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> (89)
                                                    </div>
                                                    <p class="product-list-description">Аккумуляторный шуруповерт с двумя батареями в комплекте. Легкий и удобный для любых работ.</p>
                                                    <div class="product-list-price">
                                                        <span class="current-price">8 990 ₽</span>
                                                    </div>
                                                    <div class="product-list-actions">
                                                        <button class="btn-add-to-cart" data-product-id="2">
                                                            <i class="fas fa-cart-plus"></i> В корзину
                                                        </button>
                                                        <button class="btn-wishlist" data-product-id="2">
                                                            <i class="far fa-heart"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                    {{-- ПАГИНАЦИЯ --}}
                    <div class="pagination-section">
                        <nav aria-label="Page navigation">
                            <ul class="pagination glass">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
