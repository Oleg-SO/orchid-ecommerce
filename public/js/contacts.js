// public/js/contacts.js

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ========== ЯНДЕКС КАРТА ==========
    initYandexMap();

    // ========== ФОРМА ==========
    initContactForm();

    // ========== АНИМАЦИИ ==========
    initScrollAnimations();

    function initYandexMap() {
        if (typeof ymaps === 'undefined') return;

        ymaps.ready(function() {
            const mapContainer = document.getElementById('yandexMapFull');
            if (!mapContainer) return;

            const shopCoords = [55.76, 37.64];

            const map = new ymaps.Map('yandexMapFull', {
                center: shopCoords,
                zoom: 16,
                controls: ['zoomControl', 'fullscreenControl']
            });

            const placemark = new ymaps.Placemark(shopCoords, {
                hintContent: 'Инструменты.Про',
                balloonContent: 'Инструменты.Про<br>ул. Строителей, 15'
            }, {
                preset: 'islands#blueCircleDotIcon',
                iconColor: '#0067c0'
            });

            map.geoObjects.add(placemark);
        });
    }

    function initContactForm() {
        const form = document.getElementById('contactsForm');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = form.querySelector('.btn-submit-large');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Отправка...';
            btn.disabled = true;

            // Здесь будет AJAX запрос
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check"></i> Отправлено!';
                form.reset();

                setTimeout(() => {
                    btn.innerHTML = '<i class="fas fa-paper-plane"></i> Отправить сообщение';
                    btn.disabled = false;
                }, 2000);
            }, 1000);
        });
    }

    function initScrollAnimations() {
        const elements = document.querySelectorAll('.contacts-card, .feedback-card, .reach-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });

        elements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    }
});
