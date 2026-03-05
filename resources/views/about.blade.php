@extends('home')

@section('title', 'О нас')

@section('content')
    <section class="breadcrumb-section">
        <div class="win-container">
            <div class="breadcrumb-container glass">
                <a href="/"><i class="fas fa-home"></i> Главная</a>
                <i class="fas fa-chevron-right"></i>
                <span class="current">О нас</span>
            </div>
        </div>
    </section>

    {{-- Hero секция О нас --}}
    <section class="about-hero">
        <div class="win-container">
            <div class="about-hero-content glass text-center">
                <h1>О компании <span class="accent">Инструменты.Про</span></h1>
                <p class="about-hero-text">
                    Мы — команда профессионалов, которые знают об инструментах всё. 
                    Более 15 лет помогаем строителям и мастерам находить идеальный инструмент.
                </p>
            </div>
        </div>
    </section>

    {{-- Наши преимущества --}}
    <section class="about-features">
        <div class="win-container">
            <h2 class="section-title text-center">Почему выбирают <span class="accent">нас</span></h2>
            
            <div class="features-grid-about">
                <div class="feature-about-card glass">
                    <div class="feature-about-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h3>15+ лет опыта</h3>
                    <p>Работаем с 2009 года. За это время обслужили более 25 000 клиентов</p>
                </div>
                
                <div class="feature-about-card glass">
                    <div class="feature-about-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3>8000+ товаров</h3>
                    <p>Огромный ассортимент от топовых мировых брендов</p>
                </div>
                
                <div class="feature-about-card glass">
                    <div class="feature-about-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>3 года гарантии</h3>
                    <p>На весь электроинструмент. Дополнительная гарантия от магазина</p>
                </div>
                
                <div class="feature-about-card glass">
                    <div class="feature-about-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Быстрая доставка</h3>
                    <p>Доставляем по всей России. В день заказа по городу</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Наша история --}}
    <section class="about-history">
        <div class="win-container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="history-content glass">
                        <h2 class="section-title">Наша <span class="accent">история</span></h2>
                        <p>Всё началось в 2009 году с небольшого магазинчика на окраине города. Мы начинали с 50 наименований инструментов, но всегда делали упор на качество и сервис.</p>
                        <p>Сегодня Инструменты.Про — это федеральная сеть с 15 магазинами в 10 городах России. Но мы остаёмся той же командой увлечённых профессионалов, которые любят своё дело.</p>
                        
                        <div class="history-stats">
                            <div class="history-stat-item">
                                <div class="history-stat-value">2009</div>
                                <div class="history-stat-label">Год основания</div>
                            </div>
                            <div class="history-stat-item">
                                <div class="history-stat-value">15</div>
                                <div class="history-stat-label">Магазинов</div>
                            </div>
                            <div class="history-stat-item">
                                <div class="history-stat-value">50+</div>
                                <div class="history-stat-label">Сотрудников</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="history-image glass">
                        <img src="https://via.placeholder.com/600x400/0067c0/ffffff?text=Наша+команда" alt="Наша команда">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Наша команда --}}
    <section class="about-team">
        <div class="win-container">
            <h2 class="section-title text-center">Наша <span class="accent">команда</span></h2>
            
            <div class="team-grid">
                <div class="team-card glass">
                    <div class="team-photo">
                        <img src="https://via.placeholder.com/300x300/0067c0/ffffff?text=Алексей" alt="Алексей">
                    </div>
                    <h3>Алексей Петров</h3>
                    <p class="team-position">Основатель и генеральный директор</p>
                    <p class="team-desc">Более 20 лет в инструментальной отрасли. Знает о перфораторах всё.</p>
                    <div class="team-social">
                        <a href="#"><i class="fab fa-telegram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="team-card glass">
                    <div class="team-photo">
                        <img src="https://via.placeholder.com/300x300/d83b01/ffffff?text=Елена" alt="Елена">
                    </div>
                    <h3>Елена Смирнова</h3>
                    <p class="team-position">Руководитель отдела закупок</p>
                    <p class="team-desc">Отбирает только лучшие инструменты от проверенных брендов.</p>
                    <div class="team-social">
                        <a href="#"><i class="fab fa-telegram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="team-card glass">
                    <div class="team-photo">
                        <img src="https://via.placeholder.com/300x300/107c10/ffffff?text=Дмитрий" alt="Дмитрий">
                    </div>
                    <h3>Дмитрий Иванов</h3>
                    <p class="team-position">Технический эксперт</p>
                    <p class="team-desc">Поможет выбрать инструмент под любые задачи. Проводит мастер-классы.</p>
                    <div class="team-social">
                        <a href="#"><i class="fab fa-telegram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="team-card glass">
                    <div class="team-photo">
                        <img src="https://via.placeholder.com/300x300/c30052/ffffff?text=Мария" alt="Мария">
                    </div>
                    <h3>Мария Соколова</h3>
                    <p class="team-position">Руководитель отдела сервиса</p>
                    <p class="team-desc">Заботится о том, чтобы каждый клиент остался доволен.</p>
                    <div class="team-social">
                        <a href="#"><i class="fab fa-telegram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Контакты и карта --}}
    <section class="contacts-section">
        <div class="win-container">
            <h2 class="section-title text-center">Как нас <span class="accent">найти</span></h2>
            
            <div class="row">
                <div class="col-lg-4">
                    <div class="contacts-info glass">
                        <h3>Контактная информация</h3>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Телефон</h4>
                                <p><a href="tel:+78005553535">8-800-555-35-35</a></p>
                                <p class="small">(круглосуточно, бесплатно)</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Email</h4>
                                <p><a href="mailto:info@instrument.pro">info@instrument.pro</a></p>
                                <p class="small">Ответим в течение часа</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Адрес</h4>
                                <p>г. Москва, ул. Строителей, д. 15</p>
                                <p class="small">м. "Строительная", выход №2</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Режим работы</h4>
                                <p>Пн-Пт: 9:00 - 21:00</p>
                                <p>Сб-Вс: 10:00 - 20:00</p>
                            </div>
                        </div>
                        
                        <div class="contact-social">
                            <h4>Мы в соцсетях</h4>
                            <div class="social-links">
                                <a href="#" class="glass"><i class="fab fa-telegram"></i></a>
                                <a href="#" class="glass"><i class="fab fa-vk"></i></a>
                                <a href="#" class="glass"><i class="fab fa-youtube"></i></a>
                                <a href="#" class="glass"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="map-container glass">
                        <div id="yandexMap" style="width: 100%; height: 450px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Обратная связь --}}
    <section class="feedback-section">
        <div class="win-container">
            <div class="feedback-card glass">
                <div class="row">
                    <div class="col-lg-6">
                        <h3 class="feedback-title">Остались вопросы?</h3>
                        <p class="feedback-text">Напишите нам, и мы ответим в ближайшее время</p>
                    </div>
                    <div class="col-lg-6">
                        <form class="feedback-form" id="feedbackForm">
                            <div class="form-group">
                                <input type="text" class="form-control glass" placeholder="Ваше имя" required>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control glass" placeholder="Email" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control glass" rows="3" placeholder="Ваш вопрос" required></textarea>
                            </div>
                            <button type="submit" class="btn-submit">Отправить <i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://api-maps.yandex.ru/2.1/?apikey=ваш-ключ&lang=ru_RU"></script>
    @endsection