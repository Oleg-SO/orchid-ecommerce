// Просто функция, которая вешает чекбокс в шапку
function addCheckboxToHeader() {
    console.log('Ищем таблицу...');
    
    const firstHeader = document.querySelector('table thead tr th:first-child');
    const checkboxes = document.querySelectorAll('.product-checkbox');

    if (!firstHeader || checkboxes.length === 0) {
        console.log('Нет таблицы или чекбоксов, повтор через 500ms');
        setTimeout(addCheckboxToHeader, 500);
        return;
    }

    // Если чекбокс уже есть — не надо дублировать
    if (document.getElementById('select-all-checkbox')) {
        console.log('Чекбокс уже есть');
        return;
    }

    console.log('Добавляем чекбокс в шапку');

    firstHeader.innerHTML = '';
    firstHeader.style.textAlign = 'center';
    firstHeader.style.verticalAlign = 'middle';

    const selectAll = document.createElement('input');
    selectAll.type = 'checkbox';
    selectAll.id = 'select-all-checkbox';
    selectAll.style.width = '18px';
    selectAll.style.height = '18px';
    selectAll.style.cursor = 'pointer';
    selectAll.style.margin = '0 auto';
    selectAll.style.display = 'block';
    firstHeader.appendChild(selectAll);

    // Логика
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    document.querySelectorAll('.product-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = Array.from(document.querySelectorAll('.product-checkbox')).every(c => c.checked);
            selectAll.checked = allChecked;
        });
    });
}

// Запускаем проверку каждые секунду, пока не появится
function keepWatching() {
    if (!document.getElementById('select-all-checkbox')) {
        addCheckboxToHeader();
    }
    setTimeout(keepWatching, 1000);
}

// Запускаем
setTimeout(addCheckboxToHeader, 500);
keepWatching();