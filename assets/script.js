document.addEventListener('DOMContentLoaded', function () {
    const image = document.getElementById('screenshot');
    const loader = document.getElementById('waiting-loader');

    // Если картинка есть, инициализируем Viewer.js
    if (image) {
        const viewer = new Viewer(image, {
            inline: true,          // Отображать сразу на странице, а не в модальном окне
            navbar: false,         // Отключаем нижнюю панель со списком картинок (она не нужна для одного фото)
            title: false,          // Отключаем встроенное название файла
            fullscreen: true,
            toolbar: {             // Оставляем только нужные кнопки управления
                zoomIn: 4,
                zoomOut: 4,
                oneToOne: 4,
                reset: 4,
                rotateLeft: 4,
                rotateRight: 4,
                play: {
                    show: 4,
                    size: 'large',
                },
            },
            viewed() {
                // Устанавливаем масштаб 1:1 (реальный размер) при загрузке
                //viewer.zoomTo(1);
            }
        });
    }

    // Если картинки нет и мы видим лоадер, начинаем проверку
    if (loader) {
        const fileUrl = loader.getAttribute('data-url');
        
        const checkFile = setInterval(() => {
            // Добавляем случайный параметр для обхода кэша браузера/прокси
            const cacheBuster = `?t=${new Date().getTime()}`;
            console.log(fileUrl + cacheBuster);
            // Используем метод GET, чтобы проверить существование файла (метод HEAD может блокироваться)
            fetch(fileUrl + cacheBuster, { 
                method: 'GET',
                cache: 'no-store'
            })
                .then(response => {
                    if (response.ok) {
                        clearInterval(checkFile);
                        window.location.reload(); 
                    }
                })
                .catch(err => console.log('Ожидание файла...'));
        }, 1500); // Проверка каждые 1.5 секунды
    }
});
