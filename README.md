**Схема БД**
![alt text](https://github.com/chugumba/TEST_TASK_PHP/blob/master/db_schema.png?raw=true)

Чтобы запустить данное веб-приложение необходимо:
1) Установить Docker на ПК на котором будет запускаться проект.
2) Открыть в терминале папку с проектом.
3) Выполнить команды: 
docker-compose build
docker-compose up -d
docker exec -it db pg_restore -U max -h localhost -d bus /docker-entrypoint-initdb.d
(последняя команда нужна для выгрузки дампа БД в контейнер)
4) Открыть приложение в браузере по адресу http://localhost:8080/ .
5) Чтобы проверить программу стоит выбрать маршрут от ул. Попова до ул. Ленина.

