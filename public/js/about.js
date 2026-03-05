// public/js/about.js

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ========== ЯНДЕКС КАРТА ==========
    initYandexMap();
    
    // ========== АНИМАЦИЯ ПРИ СКРОЛЛЕ ==========
    initScrollAnimations();
    
    // ========== ФОРМА ОБРАТНОЙ СВЯЗИ ==========
    initFeedbackForm();
    
    // ========== СЧЕТЧИКИ (ДЛЯ КРАСОТЫ) ==========
    initCounters();
    
    /**
     * Инициализация Яндекс карты
     */
    function initYandexMap() {
        if (typeof ymaps === 'undefined') return;
        
        ymaps.ready(function() {
            const mapContainer = document.getElementById('yandexMap');
            if (!mapContainer) return;
            
            // Координаты центра (Москва, ул. Строителей)
            const centerCoords = [55.76, 37.64];
            
            const map = new ymaps.Map('yandexMap', {
                center: centerCoords,
                zoom: 15,
                controls: ['zoomControl', 'fullscreenControl']
            });
            
            // Кастомная метка в стиле Windows 11
            const placemark = new ymaps.Placemark(centerCoords, {
                hintContent: 'Инструменты.Про',
                balloonContent: `
                    <div style="padding: 10px; font-family: 'Segoe UI', sans-serif;">
                        <strong style="color: #0067c0;">Инструменты.Про</strong><br>
                        ул. Строителей, д. 15<br>
                        <span style="color: #666;">Ежедневно 9:00-21:00</span>
                    </div>
                `
            }, {
                iconLayout: 'default#image',
                iconImageHref: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSIyMCIgY3k9IjIwIiByPSIxOCIgZmlsbD0id2hpdGUiIHN0cm9rZT0iIzAwNjdjMCIgc3Ryb2tlLXdpZHRoPSI0Ii8+PHBhdGggZD0iTTEwIDE2TDIwIDI2TDMwIDE2IiBzdHJva2U9IiMwMDY3YzAiIHN0cm9rZS13aWR0aD0iNCIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBmaWxsPSJub25lIi8+PC9zdmc+',
                iconImageSize: [40, 40],
                iconImageOffset: [-20, -40]
            });
            
            map.geoObjects.add(placemark);
            
            // Делаем карту красивой - убираем лишние элементы
            map.controls.remove('geolocationControl');
            map.controls.remove('searchControl');
            map.controls.remove('trafficControl');
            map.controls.remove('typeSelector');
            map.controls.remove('rulerControl');
            
            // Добавляем плавный скролл колесиком
            map.behaviors.enable('scrollZoom');
        });
    }
    
    /**
     * Анимация элементов при скролле
     */
    function initScrollAnimations() {
        const animatedElements = document.querySelectorAll('.feature-about-card, .team-card, .history-content, .history-image, .contacts-info, .map-container');
        
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
    }
    
    /**
     * Обработка формы обратной связи (только анимация)
     */
    function initFeedbackForm() {
        const form = document.getElementById('feedbackForm');
        if (!form) return;
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('.btn-submit');
            const originalText = submitBtn.innerHTML;
            
            // Анимация отправки
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Отправка...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                // Показываем успех
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Отправлено!';
                submitBtn.style.background = '#107c10';
                
                // Очищаем форму
                form.reset();
                
                // Возвращаем кнопку в исходное состояние
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.style.background = '';
                    submitBtn.disabled = false;
                }, 2000);
                
                // Показываем уведомление
                showNotification('Спасибо! Ваше сообщение отправлено', 'success');
                
            }, 1500);
        });
    }
    
    /**
     * Плавный счетчик для статистики (для красоты)
     */
    function initCounters() {
        const statValues = document.querySelectorAll('.history-stat-value');
        
        statValues.forEach(stat => {
            const targetValue = stat.textContent;
            if (!isNaN(targetValue)) {
                animateCounter(stat, 0, parseInt(targetValue), 2000);
            }
        });
    }
    
    function animateCounter(element, start, end, duration) {
        const startTime = performance.now();
        
        function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const currentValue = Math.floor(progress * (end - start) + start);
            element.textContent = currentValue + (element.textContent.includes('+') ? '+' : '');
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = end + (element.textContent.includes('+') ? '+' : '');
            }
        }
        
        requestAnimationFrame(updateCounter);
    }
    
    /**
     * Уведомления
     */
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification glass notification-${type}`;
        notification.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
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
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
    
    // Добавляем стили для анимаций
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
        
        .ymaps-2-1-79-map {
            border-radius: 24px;
        }
    `;
    
    document.head.appendChild(style);
});