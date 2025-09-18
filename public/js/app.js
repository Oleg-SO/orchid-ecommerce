// EcoFire - Main JavaScript File

document.addEventListener('DOMContentLoaded', function() {
    console.log('EcoFire магазин загружен!');
    
    // Mobile menu functionality
    initMobileMenu();
    
    // Smooth scrolling
    initSmoothScroll();
    
    // Cart functionality
    initCart();
    
    // Animations
    initAnimations();
});

// Mobile menu toggle
function initMobileMenu() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');
        });
    }
}

// Smooth scrolling for anchor links
function initSmoothScroll() {
    const scroll = new SmoothScroll('a[href*="#"]', {
        speed: 800,
        speedAsDuration: true,
        offset: 80
    });
}

// Cart functionality
function initCart() {
    const cartBtn = document.querySelector('.cart-btn');
    const cartBadge = document.querySelector('.cart-badge');
    
    if (cartBtn) {
        cartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Здесь будет логика корзины
            console.log('Корзина открыта');
        });
    }
}

// Animations and effects
function initAnimations() {
    // Add fade-in animation to elements
    const animateElements = document.querySelectorAll('.category-card, .feature-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });
    
    animateElements.forEach(element => {
        observer.observe(element);
    });
}

// Cart functions
function addToCart(productId, quantity = 1) {
    console.log(`Добавлен товар ${productId}, количество: ${quantity}`);
    updateCartBadge(quantity);
}

function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        const currentCount = parseInt(badge.textContent) || 0;
        badge.textContent = currentCount + count;
    }
}

// Search functionality
function initSearch() {
    const searchBtn = document.querySelector('.search-btn');
    
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Поиск товаров...';
            searchInput.classList.add('search-input');
            
            // Логика поиска
            console.log('Поиск активирован');
        });
    }
}

// Form validation
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export functions for global use
window.EcoFire = {
    addToCart,
    updateCartBadge,
    validateForm
};