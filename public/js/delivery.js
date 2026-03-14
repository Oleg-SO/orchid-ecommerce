// public/js/delivery.js

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // Аккордеон для FAQ
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');

        question.addEventListener('click', () => {
            const isActive = item.classList.contains('active');

            // Закрываем все
            faqItems.forEach(i => i.classList.remove('active'));

            // Открываем текущий, если он не был активен
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });

    // Анимация при скролле
    const animatedElements = document.querySelectorAll('.method-card, .step, .region-card, .pickup-card, .term-item');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});
