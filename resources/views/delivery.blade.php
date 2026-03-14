{{-- resources/views/delivery.blade.php --}}
@extends('home')

@section('title', 'Доставка | Инструменты.Про - Быстрая доставка инструментов')
@section('description', 'Условия доставки интернет-магазина Инструменты.Про. Доставка по городу, межгород, по России. Сроки и стоимость доставки.')
@section('keywords', 'доставка инструментов, курьерская доставка, самовывоз, транспортные компании, сроки доставки')

@section('content')
{{-- Хлебные крошки --}}
<section class="breadcrumb-section">
    <div class="win-container">
        <div class="breadcrumb-container glass">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> Главная</a>
            <i class="fas fa-chevron-right"></i>
            <span class="current">Доставка</span>
        </div>
    </div>
</section>

{{-- Заголовок страницы --}}
<section class="delivery-header">
    <div class="win-container">
        <div class="delivery-header-content glass text-center">
            <h1>Доставка <span class="accent">инструментов</span></h1>
            <p class="delivery-header-text">
                Быстрая и надежная доставка по городу и всей России
            </p>
        </div>
    </div>
</section>

{{-- Способы доставки (основные) --}}
<section class="delivery-methods">
    <div class="win-container">
        <h2 class="section-title text-center">Способы <span class="accent">доставки</span></h2>

        <div class="methods-grid">
            <div class="method-card glass">
                <div class="method-icon">
                    <i class="fas fa-bicycle"></i>
                </div>
                <h3>Курьером по городу</h3>
                <div class="method-price">от 300 ₽</div>
                <ul class="method-features">
                    <li><i class="fas fa-check"></i> Доставка день в день</li>
                    <li><i class="fas fa-check"></i> Оплата при получении</li>
                    <li><i class="fas fa-check"></i> Примерка инструмента</li>
                </ul>
                <div class="method-time">Срок: 1-2 часа</div>
            </div>

            <div class="method-card glass popular">
                <div class="method-badge">Самый популярный</div>
                <div class="method-icon">
                    <i class="fas fa-store"></i>
                </div>
                <h3>Самовывоз</h3>
                <div class="method-price">Бесплатно</div>
                <ul class="method-features">
                    <li><i class="fas fa-check"></i> 5 точек самовывоза</li>
                    <li><i class="fas fa-check"></i> Осмотр товара перед покупкой</li>
                    <li><i class="fas fa-check"></i> Консультация специалиста</li>
                </ul>
                <div class="method-time">Срок: через 1 час</div>
            </div>

            <div class="method-card glass">
                <div class="method-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Доставка по области</h3>
                <div class="method-price">от 500 ₽</div>
                <ul class="method-features">
                    <li><i class="fas fa-check"></i> В радиусе 50 км</li>
                    <li><i class="fas fa-check"></i> Доставка до двери</li>
                    <li><i class="fas fa-check"></i> Отслеживание заказа</li>
                </ul>
                <div class="method-time">Срок: 1-2 дня</div>
            </div>

            <div class="method-card glass">
                <div class="method-icon">
                    <i class="fas fa-plane"></i>
                </div>
                <h3>В регионы России</h3>
                <div class="method-price">от 800 ₽</div>
                <ul class="method-features">
                    <li><i class="fas fa-check"></i> ТК "Деловые линии"</li>
                    <li><i class="fas fa-check"></i> ТК "ПЭК"</li>
                    <li><i class="fas fa-check"></i> ТК "СДЭК"</li>
                </ul>
                <div class="method-time">Срок: 3-7 дней</div>
            </div>
        </div>
    </div>
</section>

{{-- Тарифы по городу --}}
<section class="city-delivery">
    <div class="win-container">
        <h2 class="section-title text-center">Доставка <span class="accent">по городу</span></h2>

        <div class="delivery-table glass">
            <table class="price-table">
                <thead>
                    <tr>
                        <th>Зона доставки</th>
                        <th>Сумма заказа</th>
                        <th>Стоимость</th>
                        <th>Срок</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Центр</td>
                        <td>до 5 000 ₽</td>
                        <td>300 ₽</td>
                        <td>1-2 часа</td>
                    </tr>
                    <tr>
                        <td>Центр</td>
                        <td>от 5 000 ₽</td>
                        <td class="free">Бесплатно</td>
                        <td>1-2 часа</td>
                    </tr>
                    <tr>
                        <td>Северный район</td>
                        <td>до 5 000 ₽</td>
                        <td>350 ₽</td>
                        <td>2-3 часа</td>
                    </tr>
                    <tr>
                        <td>Северный район</td>
                        <td>от 5 000 ₽</td>
                        <td class="free">Бесплатно</td>
                        <td>2-3 часа</td>
                    </tr>
                    <tr>
                        <td>Южный район</td>
                        <td>до 5 000 ₽</td>
                        <td>350 ₽</td>
                        <td>2-3 часа</td>
                    </tr>
                    <tr>
                        <td>Южный район</td>
                        <td>от 5 000 ₽</td>
                        <td class="free">Бесплатно</td>
                        <td>2-3 часа</td>
                    </tr>
                    <tr>
                        <td>Западный район</td>
                        <td>до 5 000 ₽</td>
                        <td>400 ₽</td>
                        <td>3-4 часа</td>
                    </tr>
                    <tr>
                        <td>Западный район</td>
                        <td>от 5 000 ₽</td>
                        <td class="free">Бесплатно</td>
                        <td>3-4 часа</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="delivery-note glass">
            <i class="fas fa-info-circle"></i>
            <p>При заказе от 10 000 ₽ - бесплатная доставка по всему городу независимо от района</p>
        </div>
    </div>
</section>

{{-- Межгород и регионы --}}
<section class="intercity-delivery">
    <div class="win-container">
        <h2 class="section-title text-center">Доставка <span class="accent">межгород и регионы</span></h2>

        <div class="row">
            <div class="col-lg-6">
                <div class="region-card glass">
                    <h3><i class="fas fa-city"></i> Областные центры</h3>
                    <div class="region-list">
                        <div class="region-item">
                            <span>Москва</span>
                            <span class="region-price">от 800 ₽</span>
                            <span class="region-time">2-3 дня</span>
                        </div>
                        <div class="region-item">
                            <span>Санкт-Петербург</span>
                            <span class="region-price">от 900 ₽</span>
                            <span class="region-time">3-4 дня</span>
                        </div>
                        <div class="region-item">
                            <span>Казань</span>
                            <span class="region-price">от 1000 ₽</span>
                            <span class="region-time">4-5 дней</span>
                        </div>
                        <div class="region-item">
                            <span>Екатеринбург</span>
                            <span class="region-price">от 1100 ₽</span>
                            <span class="region-time">5-6 дней</span>
                        </div>
                        <div class="region-item">
                            <span>Новосибирск</span>
                            <span class="region-price">от 1300 ₽</span>
                            <span class="region-time">6-7 дней</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="region-card glass">
                    <h3><i class="fas fa-truck-moving"></i> Транспортные компании</h3>
                    <div class="tc-list">
                        <div class="tc-item">
                            <img src="https://via.placeholder.com/100x40/0067c0/ffffff?text=DL" alt="Деловые линии">
                            <div class="tc-info">
                                <div class="tc-name">Деловые линии</div>
                                <div class="tc-desc">Отправка ежедневно, отслеживание по номеру</div>
                            </div>
                            <div class="tc-price">от 800 ₽</div>
                        </div>
                        <div class="tc-item">
                            <img src="https://via.placeholder.com/100x40/0067c0/ffffff?text=PEK" alt="ПЭК">
                            <div class="tc-info">
                                <div class="tc-name">ПЭК</div>
                                <div class="tc-desc">Страхование груза, быстрая доставка</div>
                            </div>
                            <div class="tc-price">от 900 ₽</div>
                        </div>
                        <div class="tc-item">
                            <img src="https://via.placeholder.com/100x40/0067c0/ffffff?text=CDEK" alt="СДЭК">
                            <div class="tc-info">
                                <div class="tc-name">СДЭК</div>
                                <div class="tc-desc">Доставка до пункта выдачи или двери</div>
                            </div>
                            <div class="tc-price">от 750 ₽</div>
                        </div>
                        <div class="tc-item">
                            <img src="https://via.placeholder.com/100x40/0067c0/ffffff?text=Почта" alt="Почта России">
                            <div class="tc-info">
                                <div class="tc-name">Почта России</div>
                                <div class="tc-desc">Для отдаленных регионов, 1 класс</div>
                            </div>
                            <div class="tc-price">от 500 ₽</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Пункты самовывоза --}}
<section class="pickup-points">
    <div class="win-container">
        <h2 class="section-title text-center">Пункты <span class="accent">самовывоза</span></h2>

        <div class="row">
            <div class="col-md-6">
                <div class="pickup-card glass">
                    <h3>Главный офис</h3>
                    <p><i class="fas fa-map-pin"></i> ул. Строителей, 15</p>
                    <p><i class="fas fa-clock"></i> Пн-Пт: 9:00-21:00, Сб-Вс: 10:00-20:00</p>
                    <p><i class="fas fa-subway"></i> м. Строительная, 5 мин</p>
                    <p><i class="fas fa-parking"></i> Бесплатная парковка</p>
                    <div class="pickup-status available">Готовность заказа: 1 час</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="pickup-card glass">
                    <h3>Северный филиал</h3>
                    <p><i class="fas fa-map-pin"></i> пр. Северный, 42</p>
                    <p><i class="fas fa-clock"></i> Пн-Вс: 10:00-21:00</p>
                    <p><i class="fas fa-bus"></i> ост. "Северный рынок"</p>
                    <p><i class="fas fa-parking"></i> Парковка 30 мин бесплатно</p>
                    <div class="pickup-status available">Готовность заказа: 2 часа</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Сроки доставки --}}
<section class="delivery-terms">
    <div class="win-container">
        <h2 class="section-title text-center">Сроки <span class="accent">доставки</span></h2>

        <div class="terms-grid">
            <div class="term-item glass">
                <div class="term-icon"><i class="fas fa-clock"></i></div>
                <div class="term-content">
                    <h3>По городу</h3>
                    <p>При заказе до 16:00 - доставка сегодня. После 16:00 - завтра с 10:00</p>
                </div>
            </div>

            <div class="term-item glass">
                <div class="term-icon"><i class="fas fa-calendar"></i></div>
                <div class="term-content">
                    <h3>По области</h3>
                    <p>На следующий день при заказе до 14:00. Доставка 2 раза в неделю</p>
                </div>
            </div>

            <div class="term-item glass">
                <div class="term-icon"><i class="fas fa-train"></i></div>
                <div class="term-content">
                    <h3>Межгород</h3>
                    <p>Отправка ТК в течение 24 часов после оплаты. Трек-номер высылается СМС</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Часто задаваемые вопросы о доставке --}}
<section class="delivery-faq">
    <div class="win-container">
        <h2 class="section-title text-center">Вопросы о <span class="accent">доставке</span></h2>

        <div class="faq-accordion">
            <div class="faq-item glass">
                <div class="faq-question">
                    <span>Можно ли изменить адрес доставки?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Да, вы можете изменить адрес доставки до момента передачи заказа курьеру. Для этого свяжитесь с нами по телефону 8-800-555-35-35.
                </div>
            </div>

            <div class="faq-item glass">
                <div class="faq-question">
                    <span>Что делать, если курьер опоздал?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Если курьер задерживается более чем на 30 минут от согласованного времени, вы получите компенсацию 500 ₽ на бонусный счет.
                </div>
            </div>

            <div class="faq-item glass">
                <div class="faq-question">
                    <span>Можно ли проверить инструмент перед оплатой?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Да, вы можете проверить товар при курьере или в пункте самовывоза. Включить, проверить комплектацию.
                </div>
            </div>

            <div class="faq-item glass">
                <div class="faq-question">
                    <span>Как отследить заказ?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    После отправки заказа ТК мы пришлем трек-номер по СМС и email. Отслеживать можно на сайте ТК или у нас в личном кабинете.
                </div>
            </div>

            <div class="faq-item glass">
                <div class="faq-question">
                    <span>Доставляете ли вы в труднодоступные районы?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Да, отправляем Почтой России в любые населенные пункты. Сроки уточняйте у оператора.
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Схема работы --}}
<section class="delivery-process">
    <div class="win-container">
        <h2 class="section-title text-center">Как мы <span class="accent">доставляем</span></h2>

        <div class="process-steps">
            <div class="step glass">
                <div class="step-number">1</div>
                <i class="fas fa-shopping-cart"></i>
                <h3>Вы оформляете заказ</h3>
                <p>На сайте или по телефону</p>
            </div>

            <div class="step glass">
                <div class="step-number">2</div>
                <i class="fas fa-box"></i>
                <h3>Мы собираем заказ</h3>
                <p>Проверяем комплектацию</p>
            </div>

            <div class="step glass">
                <div class="step-number">3</div>
                <i class="fas fa-phone"></i>
                <h3>Звоним для подтверждения</h3>
                <p>Согласовываем время</p>
            </div>

            <div class="step glass">
                <div class="step-number">4</div>
                <i class="fas fa-truck"></i>
                <h3>Доставляем</h3>
                <p>Курьером или ТК</p>
            </div>

            <div class="step glass">
                <div class="step-number">5</div>
                <i class="fas fa-hand-holding-usd"></i>
                <h3>Вы оплачиваете</h3>
                <p>При получении или онлайн</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/delivery.js') }}"></script>
@endsection
