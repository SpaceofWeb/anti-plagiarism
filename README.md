AP - программа анти плагиат для дипломных работ
<hr>

## Технологии
```json
  Node.js ^8.11.1
  PHP ^7.0
```

## Зависимости

### Node.js
Зависимости node.js подгружаются через npm.  
Вам необходимо вписать команду `npm install` и зависимости будут установленны.  

  **[MySQL](https://github.com/mysqljs/mysql) ^2.15.0**  
  **[String-Similarity](https://github.com/aceakash/string-similarity) ^1.2.0**  


### PHP
  **[PHP-MySQLi-Database-Class](https://github.com/ThingEngineer/PHP-MySQLi-Database-Class)** включен в проект  
  **[PHPDocumentParser](https://github.com/LukeMadhanga/PHPDocumentParser)** включен в проект  


### PHP mods
  PHP Zip  


## Структура проекта
```
access - статичные файлы, стили, js-скрипты
dumps - дампы базы данных
engine - движок
  ajax - скрипты для работы с базой
  classes - классы php
  compare - скрипт node.js для сравнения дипломных работ
  inc - конфигурация, подключение к базе данных
```

## Настройка
Настройка php производится в файле `engine/inc/conf.php`   
Настройка node.js производится в файле `engine/compare/compare.js`   


## Сравнение
Скрипт node.js сравнивает дипломные работы алгоритмом [Сёренсена-Дайса](https://en.wikipedia.org/wiki/S%C3%B8rensen%E2%80%93Dice_coefficient)
