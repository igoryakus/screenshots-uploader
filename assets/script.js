document.addEventListener('DOMContentLoaded', function () {
    const image = document.getElementById('screenshot');

    // Инициализируем Viewer.js встроенным в нашу страницу
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
});
