# Clean Php

- 1 точка входа приложение public/index.php
- 2 здесь все запросы перенапровляются на файл index.php с помошю .htaccess
- 3 создается экзепляр класса App в единством экземпляре
- 4 в App Иницализурется Model, Router, сохраняется данние из файла конфиг 
- 5 Router здесь получаем все маршуты и обработатоваем параметры + запросы, 
если нужно то создаем экземпляр зависомости обработоваем валидацию в Request (IRequest)
если все норм то создаем класс контроллера и вызоваем нужни метод передавая в нем параметры
- 6 Controller приходять уже валидирование данние спомошю зависимую класса
здесь есть два решение, первый все даем в HTML документ, второе JSON данных
для JSON данных есть класс JsonResource который переборозует данние в json
- 7 данние сохраняются и выводится через класса наследуемие иж Model
- 8 в приложение исползуется ReadBinPhp библиатека для работы с базы данных + dumper из Symphony


## В основно зоплолнял базовие классый, это методы которы нужны для работы приложение
### но при желание можно расширать класса добовляя методы в дочерном классе

### Для запуска приложение нужно вызовать из консолы 
 - docker-compose build
 - docker-compose up
