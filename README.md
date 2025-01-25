**Схема БД** <br />
![alt text](https://github.com/chugumba/TEST_TASK_PHP/blob/master/db_schema.png?raw=true)

Чтобы запустить данное веб-приложение необходимо: <br />
1) Установить Docker на ПК на котором будет запускаться проект. <br />
2) Открыть в терминале папку с проектом. <br />
3) Выполнить команды: <br />
docker-compose build <br />
docker-compose up -d <br />
docker exec -it db pg_restore -U max -h localhost -d bus /docker-entrypoint-initdb.d <br />
(последняя команда нужна для выгрузки дампа БД в контейнер) <br />
4) Открыть приложение в браузере по адресу http://localhost:8080/ . <br />
5) Чтобы проверить программу стоит выбрать маршрут от ул. Попова до ул. Ленина. <br />

Если возникают проблемы с портами, из можно поменять в файле docker-compose.yml (Меннять только выделенную часть) <br />
  ports:
      - "**5432**:5432" # Ставим порт 5432
