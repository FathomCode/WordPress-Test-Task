# Тестовое задание WordPress-разработчика

1. Цель задания
Проверить навыки кандидата в следующих областях:
Создание дочерних тем WordPress


Custom Post Types (CPT) и таксономий


Advanced Custom Fields (ACF)


Адаптивная верстка на Grid или Flex


CSS-переменные и структура CSS


WP_Query


AJAX-сортировка


Подключение скриптов и стилей через wp_enqueue_script и wp_enqueue_style


Использование шаблонной иерархии WordPress (page-*, single-*)


Подключение header и footer родительской темы через get_header() / get_footer() или get_template_part()



2. Создание окружения
2.1 Дочерняя тема (Child Theme)
Использовать любую стандартную тему WordPress (например, Twenty Twenty-Four)


Создать дочернюю тему, включив:


style.css с корректным Template:


functions.php с подключением стилей родительской темы


Все изменения и файлы задания должны находиться только в дочерней теме



3. Структура данных
3.1 Custom Post Type “Projects”
CPT можно создать через плагин CPT UI или вручную через functions.php


Документация CPT UI: https://wordpress.org/plugins/custom-post-type-ui/


Документация register_post_type: https://developer.wordpress.org/reference/functions/register_post_type/


Требования:
Название: Проекты


Слаг: projects


Включить: архив, ЧПУ, миниатюры


3.2 Таксономия “Категории проектов”
Документация: https://developer.wordpress.org/reference/functions/register_taxonomy/


Термины: разработка, дизайн, верстка, маркетинг


Привязать к CPT “Projects”


Можно создать через UI или вручную


3.3 ACF-поля проекта
Документация: https://www.advancedcustomfields.com/resources/


Поля:


Стоимость (Number)


Галерея проекта (Gallery)


Дополнительное описание (Textarea или WYSIWYG)


Время разработки (Text или Number)



4. Шаблоны и вывод
4.1 Шаблон page-projects.php
Использовать WP_Query для вывода проектов


Верстка на CSS Grid или Flexbox


Адаптивная сетка:


Устройство
Breakpoint
Количество карточек
Desktop
≥1400px
3
Tablet
≤991px
2
Mobile
≤480px
1


Карточка проекта содержит: миниатюру (или первое изображение галереи), название, категорию, стоимость


Подключение header и footer родительской темы через get_header() и get_footer(), либо использование get_template_part()


Документация шаблонной иерархии для страниц: https://developer.wordpress.org/themes/basics/template-hierarchy/#pages


4.2 Шаблон single-project.php
Вывести: название проекта, категории, основное описание (content), дополнительное описание (ACF), стоимость, время разработки, галерею


Подключение header и footer родительской темы через get_header() и get_footer() или get_template_part()


Документация шаблонной иерархии для single постов: https://developer.wordpress.org/themes/basics/template-hierarchy/



5. CSS: структура и требования
Использовать минимум 3 CSS-переменные (например, цвет, отступ, радиус)


Разделить стили на два файла:


style.css — базовые стили


responsive.css или media.css — медиазапросы


Подключение через wp_enqueue_style()


Использовать 3 медиазапроса:


Desktop: min-width 1400px


Tablet: max-width 991px


Mobile: max-width 480px



6. AJAX-сортировка проектов
6.1 Элементы управления
Выпадающий список (select) с вариантами:


По умолчанию


По дате (сначала новые)


По дате (сначала старые)


Стоимость ↑


Стоимость ↓


6.2 Требования
Обновление списка проектов без перезагрузки страницы


JS-файл подключается через wp_enqueue_script()


Использовать WordPress AJAX: wp_ajax_filter_projects и wp_ajax_nopriv_filter_projects


6.3 Логика сортировки
По дате: orderby=date, order=ASC/DESC


По стоимости: сортировка по метаполю ACF cost, orderby=meta_value_num, order=ASC/DESC


Функция должна: получить значение из $_POST['sort'], сформировать WP_Query, вывести HTML карточек, вернуть JSON, завершить wp_die()



7. Формат сдачи
Папка дочерней темы с шаблонами page-projects.php, single-project.php


functions.php


style.css и responsive.css


JS-файл для AJAX с подключением


Экспорт ACF JSON (если используется)


Краткое описание (1–2 абзаца): что реализовано, как работает сортировка, структура файлов
