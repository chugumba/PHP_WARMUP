$(document).ready(function() {
    $('#send-btn').on('click', function () {
        // Собираем данные из select
        const select1 = $('#first-stop-select').val();
        const select2 = $('#second-stop-select').val();
        const select1Name = $('#first-stop-select').find(':selected').text();
        const select2Name = $('#second-stop-select').find(':selected').text();
        // Отправляем ajax запрос
        $.ajax({
            url: 'api/find-bus.php',
            type: 'POST',
            data: {
                start: select1,
                stop: select2,
                startName: select1Name,
                stopName: select2Name,
            },
            dataType: 'json',
            success: function (response) {
                console.log(response)//(JSON.stringify(response, null, 4))
                $('.result-container').html(JSON.stringify(response, null, 4));
            },
            error: function () {
                alert('Ошибка при обработке запроса.');
            }
        });
    });
    $('#first-stop-select').trigger('change')
});

// Прячем точку отправления в точках назначения
$('#first-stop-select').on('change', function() {
    $(`#second-stop-select option`).show()  
    $(`#second-stop-select option[value="${$('#first-stop-select').val()}"]`).hide();
    if($('#first-stop-select').find(':selected').val() == $('#second-stop-select').find(':selected').val()) {
        let firstVisibleOption = $('#second-stop-select option').filter(function() {
            return $(this).css('display') !== 'none';
        }).first();
        $('#second-stop-select').val(firstVisibleOption.val())   
    }
})