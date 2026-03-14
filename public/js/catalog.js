// public/js/catalog.js

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ========== ИНИЦИАЛИЗАЦИЯ ==========
    initPriceSlider();
    initAccordion();
    initViewToggle();
    initAddToCartAnimation();
    initWishlistAnimation();
    initMobileFilters();

    // ========== СЛАЙДЕР ЦЕН (ТОЛЬКО ДИЗАЙН) ==========
    function initPriceSlider() {
        const priceSlider = document.getElementById('priceRangeSlider');
        if (!priceSlider) return;

        const minPriceInput = document.getElementById('minPrice');
        const maxPriceInput = document.getElementById('maxPrice');

        // Инициализация слайдера без логики фильтрации
        noUiSlider.create(priceSlider, {
            start: [0, 100000],
            connect: true,
            range: {
                'min': 0,
                'max': 200000
            },
            format: wNumb({
                decimals: 0,
                thousand: ' '
            })
        });

        // Только обновление значений в полях (визуально)
        priceSlider.noUiSlider.on('update', function(values, handle) {
            if (handle === 0) {
                minPriceInput.value = Math.round(values[0]);
            } else {
                maxPriceInput.value = Math.round(values[1]);
            }
        });

        // При изменении полей - двигаем слайдер (визуально)
        minPriceInput.addEventListener('change', function() {
            priceSlider.noUiSlider.set([this.value, null]);
        });

        maxPriceInput.addEventListener('change', function() {
            priceSlider.noUiSlider.set([null, this.value]);
        });
    }

    // ========== АККОРДЕОН ДЛЯ ФИЛЬТРОВ (ТОЛЬКО UI) ==========
    function initAccordion() {
        document.querySelectorAll('.filter-section-title').forEach(title => {
            title.addEventListener('click', function() {
                const section = this.closest('.filter-section');
                section.classList.toggle('collapsed');
            });
        });
    }

    // ========== ПЕРЕКЛЮЧЕНИЕ ВИДА (ТОЛЬКО ВИЗУАЛ) ==========
    function initViewToggle() {
        const viewBtns = document.querySelectorAll('.view-btn');
        const productsGrid = document.getElementById('productsGrid');
        const productsList = document.getElementById('productsList');

        if (!viewBtns.length || !productsGrid || !productsList) return;

        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Меняем активную кнопку
                viewBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Переключаем отображение
                const view = this.dataset.view;

                if (view === 'grid') {
                    productsGrid.style.display = 'grid';
                    productsList.style.display = 'none';
                } else {
                    productsGrid.style.display = 'none';
                    productsList.style.display = 'block';
                }
            });
        });
    }

    // ========== АНИМАЦИЯ ДОБАВЛЕНИЯ В КОРЗИНУ ==========
    function initAddToCartAnimation() {
        document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                // Только анимация кнопки
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> Добавлено';
                this.style.background = '#107c10';

                // Анимация корзины (подпрыгивание)
                const cartBtn = document.querySelector('.cart-btn');
                if (cartBtn) {
                    cartBtn.classList.add('cart-bounce');
                    setTimeout(() => {
                        cartBtn.classList.remove('cart-bounce');
                    }, 500);
                }

                // Возвращаем кнопку в исходное состояние
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.background = '';
                }, 1500);
            });
        });
    }

    // ========== АНИМАЦИЯ ИЗБРАННОГО ==========
    function initWishlistAnimation() {
        document.querySelectorAll('.btn-wishlist').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                // Только анимация сердечка
                this.classList.toggle('active');

                const icon = this.querySelector('i');
                if (this.classList.contains('active')) {
                    icon.className = 'fas fa-heart';
                    // Эффект "вспышка"
                    this.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 200);
                } else {
                    icon.className = 'far fa-heart';
                }
            });
        });
    }

    // ========== МОБИЛЬНЫЕ ФИЛЬТРЫ ==========
    function initMobileFilters() {
        // Для мобильной версии - кнопка показа/скрытия фильтров
        const filterBtn = document.createElement('button');
        filterBtn.className = 'mobile-filter-btn glass';
        filterBtn.innerHTML = '<i class="fas fa-filter"></i> Фильтры';

        const sidebar = document.querySelector('.filters-sidebar');
        const catalogToolbar = document.querySelector('.catalog-toolbar');

        if (window.innerWidth <= 992 && catalogToolbar && sidebar) {
            catalogToolbar.prepend(filterBtn);

            filterBtn.addEventListener('click', function() {
                sidebar.classList.toggle('mobile-show');
                document.body.classList.toggle('filter-open');
            });

            // Кнопка закрытия на мобильных
            const closeBtn = document.createElement('button');
            closeBtn.className = 'mobile-filter-close';
            closeBtn.innerHTML = '<i class="fas fa-times"></i>';
            sidebar.prepend(closeBtn);

            closeBtn.addEventListener('click', function() {
                sidebar.classList.remove('mobile-show');
                document.body.classList.remove('filter-open');
            });
        }
    }

    // ========== ДОБАВЛЯЕМ НЕОБХОДИМЫЕ СТИЛИ ДЛЯ АНИМАЦИЙ ==========
    const style = document.createElement('style');
    style.textContent = `
        /* Анимация корзины */
        @keyframes cartBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        .cart-bounce {
            animation: cartBounce 0.3s ease;
        }

        /* Мобильные фильтры */
        @media (max-width: 992px) {
            .filters-sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 90%;
                max-width: 350px;
                height: 100vh;
                z-index: 10000;
                border-radius: 0;
                overflow-y: auto;
                transition: left 0.3s ease;
            }

            .filters-sidebar.mobile-show {
                left: 0;
            }

            .mobile-filter-btn {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                background: var(--win-card-bg);
                backdrop-filter: blur(var(--win-blur));
                border: 1px solid var(--win-border);
                border-radius: 40px;
                padding: 0.6rem 1.2rem;
                margin-right: 1rem;
                cursor: pointer;
            }

            .mobile-filter-close {
                position: sticky;
                top: 10px;
                left: 100%;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: var(--win-card-bg);
                backdrop-filter: blur(var(--win-blur));
                border: 1px solid var(--win-border);
                margin-left: auto;
                margin-bottom: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 10001;
            }

            body.filter-open::after {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.3);
                backdrop-filter: blur(4px);
                z-index: 9999;
            }
        }

        @media (min-width: 993px) {
            .mobile-filter-btn,
            .mobile-filter-close {
                display: none;
            }
        }

        /* Анимация сердечка */
        .btn-wishlist {
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .btn-wishlist.active {
            color: #c30052;
        }

        /* Анимация кнопки добавления */
        .btn-add-to-cart {
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .btn-add-to-cart:active {
            transform: scale(0.95);
        }

        /* Анимация аккордеона */
        .filter-section-content {
            transition: opacity 0.3s ease, max-height 0.3s ease;
            max-height: 500px;
            opacity: 1;
        }

        .filter-section.collapsed .filter-section-content {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }
    `;

    document.head.appendChild(style);
});
