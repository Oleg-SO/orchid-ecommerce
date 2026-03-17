function addCategoryCheckboxToHeader() {
    console.log('Ищем таблицу категорий...');
    
    const firstHeader = document.querySelector('table thead tr th:first-child');
    const checkboxes = document.querySelectorAll('.category-checkbox');

    if (!firstHeader || checkboxes.length === 0) {
        console.log('Нет таблицы или чекбоксов, повтор через 1с');
        setTimeout(addCategoryCheckboxToHeader, 1000);
        return;
    }

    if (document.getElementById('select-all-categories-checkbox')) {
        console.log('Чекбокс уже есть');
        return;
    }

    console.log('Добавляем чекбокс в шапку таблицы категорий');

    firstHeader.innerHTML = '';
    firstHeader.style.textAlign = 'center';
    firstHeader.style.verticalAlign = 'middle';

    const selectAll = document.createElement('input');
    selectAll.type = 'checkbox';
    selectAll.id = 'select-all-categories-checkbox';
    selectAll.style.width = '18px';
    selectAll.style.height = '18px';
    selectAll.style.cursor = 'pointer';
    selectAll.style.margin = '0 auto';
    selectAll.style.display = 'block';
    firstHeader.appendChild(selectAll);

    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.category-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = Array.from(document.querySelectorAll('.category-checkbox')).every(c => c.checked);
            selectAll.checked = allChecked;
        });
    });
}

// Проверка каждую секунду
setInterval(() => {
    if (!document.getElementById('select-all-categories-checkbox')) {
        addCategoryCheckboxToHeader();
    }
}, 1000);

// Первый запуск
setTimeout(addCategoryCheckboxToHeader, 500);