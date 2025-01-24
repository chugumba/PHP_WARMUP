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
                <li id="${station.route_id}" class="ui-state-default">
                    <button id="del-btn" type="button" class="btns btn btn-danger"><span class="ui-icon ui-icon-trash  "></span></button>
                    Ост. ${station.stop_name} <span id="${station.stop}" class="ui-icon ui-icon-arrowthick-1-e"></span>
                </li>`
            })

            $('main').append(
                `<div class="route" id="${element.bus}">
                    <h1>${element.bus_name} ${element.dir == 'forward' ? "Вперёд" : "Назад"}</h1>
                    <ul class="sortable">
                        ${stations}
                    </ul>
                    
                    <div>
                        <button id="upd-btn" type="button" class="btns btn btn-primary">Обновить порядок</button>
                        <button id="add-btn" type="button" class="btns btn btn-success">Добавить остановку</button>
                    </div>
                </div>`
            )
        });
        
        $( ".sortable" ).sortable();
    }

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
 </script>
</body>
</html>