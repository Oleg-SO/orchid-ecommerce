<footer class="win-footer">
    <div class="win-container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-logo">
                    <i class="fas fa-tools"></i> Инструменты.Про
                </div>
                <p>Лучший инструмент для профессионалов с 2009 года.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-telegram"></i></a>
                    <a href="#"><i class="fab fa-vk"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Каталог</h4>
                <ul>
                    @foreach($popularCategories ?? [] as $category)
                        <li><a href="/catalog/category/{{ $category->slug }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="footer-col">
                <h4>Покупателям</h4>
                <ul>
                    <li><a href="#">Доставка и оплата</a></li>
                    <li><a href="#">Гарантия</a></li>
                    <li><a href="#">Возврат</a></li>
                    <li><a href="#">Бонусная программа</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Контакты</h4>
                <ul>
                    <li><i class="fas fa-phone"></i> 8-800-555-35-35</li>
                    <li><i class="fas fa-envelope"></i> info@instrument.pro</li>
                    <li><i class="fas fa-map-marker-alt"></i> ул. Строителей, 15</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2025 Инструменты.Про — интернет-магазин строй инструментов</p>
            <div class="payment-methods">
                <i class="fab fa-cc-visa"></i>
                <i class="fab fa-cc-mastercard"></i>
                <i class="fab fa-cc-mir"></i>
            </div>
        </div>
    </div>
</footer>
