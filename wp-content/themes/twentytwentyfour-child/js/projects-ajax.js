jQuery(document).ready(function($) {
    // Функция для выполнения AJAX-запроса и обновления списка проектов
    function sortProjects(sortBy) {
        var data = {
            action: 'filter_projects',
            sort: sortBy,
            nonce: ajax_object.nonce
        };

        // Показываем индикатор загрузки
        $('#projects-container').html('<div class="loading-indicator" style="text-align: center; padding: 50px;">Загрузка...</div>');

        $.post(ajax_object.ajax_url, data, function(response) {
            if (response.success) {
                $('#projects-container').html(response.html);
            } else {
                $('#projects-container').html('<p style="text-align: center; color: red;">Ошибка при загрузке проектов.</p>');
                console.error('AJAX Error:', response.data);
            }
        }).fail(function() {
            $('#projects-container').html('<p style="text-align: center; color: red;">Ошибка соединения с сервером.</p>');
        });
    }

    $('#project-sort').on('change', function() {
        var selectedSort = $(this).val();
        sortProjects(selectedSort);
    });
});