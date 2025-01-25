<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовое задание (автобусы)</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="./css/routes.css">
    <style>
        #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
        #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
        #sortable li span { position: absolute; margin-left: -1.3em; }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <?php
        // Подключаем файл с контроллером
        require_once './api/DBcontroller.php';
        $db = new dbController();
    ?>
    <header>
        <a href="/" class="btn btn-info btns" role="button">Обратно</a>
    </header>
    <main>
    </main>
 <script>
    
    function fillMain(allRoutes) {
        console.log(allRoutes)

        allRoutes.forEach(element => {
            let stations = '';

            element.data.forEach(station => {
                stations += `
                <li 
                data-route-id="${station.route_id}"
                data-stop-id="${station.stop}"
                class="ui-state-default">
                    <button data-route-id="${station.route_id}" type="button" class="del-btn btns btn btn-danger"><span class="ui-icon ui-icon-trash  "></span></button>
                    Ост. ${station.stop_name} <span class="ui-icon ui-icon-arrowthick-1-e"></span>
                </li>`
            })

            $('main').append(
                `<div class="route" 
                data-bus-id="${element.bus}"
                data-dir="${element.dir}">
                    <h1>${element.bus_name} ${element.dir == 'forward' ? "Вперёд" : "Назад"}</h1>
                    <ul class="sortable">
                        ${stations}
                    </ul>
                    
                    <div>
                        <button type="button" class="upd-btn btns btn btn-primary">Обновить порядок</button>
                        <button type="button" class="add-btn btns btn btn-success">Добавить остановку</button>
                    </div>
                </div>`
            )
        });
        
        $( ".sortable" ).sortable();
    }
    // Загрузка данных
    $(document).ready(function () {
        $.ajax({
            url: 'api/all-routes.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                fillMain(response)
            },
            error: function () {
                alert('Ошибка при обработке запроса.');
            }
        });
    })
    // Удаление записи
    $('main').on('click', '.del-btn', function () {
        const dataAttributes = $(this).parent().data('routeId'); 

        $.ajax({
            url: 'api/all-routes.php/del',
            type: 'POST',
            data: {ids: dataAttributes},
            success: function (response) {
                alert('Удалена точка маршрута')
                window.location.reload();
            },
            error: function () {
                alert('Ошибка при обработке запроса.');
            }
        });
    });
    // Добавление записи
    $('main').on('click', '.add-btn', function () {
        $(this).hide()
        const parentDiv = $(this).parent();
        if(parentDiv.find('select').length > 0) 
        {
            return;
        }
        let existingStops = []
        parentDiv.parent().find('li').each(function () {
            existingStops.push($(this).data('stopId'));
        });

        parentDiv.append( 
            `<select>
                <?php $db->getStops();?>
            </select>`+
            `<button class="btns btn btn-danger"
            onclick="$(this).parent().find('select').remove(); 
            $('.add-btn').show(); 
            $(this).remove()
            $('.add-confirm').remove()">
                Отмена
            </button>`+
            `<button class='add-confirm btn btn-success'
            onclick='addRouteStop($(this).parent())'>
                Подтвердить
            </button>`
        )
        parentDiv.find('select option').each(function () {
            const optionValue = parseInt($(this).val());
            if (existingStops.includes(optionValue)) {
                $(this).remove();
            }
        });
    })

    function addRouteStop(parentDiv) {
        const stopsSel = parentDiv.find('select');

        if (stopsSel.length < 1) {
            alert('Не выбраны значения!')
        } else {
            const selectedValue = stopsSel.val();
            const direction = parentDiv.parent().data('dir')
            const bus = parentDiv.parent().data('busId')
            $.ajax({
                url: 'api/all-routes.php/add',
                type: 'POST',
                data: {bus: bus, ids: selectedValue, dir: direction},
                success: function (response) {
                    console.log(response)
                    alert('Добавлена точка маршрута')
                    window.location.reload();
                },
                error: function () {
                    alert('Ошибка при обработке запроса.');
                }
            });

            return selectedValue;
        }
    }


 </script>
</body>
</html>