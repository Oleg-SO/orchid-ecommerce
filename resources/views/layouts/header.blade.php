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
                <a href="#" class="active"><i class="fas fa-home"></i> Главная</a>
                <a href="catalog"><i class="fas fa-th-large"></i> Каталог</a>
                <a href="#"><i class="fas fa-tag"></i> Акции</a>
                <a href="#"><i class="fas fa-info-circle"></i> О нас</a>
                <a href="#"><i class="fas fa-phone"></i> Контакты</a>
            </div>

            <div class="nav-actions">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Поиск инструмента...">
                </div>

                <div class="cart-wrapper">
                    <button class="cart-btn" id="cartBtn">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="cart-badge">3</span>
                    </button>

                    {{-- Мини-корзина (появится при клике) --}}
                    <div class="cart-dropdown" id="cartDropdown">
                        <div class="cart-header">
                            <span>Корзина</span>
                            <span class="cart-count">3 товара</span>
                        </div>
                        <div class="cart-items">
                            <div class="cart-item">
                                <img src="https://via.placeholder.com/50" alt="item">
                                <div class="item-info">
                                    <div>Перфоратор BOSCH</div>
                                    <div class="item-price">12 490 ₽</div>
                                </div>
                                <span class="item-qty">x1</span>
                            </div>
                            <div class="cart-item">
                                <img src="https://via.placeholder.com/50" alt="item">
                                <div class="item-info">
                                    <div>Шуруповерт Makita</div>
                                    <div class="item-price">8 990 ₽</div>
                                </div>
                                <span class="item-qty">x2</span>
                            </div>
                        </div>
                        <div class="cart-footer">
                            <div class="cart-total">Итого: <span>30 470 ₽</span></div>
                            <button class="btn-checkout">Оформить</button>
                        </div>
                    </div>
                </div>

                <button class="user-btn">
                    <i class="fas fa-user-circle"></i>
                </button>
            </div>

            {{-- Мобильное меню (бургер) --}}
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>




