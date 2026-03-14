
@extends('home')

@section('title', 'Контакты | Инструменты.Про')
@section('description', 'Контакты интернет-магазина Инструменты.Про. Адрес, телефон, email, режим работы.')

@section('content')
{{-- Хлебные крошки --}}
<section class="breadcrumb-section">
    <div class="win-container">
        <div class="breadcrumb-container glass">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> Главная</a>
            <i class="fas fa-chevron-right"></i>
            <span class="current">Контакты</span>
        </div>
    </div>
</section>

{{-- Заголовок страницы --}}
<section class="contacts-header">
    <div class="win-container">
        <div class="contacts-header-content glass text-center">
            <h1>Наши <span class="accent">контакты</span></h1>
            <p class="contacts-header-text">
                Мы всегда на связи! Звоните, пишите или приходите к нам в гости.
            </p>
        </div>
    </div>
</section>

{{-- Основная информация --}}
<section class="contacts-info-section">
    <div class="win-container">
        <div class="row">
            {{-- Левая колонка с контактами --}}
            <div class="col-lg-4">
                <div class="contacts-card glass">
                    <h2 class="contacts-card-title">Свяжитесь с нами</h2>

                    {{-- Телефон --}}
                    <div class="contact-item-large">
                        <div class="contact-icon-large">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details-large">
                            <h3>Телефон</h3>
                            <a href="tel:+78005553535" class="contact-phone">8-800-555-35-35</a>
                            <p class="contact-note">Круглосуточно, бесплатно по России</p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="contact-item-large">
                        <div class="contact-icon-large">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details-large">
                            <h3>Email</h3>
                            <a href="mailto:info@instrument.pro" class="contact-email">info@instrument.pro</a>
                            <p class="contact-note">Ответим в течение часа</p>
                        </div>
                    </div>

                    {{-- Адрес --}}
                    <div class="contact-item-large">
                        <div class="contact-icon-large">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details-large">
                            <h3>Адрес</h3>
                            <p class="contact-address">г. Москва, ул. Строителей, д. 15</p>
                            <p class="contact-note">м. "Строительная", выход №2</p>
                        </div>
                    </div>

                    {{-- Режим работы --}}
                    <div class="contact-item-large">
                        <div class="contact-icon-large">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details-large">
                            <h3>Режим работы</h3>
                            <div class="work-schedule">
                                <div class="schedule-row">
                                    <span>Пн - Пт:</span>
                                    <span class="schedule-time">9:00 - 21:00</span>
                                </div>
                                <div class="schedule-row">
                                    <span>Сб - Вс:</span>
                                    <span class="schedule-time">10:00 - 20:00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Социальные сети --}}
                    <div class="contact-social-large">
                        <h3>Мы в соцсетях</h3>
                        <div class="social-links-large">
                            <a href="#" class="glass" title="Telegram"><i class="fab fa-telegram"></i></a>
                            <a href="#" class="glass" title="VK"><i class="fab fa-vk"></i></a>
                            <a href="#" class="glass" title="YouTube"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="glass" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Правая колонка - форма обратной связи --}}
            <div class="col-lg-8">
                <div class="feedback-card glass">
                    <h2 class="feedback-card-title">Напишите нам</h2>
                    <p class="feedback-card-subtitle">Заполните форму и мы свяжемся с вами</p>

                    <form class="feedback-form-large" id="contactsForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control glass" name="name" placeholder="Ваше имя *" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" class="form-control glass" name="email" placeholder="Email *" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="tel" class="form-control glass" name="phone" placeholder="Телефон">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control glass" name="subject">
                                        <option value="">Тема обращения</option>
                                        <option value="question">Вопрос о товаре</option>
                                        <option value="order">Заказ</option>
                                        <option value="delivery">Доставка</option>
                                        <option value="cooperation">Сотрудничество</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control glass" name="message" rows="4" placeholder="Ваше сообщение *" required></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="privacyCheck" checked required>
                                    <label class="form-check-label" for="privacyCheck">
                                        Согласен на обработку персональных данных
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-submit-large">
                                    <i class="fas fa-paper-plane"></i> Отправить сообщение
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Карта от Яндекс.Конструктора --}}
<section class="map-section">
    <div class="win-container">
        <h2 class="section-title text-center">Как <span class="accent">добраться</span></h2>

        <div class="map-container glass">
            {{-- Вариант 1: iframe --}}
            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3Ad1505cc0e55eac01f60a54823d882ddb055a045a39e384214e783589c912627c&amp;source=constructor"
                    width="100%"
                    height="450"
                    frameborder="0"
                    style="border-radius: 24px;">
            </iframe>

            {{-- Вариант 2: скрипт (закомментирован, если нужен - раскомментируйте) --}}
            {{-- <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Ad1505cc0e55eac01f60a54823d882ddb055a045a39e384214e783589c912627c&amp;width=100%25&amp;height=450&amp;lang=ru_RU&amp;scroll=true"></script> --}}
        </div>
    </div>
</section>

{{-- Как добраться (текстовая информация) --}}
<section class="how-to-reach">
    <div class="win-container">
        <div class="row">
            <div class="col-md-4">
                <div class="reach-card glass">
                    <div class="reach-icon">
                        <i class="fas fa-subway"></i>
                    </div>
                    <h3>На метро</h3>
                    <p>Станция "Строительная", выход №2. После выхода из метро поверните налево, пройдите 300 метров до перекрестка. Наш магазин будет справа.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="reach-card glass">
                    <div class="reach-icon">
                        <i class="fas fa-bus"></i>
                    </div>
                    <h3>Наземный транспорт</h3>
                    <p>Автобусы: 15, 27, 42 до остановки "Улица Строителей". Троллейбусы: 8, 12.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="reach-card glass">
                    <div class="reach-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3>На автомобиле</h3>
                    <p>Бесплатная парковка перед магазином. Въезд со стороны улицы Строителей.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/contacts.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/contacts.js') }}"></script>
@endsection
