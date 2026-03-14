// public/js/product.js

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ========== ГАЛЕРЕЯ ==========
    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.thumbnail');

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            const imageUrl = this.dataset.image;

            // Меняем главное фото
            if (mainImage) mainImage.src = imageUrl;

            // Обновляем активный класс
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // ========== КОЛИЧЕСТВО ТОВАРА ==========
    const quantityInput = document.querySelector('.quantity-input');
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');

    if (quantityInput && minusBtn && plusBtn) {
        const maxQuantity = parseInt(quantityInput.max);

        minusBtn.addEventListener('click', () => {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
            }
        });

        plusBtn.addEventListener('click', () => {
            let value = parseInt(quantityInput.value);
            if (value < maxQuantity) {
                quantityInput.value = value + 1;
            }
        });

        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) this.value = 1;
            if (value > maxQuantity) this.value = maxQuantity;
        });
    }

    // ========== ДОБАВЛЕНИЕ В КОРЗИНУ ==========
    const addToCartBtn = document.querySelector('.btn-add-to-cart-large');

    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const quantity = document.querySelector('.quantity-input')?.value || 1;

            // Анимация кнопки
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i> Добавлено';
            this.style.background = '#107c10';

            // Обновляем счетчик корзины
            updateCartCount(parseInt(quantity));

            // Здесь будет AJAX запрос
            console.log('Добавлен товар ID:', productId, 'Количество:', quantity);

            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.background = '';
            }, 1500);
        });
    }

    // ========== ИЗБРАННОЕ ==========
    const wishlistBtn = document.querySelector('.btn-wishlist-large');

    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            this.classList.toggle('active');

            const icon = this.querySelector('i');
            if (this.classList.contains('active')) {
                icon.className = 'fas fa-heart';
                showNotification('Добавлено в избранное');
            } else {
                icon.className = 'far fa-heart';
                showNotification('Удалено из избранного');
            }
        });
    }

    // ========== СЛАЙДЕР "С ЭТИМ ТОВАРОМ ПОКУПАЮТ" ==========
    const slider = document.getElementById('alsoBoughtSlider');
    const prevBtn = document.getElementById('alsoBoughtPrev');
    const nextBtn = document.getElementById('alsoBoughtNext');

    if (slider && prevBtn && nextBtn) {
        let currentPosition = 0;
        const cardWidth = 280; // ширина карточки + gap
        const maxPosition = -(slider.children.length * cardWidth - slider.parentElement.offsetWidth);

        nextBtn.addEventListener('click', () => {
            currentPosition = Math.max(currentPosition - cardWidth, maxPosition);
            slider.style.transform = `translateX(${currentPosition}px)`;
        });

        prevBtn.addEventListener('click', () => {
            currentPosition = Math.min(currentPosition + cardWidth, 0);
            slider.style.transform = `translateX(${currentPosition}px)`;
        });
    }

    // ========== ФОРМА ОТЗЫВА ==========
    const showReviewBtn = document.getElementById('showReviewForm');

    if (showReviewBtn) {
        showReviewBtn.addEventListener('click', function() {
            // Здесь можно открыть модальное окно с формой отзыва
            alert('Форма отзыва появится здесь');
        });
    }

    // ========== МАЛЕНЬКИЕ КНОПКИ В КОРЗИНУ ==========
    document.querySelectorAll('.btn-add-to-cart-small').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = this.dataset.productId;

            // Анимация
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i>';
            this.style.background = '#107c10';

            // Обновляем счетчик
            updateCartCount(1);

            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.background = '';
            }, 1000);
        });
    });

    // ========== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ==========
    function updateCartCount(increment = 1) {
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            const current = parseInt(cartBadge.textContent) || 0;
            cartBadge.textContent = current + increment;

            // Анимация корзины
            const cartBtn = document.querySelector('.cart-btn');
            cartBtn.classList.add('cart-bounce');
            setTimeout(() => cartBtn.classList.remove('cart-bounce'), 500);
        }
    }

    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'notification glass';
        notification.innerHTML = `
            <i class="fas fa-heart" style="color: #c30052;"></i>
            <span>${message}</span>
        `;

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

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }
});
