<div class="footer">
    <div class="container">
        <p>&copy; <?=date('Y'); ?> Screenshots uploader. Yakus Corporation. Все права незащищены.</p>
    </div>
</div>

<?php 
// Подключаем Viewer.js только если это необходимо (есть переменная $image_url или мы не на главной)
// Или просто подключаем везде для простоты, если скрипт небольшой
?>
<!-- Подключаем скрипт плагина Viewer.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.7/viewer.min.js"></script>

<!-- Инициализация плагина -->
<script src="/screenshots-uploader/assets/script.js"></script>
</body>
</html>
