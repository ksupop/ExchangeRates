# ExchangeRates
## Демоверсия
Можно посмотреть здесь [Информационная система "Курсы валют"](http://176.108.144.234/)
## Инструкция пользователя

На странице системы организован постраничный вывод общей таблицы с данными валют, взятыми с сайта https://www.cbr.ru/development/SXML/. 

При нажатии на заголовок определенного столбца таблицы данные сортируются по этому столбцу по возрастанию и убыванию. 

При нажатии на кнопку "Удалить" строки таблицы можно удалить эту строку из таблицы. 

С помощью кнопки "Добавить валюту" валюту можно внести вручную в таблицу, заполнив поля формы в открывшемся модальном окне.

Для того, чтобы построить график необходимо нажать на соответствующую кнопку "Построить график". В открывшемся модальном окне выбрать валюту и диапазон дат. После этого отобразится график и появится кнопка "Сохранить в JSON", которая позволяет сохранить данные графика в формате json.

## Установка (Linux)
Система разработана на PHP 8.2.18. Использовалась база данных PostgreSQL 10.23.
Необходимо склонировать все файлы репозитория. 
___
`valutes.sql` - дамп базы данных (его необходимо импортировать),
`db.php` - файл подключения к базе данных, 
`gettingDataForPeriod.php` - файл получения данных о валютах за определенный период. С помощью него можно заполнить базу данных. 
`gettingData.php` - файл получения данных за сегодняшний день. Его необходимо добавить в crontab на веб-сервере.

Остальные файлы нужны для логики работы системы.
___

Требуется установка на веб-сервере следующих пакетов:

`apt-get install httpd2 apache2-mod_php8.2 php8.2-pgsql php8.2-mbstring`
Также необходимо отредактировать конфигурационные файлы httpd2
