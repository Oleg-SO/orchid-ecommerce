{{-- Навигация в стиле Windows 11 --}}
<nav class="win-nav">
    <div class="win-container nav-container">
        <div class="logo-area">
            <div class="logo-icon">
                <i class="fas fa-tools"></i>
            </div>
            <span class="logo-text">Инструменты.<span class="accent">Про</span></span>
        </div>

        <div class="nav-links" id="navLinks">
            <a href="/" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home"></i> Главная</a>
            <a href="/catalog" class="{{ request()->routeIs('catalog*') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Каталог</a>
            <a href="/delivery" class="{{ request()->routeIs('delivery') ? 'active' : '' }}"><i class="fas fa-tag"></i> Доставка</a>
            <a href="/about" class="{{ request()->routeIs('about') ? 'active' : '' }}"><i class="fas fa-info-circle"></i> О нас</a>
            <a href="/contacts" class="{{ request()->routeIs('contacts') ? 'active' : '' }}"><i class="fas fa-phone"></i> Контакты</a>
        </div>

        <div class="nav-actions">
            <form action="/catalog" method="GET" class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text"
                       name="search"
                       placeholder="Поиск инструмента..."
                       value="{{ request('search') }}"
                       autocomplete="off">
            </form>

            <div class="cart-wrapper">
                <button class="cart-btn" id="cartBtn">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-badge">0</span>
                </button>

                <div class="cart-dropdown" id="cartDropdown">
                    <div class="cart-header">
                        <span>Корзина</span>
                        <span class="cart-count">0 товаров</span>
                    </div>
                    <div class="cart-items empty-cart">
                        <div class="empty-cart-message">
                            <i class="fas fa-shopping-bag fa-3x"></i>
                            <p>Ваша корзина пуста</p>
                            <a href="/catalog" class="btn-continue-shopping">Продолжить покупки</a>
                        </div>
                    </div>
                </div>
            </div>

            <button class="user-btn">
                <i class="fas fa-user-circle"></i>
            </button>
        </div>

        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>
