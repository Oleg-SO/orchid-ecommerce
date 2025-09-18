<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoFire - Интернет-магазин</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('js/app.js') }}" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://via.placeholder.com/1920x600');
            background-size: cover;
            background-position: center;
            height: 500px;
            display: flex;
            align-items: center;
            color: white;
        }
        .category-card {
            transition: transform 0.3s ease;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        footer {
            background-color: #f8f9fa;
            padding: 40px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <!-- Логотип -->
                <a class="navbar-brand" href="/">
                    <img src="https://ecofire.kz/images/landing/home3/main-logo.png" alt="EcoFire" height="40">
                </a>

                <!-- Меню -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="/">Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/catalog">Каталог</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/about">О нас</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/contacts">Контакты</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/blog">Блог</a>
                        </li>
                    </ul>

                    <!-- Иконки справа -->
                    <div class="d-flex">
                        <a href="/search" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-search"></i>
                        </a>
                        <a href="/cart" class="btn btn-outline-primary me-2">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge bg-danger">3</span>
                        </a>
                        <a href="/login" class="btn btn-outline-secondary">
                            <i class="fas fa-user"></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <!-- О компании -->
                <div class="col-md-4 mb-4">
                    <h5>EcoFire</h5>
                    <p>Интернет-магазин экологичных товаров для дома и жизни.</p>
                    <div class="social-links">
                        <a href="#" class="text-dark me-3"><i class="fab fa-vk fa-2x"></i></a>
                        <a href="#" class="text-dark me-3"><i class="fab fa-telegram fa-2x"></i></a>
                        <a href="#" class="text-dark me-3"><i class="fab fa-instagram fa-2x"></i></a>
                    </div>
                </div>

                <!-- Меню -->
                <div class="col-md-2 mb-4">
                    <h5>Меню</h5>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-decoration-none text-dark">Главная</a></li>
                        <li><a href="/catalog" class="text-decoration-none text-dark">Каталог</a></li>
                        <li><a href="/about" class="text-decoration-none text-dark">О нас</a></li>
                        <li><a href="/contacts" class="text-decoration-none text-dark">Контакты</a></li>
                    </ul>
                </div>

                <!-- Категории -->
                <div class="col-md-2 mb-4">
                    <h5>Категории</h5>
                    <ul class="list-unstyled">
                        <li><a href="/category/eco" class="text-decoration-none text-dark">Эко-товары</a></li>
                        <li><a href="/category/home" class="text-decoration-none text-dark">Для дома</a></li>
                        <li><a href="/category/kitchen" class="text-decoration-none text-dark">Кухня</a></li>
                        <li><a href="/category/garden" class="text-decoration-none text-dark">Сад</a></li>
                    </ul>
                </div>

                <!-- Контакты -->
                <div class="col-md-4 mb-4">
                    <h5>Контакты</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> г. Москва, ул. Примерная, 123</p>
                    <p><i class="fas fa-phone me-2"></i> +7 (999) 123-45-67</p>
                    <p><i class="fas fa-envelope me-2"></i> info@ecofire.ru</p>
                    <p><i class="fas fa-clock me-2"></i> Пн-Пт: 9:00-18:00</p>
                </div>
            </div>

            <hr>
            <div class="text-center">
                <p>&copy; 2024 EcoFire. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>