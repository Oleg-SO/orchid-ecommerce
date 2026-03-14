{{-- resources/views/product.blade.php --}}
@extends('home')

@section('title', ($product->name ?? 'Товар') . ' | Инструменты.Про')
@section('description', ($product->name ?? 'Товар') . '. ' . ($product->short_description ?? '') . ' Купить с доставкой по России. Гарантия 3 года.')
@section('keywords', ($product->name ?? '') . ', ' . ($product->categories ? implode(', ', $product->categories->pluck('name')->toArray()) : '') . ', купить, инструмент')

@section('content')
{{-- Хлебные крошки (динамические) --}}
<section class="breadcrumb-section">
    <div class="win-container">
        <div class="breadcrumb-container glass">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> Главная</a>
            <i class="fas fa-chevron-right"></i>
            <a href="{{ route('catalog') }}">Каталог</a>

            @if($product->categories && $product->categories->count() > 0)
                @foreach($product->categories as $category)
                    <i class="fas fa-chevron-right"></i>
                    <a href="/catalog/{{ $category->slug }}">{{ $category->name }}</a>
                @endforeach
            @endif

            <i class="fas fa-chevron-right"></i>
            <span class="current">{{ $product->name ?? 'Товар' }}</span>
        </div>
    </div>
</section>

{{-- Основная информация о товаре --}}
<section class="product-main">
    <div class="win-container">
        <div class="row">
            {{-- Галерея товара --}}
            <div class="col-lg-6">
                <div class="product-gallery glass">
                    <div class="product-main-image">
                        @php $mainImage = $product->attachment()->first(); @endphp
                        @if($mainImage)
                            <img src="{{ $mainImage->url() }}"
                                 alt="{{ $product->name }}"
                                 id="mainProductImage">
                        @else
                            <img src="https://via.placeholder.com/600x400/0067c0/ffffff?text={{ urlencode($product->name ?? 'Нет фото') }}"
                                 alt="{{ $product->name }}">
                        @endif

                        <div class="product-badges">
                            @if(isset($product->is_hit) && $product->is_hit)
                                <span class="product-badge hit">Хит продаж</span>
                            @endif

                            @if(isset($product->old_price) && $product->old_price > $product->price)
                                <span class="product-badge discount">-{{ round((1 - $product->price/$product->old_price)*100) }}%</span>
                            @endif

                            @if(isset($product->is_new) && $product->is_new)
                                <span class="product-badge new">Новинка</span>
                            @endif
                        </div>
                    </div>

                    {{-- Миниатюры (если есть дополнительные фото) --}}
                    @if($product->attachments && $product->attachments->count() > 0)
                        <div class="product-thumbnails">
                            @foreach($product->attachments as $index => $attachment)
                                <div class="thumbnail {{ $index == 0 ? 'active' : '' }}"
                                     data-image="{{ $attachment->url() }}">
                                    <img src="{{ $attachment->url() }}" alt="{{ $product->name }} - фото {{ $index+1 }}">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Информация о товаре --}}
            <div class="col-lg-6">
                <div class="product-info-card glass">
                    <h1 class="product-title">{{ $product->name ?? 'Товар' }}</h1>

                    <div class="product-rating">
                        <div class="stars">
                            @php $rating = $product->rating ?? 0; @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($rating))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $rating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <a href="#reviews" class="rating-count">{{ $product->reviews_count ?? 0 }} отзывов</a>
                        @if(isset($product->article) && $product->article)
                            <span class="product-article">Артикул: {{ $product->article }}</span>
                        @elseif(isset($product->sku) && $product->sku)
                            <span class="product-article">Артикул: {{ $product->sku }}</span>
                        @endif
                    </div>

                    <div class="product-price-block">
                        <div class="current-price">{{ number_format($product->price ?? 0, 0, '.', ' ') }} ₽</div>
                        @if(isset($product->old_price) && $product->old_price)
                            <div class="old-price">{{ number_format($product->old_price, 0, '.', ' ') }} ₽</div>
                            <div class="price-save">Экономия {{ number_format($product->old_price - $product->price, 0, '.', ' ') }} ₽</div>
                        @endif
                    </div>

                    <div class="product-availability">
                        @if(isset($product->quantity) && $product->quantity > 0)
                            <span class="in-stock"><i class="fas fa-check-circle"></i> В наличии ({{ $product->quantity }} шт.)</span>
                        @else
                            <span class="out-of-stock"><i class="fas fa-times-circle"></i> Нет в наличии</span>
                        @endif
                    </div>

                    <div class="product-actions">
                        <div class="quantity-selector">
                            <button class="quantity-btn minus"><i class="fas fa-minus"></i></button>
                            <input type="number" value="1" min="1" max="{{ $product->quantity ?? 1 }}" class="quantity-input">
                            <button class="quantity-btn plus"><i class="fas fa-plus"></i></button>
                        </div>

                        <button class="btn-add-to-cart-large" data-product-id="{{ $product->id ?? '' }}">
                            <i class="fas fa-cart-plus"></i> Добавить в корзину
                        </button>

                        {{-- TODO: Реализовать избранное позже --}}
                        {{-- <button class="btn-wishlist-large" data-product-id="{{ $product->id }}">
                            <i class="far fa-heart"></i>
                        </button> --}}
                    </div>

                    <div class="product-delivery-info">
                        <div class="delivery-item">
                            <i class="fas fa-truck"></i>
                            <span>Доставка по городу: <strong>300 ₽</strong> (сегодня)</span>
                        </div>
                        <div class="delivery-item">
                            <i class="fas fa-store"></i>
                            <span>Самовывоз: <strong>бесплатно</strong> (через 1 час)</span>
                        </div>
                        <div class="delivery-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Гарантия: <strong>{{ $product->warranty ?? 1 }} лет</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Блок характеристик и описания --}}
<section class="product-details">
    <div class="win-container">
        <div class="row">
            <div class="col-lg-7">
                {{-- Описание товара --}}
                @if(isset($product->description) && $product->description)
                <div class="description-block glass">
                    <h2>Описание</h2>
                    <div class="description-content">
                        {!! $product->description !!}
                    </div>
                </div>
                @endif

                {{-- Характеристики --}}
                @if(isset($product->specifications) && $product->specifications->count() > 0)
                <div class="specs-block glass">
                    <h2>Характеристики</h2>

                    <table class="specs-table">
                        @foreach($product->specifications as $spec)
                            <tr>
                                <td>{{ $spec->name ?? '—' }}</td>
                                <td>{{ $spec->value ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                @else
                {{-- TODO: Добавить характеристики позже --}}
                {{--
                <div class="specs-block glass">
                    <h2>Характеристики</h2>
                    <p class="text-muted">Характеристики товара будут добавлены позже</p>
                </div>
                --}}
                @endif
            </div>

            <div class="col-lg-5">
                {{-- Бренд --}}
                @if(isset($product->brand) && $product->brand)
                <div class="brand-block glass">
                    <h2>О бренде</h2>

                    @if($product->brand->logo)
                        {{-- TODO: Добавить логику для логотипа бренда --}}
                        {{-- <img src="{{ $product->brand->logo->url() }}" alt="{{ $product->brand->name }}" class="brand-logo"> --}}
                    @endif
                    <h3>{{ $product->brand->name ?? 'Бренд' }}</h3>
                    <p>{{ $product->brand->description ?? 'Информация о бренде отсутствует' }}</p>
                    <a href="/brand/{{ $product->brand->slug ?? '' }}" class="brand-link">Все товары бренда <i class="fas fa-arrow-right"></i></a>
                </div>
                @else
                {{-- TODO: Добавить бренды позже --}}
                {{--
                <div class="brand-block glass">
                    <h2>О бренде</h2>
                    <p class="text-muted">Информация о бренде будет добавлена позже</p>
                </div>
                --}}
                @endif

                {{-- Преимущества --}}
                <div class="benefits-block glass">
                    <h2>Почему мы</h2>
                    <ul class="benefits-list">
                        <li><i class="fas fa-check-circle"></i> Оригинальный товар</li>
                        <li><i class="fas fa-check-circle"></i> Гарантия 3 года</li>
                        <li><i class="fas fa-check-circle"></i> Доставка по всей России</li>
                        <li><i class="fas fa-check-circle"></i> Бонусы за покупку</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Отзывы --}}
<section class="reviews-section" id="reviews">
    <div class="win-container">
        <h2 class="section-title">Отзывы <span class="accent">покупателей</span></h2>

        @if(isset($product->reviews) && $product->reviews->count() > 0)
        <div class="reviews-summary glass">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <div class="average-rating">{{ number_format($product->rating ?? 0, 1) }}</div>
                    <div class="average-stars">
                        @php $rating = $product->rating ?? 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($rating))
                                <i class="fas fa-star"></i>
                            @elseif($i - 0.5 <= $rating)
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <div class="reviews-total">на основе {{ $product->reviews_count ?? 0 }} отзывов</div>
                </div>

                <div class="col-md-9">
                    <div class="rating-bars">
                        @foreach([5,4,3,2,1] as $star)
                            <div class="rating-bar-item">
                                <span class="rating-label">{{ $star }} <i class="fas fa-star"></i></span>
                                <div class="rating-bar-bg">
                                    <div class="rating-bar-fill" style="width: {{ $reviewsStats['percentages'][$star] ?? 0 }}%"></div>
                                </div>
                                <span class="rating-count">{{ $reviewsStats['counts'][$star] ?? 0 }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="reviews-list">
            @foreach($product->reviews as $review)
                <div class="review-item glass">
                    <div class="review-header">
                        <div class="reviewer-info">
                            <span class="reviewer-name">{{ $review->user_name ?? 'Покупатель' }}</span>
                            <span class="review-date">{{ $review->created_at ? $review->created_at->format('d.m.Y') : '' }}</span>
                        </div>
                        <div class="review-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= ($review->rating ?? 0))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="review-content">
                        @if(isset($review->title) && $review->title)
                            <h4>{{ $review->title }}</h4>
                        @endif
                        <p>{{ $review->content ?? '' }}</p>
                    </div>
                    @if(isset($review->advantages) && $review->advantages)
                        <div class="review-advantages">+ {{ $review->advantages }}</div>
                    @endif
                    @if(isset($review->disadvantages) && $review->disadvantages)
                        <div class="review-disadvantages">- {{ $review->disadvantages }}</div>
                    @endif
                </div>
            @endforeach
        </div>
        @else
        <div class="no-reviews glass text-center py-5">
            <i class="fas fa-comment-dots fa-4x text-muted mb-3"></i>
            <h3>Пока нет отзывов</h3>
            <p class="text-muted">Будьте первым, кто оставит отзыв об этом товаре</p>
            <button class="btn-primary" id="showReviewForm">Написать отзыв</button>
        </div>
        @endif

        {{-- TODO: Реализовать форму отправки отзыва позже --}}
        {{--
        <div class="text-center">
            <button class="btn-secondary" id="showReviewForm">Написать отзыв</button>
        </div>
        --}}
    </div>
</section>

{{-- С этим товаром покупают --}}
@if(isset($alsoBought) && $alsoBought->count() > 0)
<section class="also-bought">
    <div class="win-container">
        <h2 class="section-title">С этим товаром <span class="accent">покупают</span></h2>

        <div class="products-slider">
            <div class="slider-track" id="alsoBoughtSlider">
                @foreach($alsoBought as $item)
                    <div class="product-card-wrapper">
                        <div class="product-card glass">
                            <div class="product-image-wrapper">
                                @php $itemImage = $item->attachment()->first(); @endphp
                                @if($itemImage)
                                    <img src="{{ $itemImage->url() }}" alt="{{ $item->name }}">
                                @else
                                    <img src="https://via.placeholder.com/300x200/0067c0/ffffff?text={{ urlencode($item->name ?? 'Товар') }}" alt="{{ $item->name }}">
                                @endif

                                @if(isset($item->old_price) && $item->old_price > $item->price)
                                    <span class="product-badge discount">-{{ round((1 - $item->price/$item->old_price)*100) }}%</span>
                                @endif
                            </div>

                            <div class="product-info">
                                <h3><a href="/product/{{ $item->slug ?? '' }}">{{ $item->name ?? 'Товар' }}</a></h3>
                                <div class="product-price">{{ number_format($item->price ?? 0, 0, '.', ' ') }} ₽</div>
                                {{-- TODO: Реализовать добавление в корзину --}}
                                {{-- <button class="btn-add-to-cart-small" data-product-id="{{ $item->id }}">
                                    <i class="fas fa-cart-plus"></i> Купить
                                </button> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- TODO: Реализовать слайдер позже --}}
            {{--
            <button class="slider-arrow prev" id="alsoBoughtPrev"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-arrow next" id="alsoBoughtNext"><i class="fas fa-chevron-right"></i></button>
            --}}
        </div>
    </div>
</section>
@endif

{{-- Похожие товары --}}
@if(isset($similarProducts) && $similarProducts->count() > 0)
<section class="similar-products">
    <div class="win-container">
        <h2 class="section-title">Похожие <span class="accent">товары</span></h2>

        <div class="products-grid">
            @foreach($similarProducts as $product)
                <div class="product-card-wrapper">
                    <div class="product-card glass">
                        <div class="product-image-wrapper">
                            @php $productImage = $product->attachment()->first(); @endphp
                            @if($productImage)
                                <img src="{{ $productImage->url() }}" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x200/0067c0/ffffff?text={{ urlencode($product->name ?? 'Товар') }}" alt="{{ $product->name }}">
                            @endif
                        </div>

                        <div class="product-info">
                            <h3><a href="/product/{{ $product->slug ?? '' }}">{{ $product->name ?? 'Товар' }}</a></h3>
                            <div class="product-price">{{ number_format($product->price ?? 0, 0, '.', ' ') }} ₽</div>
                            {{-- TODO: Реализовать добавление в корзину --}}
                            {{-- <button class="btn-add-to-cart-small" data-product-id="{{ $product->id }}">
                                <i class="fas fa-cart-plus"></i> Купить
                            </button> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Просмотренные товары --}}
@if(isset($viewedProducts) && $viewedProducts->count() > 0)
<section class="viewed-products">
    <div class="win-container">
        <h2 class="section-title">Вы <span class="accent">смотрели</span></h2>

        <div class="viewed-grid">
            @foreach($viewedProducts as $viewedProduct)
                <div class="viewed-item glass">
                    <a href="/product/{{ $viewedProduct->slug ?? '' }}">
                        @php $viewedImage = $viewedProduct->attachment()->first(); @endphp
                        @if($viewedImage)
                            <img src="{{ $viewedImage->url() }}" alt="{{ $viewedProduct->name }}">
                        @else
                            <img src="https://via.placeholder.com/100x100/0067c0/ffffff?text={{ urlencode($viewedProduct->name ?? 'Товар') }}" alt="{{ $viewedProduct->name }}">
                        @endif
                        <span>{{ $viewedProduct->name ?? 'Товар' }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@section('scripts')
<script src="{{ asset('js/product.js') }}"></script>
<script>
    window.productData = {
        id: {{ $product->id ?? 0 }},
        name: '{{ $product->name ?? '' }}',
        price: {{ $product->price ?? 0 }},
        quantity: {{ $product->quantity ?? 0 }}
    };
</script>
{{-- TODO: Добавить скрипты для отзывов, корзины и слайдера позже --}}
@endsection
