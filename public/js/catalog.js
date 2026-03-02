// public/js/catalog.js

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ========== ИНИЦИАЛИЗАЦИЯ ==========
    initPriceSlider();
    initFilters();
    initSort();
    initViewToggle();
    initSearch();
    initAccordion();
    initAddToCart();
    initWishlist();

    // ========== СЛАЙДЕР ЦЕН ==========
    function initPriceSlider() {
        const priceSlider = document.getElementById('priceRangeSlider');
        if (!priceSlider) return;

        const minPriceInput = document.getElementById('minPrice');
        const maxPriceInput = document.getElementById('maxPrice');

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

        priceSlider.noUiSlider.on('update', function(values, handle) {
            if (handle === 0) {
                minPriceInput.value = Math.round(values[0]);
            } else {
                maxPriceInput.value = Math.round(values[1]);
            }
        });

        minPriceInput.addEventListener('change', function() {
            priceSlider.noUiSlider.set([this.value, null]);
        });

        maxPriceInput.addEventListener('change', function() {
            priceSlider.noUiSlider.set([null, this.value]);
        });
    }

    // ========== ФИЛЬТРЫ ==========
    function initFilters() {
        // Применение фильтров
        const applyBtn = document.getElementById('applyFilters');
        if (applyBtn) {
            applyBtn.addEventListener('click', function() {
                applyFilters();
            });
        }

        // Сброс фильтров
        const clearBtn = document.getElementById('clearFilters');
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                clearFilters();
            });
        }

        // Чекбоксы категорий
        document.querySelectorAll('.category-checkbox input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateProductsCount();
            });
        });

        // Поиск по категориям
        const searchFilter = document.getElementById('searchFilter');
        if (searchFilter) {
            searchFilter.addEventListener('input', function() {
                filterCategories(this.value);
            });
        }
    }

    function applyFilters() {
        // Собираем все активные фильтры
        const filters = {
            categories: [],
            brands: [],
            inStock: document.querySelector('input[name="in_stock"]:checked') ? true : false,
            minPrice: document.getElementById('minPrice')?.value || 0,
            maxPrice: document.getElementById('maxPrice')?.value || 200000,
            search: document.getElementById('globalSearch')?.value || ''
        };

        document.querySelectorAll('.category-checkbox input:checked').forEach(cb => {
            filters.categories.push(cb.value);
        });

        document.querySelectorAll('.brand-checkbox input:checked').forEach(cb => {
            filters.brands.push(cb.value);
        });

        console.log('Применены фильтры:', filters);

        // Здесь будет AJAX запрос к серверу
        // fetchProducts(filters);

        // Пока просто показываем уведомление
        showNotification('Фильтры применены', 'success');
    }

    function clearFilters() {
        // Сбрасываем все чекбоксы
        document.querySelectorAll('.category-checkbox input, .brand-checkbox input, input[name="in_stock"]').forEach(cb => {
            cb.checked = false;
        });

        // Сбрасываем слайдер цен
        const priceSlider = document.getElementById('priceRangeSlider');
        if (priceSlider && priceSlider.noUiSlider) {
            priceSlider.noUiSlider.set([0, 200000]);
        }

        // Сбрасываем поиск
        const searchInput = document.getElementById('globalSearch');
        if (searchInput) searchInput.value = '';

        const searchFilter = document.getElementById('searchFilter');
        if (searchFilter) searchFilter.value = '';

        // Показываем все категории
        document.querySelectorAll('.category-checkbox').forEach(item => {
            item.style.display = 'flex';
        });

        showNotification('Фильтры сброшены', 'info');
    }

    function filterCategories(searchText) {
        const text = searchText.toLowerCase().trim();
        document.querySelectorAll('.category-checkbox').forEach(item => {
            const categoryName = item.querySelector('.category-name').textContent.toLowerCase();
            if (text === '' || categoryName.includes(text)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // ========== СОРТИРОВКА ==========
    function initSort() {
        const sortSelect = document.getElementById('sortSelect');
        if (!sortSelect) return;

        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            console.log('Сортировка:', sortValue);

            // Здесь будет AJAX запрос
            // fetchProducts({ sort: sortValue });

            // Пока просто меняем классы для демо
            sortProducts(sortValue);
        });
    }

    function sortProducts(sortType) {
        const productsGrid = document.getElementById('productsGrid');
        if (!productsGrid) return;

        const products = Array.from(productsGrid.children);

        switch(sortType) {
            case 'price_asc':
                products.sort((a, b) => {
                    const priceA = getProductPrice(a);
                    const priceB = getProductPrice(b);
                    return priceA - priceB;
                });
                break;
            case 'price_desc':
                products.sort((a, b) => {
                    const priceA = getProductPrice(a);
                    const priceB = getProductPrice(b);
                    return priceB - priceA;
                });
                break;
            case 'name_asc':
                products.sort((a, b) => {
                    const nameA = getProductName(a);
                    const nameB = getProductName(b);
                    return nameA.localeCompare(nameB);
                });
                break;
            case 'name_desc':
                products.sort((a, b) => {
                    const nameA = getProductName(a);
                    const nameB = getProductName(b);
                    return nameB.localeCompare(nameA);
                });
                break;
            default:
                return;
        }

        // Перестраиваем DOM
        products.forEach(product => productsGrid.appendChild(product));
    }

    function getProductPrice(productElement) {
        const priceElement = productElement.querySelector('.current-price');
        if (priceElement) {
            return parseInt(priceElement.textContent.replace(/[^\d]/g, ''));
        }
        return 0;
    }

    function getProductName(productElement) {
        const nameElement = productElement.querySelector('.product-title a');
        return nameElement ? nameElement.textContent : '';
    }

    // ========== ПЕРЕКЛЮЧЕНИЕ ВИДА ==========
    function initViewToggle() {
        const viewBtns = document.querySelectorAll('.view-btn');
        const productsGrid = document.getElementById('productsGrid');
        const productsList = document.getElementById('productsList');

        if (!viewBtns.length || !productsGrid || !productsList) return;

        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                viewBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

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

    // ========== ПОИСК ==========
    function initSearch() {
        const globalSearch = document.getElementById('globalSearch');
        if (!globalSearch) return;

        let searchTimeout;

        globalSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                const query = this.value.trim();
                if (query.length >= 3) {
                    console.log('Поиск:', query);
                    // Здесь будет AJAX запрос
                    // fetchProducts({ search: query });
                }
            }, 500);
        });

        // Поиск по Enter
        globalSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    console.log('Поиск (Enter):', query);
                    // Здесь будет AJAX запрос
                }
            }
        });
    }

    // ========== АККОРДЕОН ДЛЯ ФИЛЬТРОВ ==========
    function initAccordion() {
        document.querySelectorAll('.filter-section-title').forEach(title => {
            title.addEventListener('click', function() {
                const section = this.closest('.filter-section');
                section.classList.toggle('collapsed');
            });
        });
    }

    // ========== ДОБАВЛЕНИЕ В КОРЗИНУ ==========
    function initAddToCart() {
        document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.dataset.productId;

                // Анимация
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> Добавлено';
                this.style.background = '#107c10';

                // Обновляем счетчик в корзине
                updateCartCount(1);

                // Показываем уведомление
                showNotification('Товар добавлен в корзину', 'success');

                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.style.background = '';
                }, 1500);

                // Здесь будет AJAX запрос на добавление в корзину
                console.log('Добавлен товар ID:', productId);
            });
        });
    }

    // ========== ИЗБРАННОЕ ==========
    function initWishlist() {
        document.querySelectorAll('.btn-wishlist').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.toggle('active');

                const icon = this.querySelector('i');
                if (this.classList.contains('active')) {
                    icon.className = 'fas fa-heart';
                    showNotification('Добавлено в избранное', 'info');
                } else {
                    icon.className = 'far fa-heart';
                    showNotification('Удалено из избранного', 'info');
                }
            });
        });
    }

    // ========== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ==========

    // Обновление счетчика корзины
    function updateCartCount(increment = 1) {
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            const current = parseInt(cartBadge.textContent) || 0;
            cartBadge.textContent = current + increment;
        }
    }

    // Показ уведомлений
    function showNotification(message, type = 'info') {
        // Создаем элемент уведомления
        const notification = document.createElement('div');
        notification.className = `notification glass notification-${type}`;
        notification.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
        `;

        // Добавляем стили для уведомления (можно добавить в CSS)
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--win-card-bg);
            backdrop-filter: blur(var(--win-blur));
            border: 1px solid var(--win-border);
            border-radius: 40px;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        `;

        document.body.appendChild(notification);

        // Удаляем через 3 секунды
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Обновление количества товаров (для заголовка)
    function updateProductsCount(count = 0) {
        const productsCount = document.getElementById('productsCount');
        if (productsCount) {
            productsCount.textContent = count;
        }
    }

    // ========== FETCH PRODUCTS (ЗАГОТОВКА ДЛЯ AJAX) ==========
    async function fetchProducts(filters = {}) {
        try {
            // Показываем прелоадер
            showLoader(true);

            // Формируем URL с параметрами
            const params = new URLSearchParams(filters);
            const response = await fetch(`/api/products?${params}`);
            const data = await response.json();

            // Обновляем список товаров
            renderProducts(data.products);
            updateProductsCount(data.total);

            // Скрываем прелоадер
            showLoader(false);

        } catch (error) {
            console.error('Ошибка загрузки товаров:', error);
            showNotification('Ошибка загрузки товаров', 'error');
            showLoader(false);
        }
    }

    function showLoader(show) {
        let loader = document.querySelector('.products-loader');

        if (show) {
            if (!loader) {
                loader = document.createElement('div');
                loader.className = 'products-loader glass';
                loader.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Загрузка...';
                loader.style.cssText = `
                    text-align: center;
                    padding: 3rem;
                    margin: 2rem 0;
                    border-radius: 24px;
                `;
                document.querySelector('.products-grid').appendChild(loader);
            }
        } else {
            if (loader) {
                loader.remove();
            }
        }
    }

    function renderProducts(products) {
        // Здесь будет отрисовка товаров
        console.log('Рендеринг товаров:', products);
    }

    // Добавляем CSS анимации
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .notification-success i {
            color: #107c10;
        }

        .notification-info i {
            color: var(--win-accent);
        }

        .notification-error i {
            color: #d83b01;
        }
    `;
    document.head.appendChild(style);
});
