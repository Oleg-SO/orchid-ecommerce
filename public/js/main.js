// public/js/windows11.js
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // Мобильное меню
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const navLinks = document.getElementById('navLinks');

    if (mobileBtn && navLinks) {
        mobileBtn.addEventListener('click', () => {
            navLinks.classList.toggle('show');
        });
    }

    // Корзина (открытие/закрытие)
    const cartBtn = document.getElementById('cartBtn');
    const cartDropdown = document.getElementById('cartDropdown');

    if (cartBtn && cartDropdown) {
        cartBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            cartDropdown.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!cartBtn.contains(e.target) && !cartDropdown.contains(e.target)) {
                cartDropdown.classList.remove('show');
            }
        });
    }

    // Слайдер товаров
    const sliderTrack = document.querySelector('.slider-track');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    if (sliderTrack && prevBtn && nextBtn) {
        // Заполняем слайдер товарами
        const products = [
            {
                img: 'https://via.placeholder.com/260x180/0067c0/ffffff?text=Перфоратор',
                title: 'Перфоратор BOSCH GBH 2-28',
                price: '12 490 ₽',
                rating: 4.8,
                reviews: 124
            },
            {
                img: 'https://via.placeholder.com/260x180/d83b01/ffffff?text=Шуруповерт',
                title: 'Шуруповерт Makita DF457D',
                price: '8 990 ₽',
                rating: 4.9,
                reviews: 89
            },
            {
                img: 'https://via.placeholder.com/260x180/107c10/ffffff?text=Уровень',
                title: 'Лазерный уровень DeWalt',
                price: '15 900 ₽',
                rating: 4.7,
                reviews: 56
            },
            {
                img: 'https://via.placeholder.com/260x180/c30052/ffffff?text=Болгарка',
                title: 'УШМ Metabo WEV 15-125',
                price: '11 200 ₽',
                rating: 4.8,
                reviews: 73
            },
            {
                img: 'https://via.placeholder.com/260x180/ff8c00/ffffff?text=Пила',
                title: 'Торцовочная пила Hitachi',
                price: '24 800 ₽',
                rating: 4.6,
                reviews: 42
            }
        ];

        sliderTrack.innerHTML = products.map(product => `
            <div class="product-card">
                <img src="${product.img}" alt="${product.title}">
                <div class="product-title">${product.title}</div>
                <div class="product-price">${product.price}</div>
                <div class="product-rating">
                    ${'<i class="fas fa-star"></i>'.repeat(Math.floor(product.rating))}
                    ${product.rating % 1 ? '<i class="fas fa-star-half-alt"></i>' : ''}
                    <span>(${product.reviews})</span>
                </div>
                <button class="btn-add"><i class="fas fa-cart-plus"></i> В корзину</button>
            </div>
        `).join('');

        let currentPosition = 0;
        const cardWidth = 280; // ширина карточки + gap
        const maxPosition = -(products.length * cardWidth - sliderTrack.parentElement.offsetWidth);

        nextBtn.addEventListener('click', () => {
            currentPosition = Math.max(currentPosition - cardWidth, maxPosition);
            sliderTrack.style.transform = `translateX(${currentPosition}px)`;
        });

        prevBtn.addEventListener('click', () => {
            currentPosition = Math.min(currentPosition + cardWidth, 0);
            sliderTrack.style.transform = `translateX(${currentPosition}px)`;
        });

        window.addEventListener('resize', () => {
            const newMax = -(products.length * cardWidth - sliderTrack.parentElement.offsetWidth);
            if (currentPosition < newMax) {
                currentPosition = newMax;
                sliderTrack.style.transform = `translateX(${currentPosition}px)`;
            }
        });
    }

    // Аккордеон для FAQ
    const accordionItems = document.querySelectorAll('.accordion-item');

    accordionItems.forEach(item => {
        const header = item.querySelector('.accordion-header');

        header.addEventListener('click', () => {
            const isActive = item.classList.contains('active');

            // Закрываем все
            accordionItems.forEach(i => i.classList.remove('active'));

            // Открываем текущий, если он не был активен
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });

    // Фильтры (демо)
    const filterBtns = document.querySelectorAll('.filter-btn');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Здесь можно добавить логику фильтрации
            console.log('Фильтр:', btn.textContent);
        });
    });

    // Кнопки "В корзину"
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-add') || e.target.closest('.btn-add')) {
            const btn = e.target.closest('.btn-add');
            const badge = document.querySelector('.cart-badge');

            if (badge) {
                const currentCount = parseInt(badge.textContent);
                badge.textContent = currentCount + 1;

                // Анимация
                btn.innerHTML = '<i class="fas fa-check"></i> Добавлено';
                btn.style.background = '#107c10';
                btn.style.color = 'white';

                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-cart-plus"></i> В корзину';
                    btn.style.background = 'white';
                    btn.style.color = '';
                }, 1500);
            }
        }
    });
});
