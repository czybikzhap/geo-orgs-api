/**
* # 📖 Organizations REST API (Laravel 12 + Spatie Query Builder)
*
* REST API для справочника организаций с поддержкой фильтров, поиска по названию,
* географических координат, иерархии видов деятельности, а также интеграцией
* со Swagger для удобного тестирования.
## Установка и настройка

1. Установите зависимости:
```bash
composer install 
``` 
```bash
composer require spatie/laravel-query-builder
``` 
```bash
composer require darkaonline/l5-swagger
``` 


2. Настройте подключение к базе данных в файле `.env`

3. Выполните миграции:
```bash
php artisan migrate
```

4. Заполните базу тестовыми данными:
```bash
php artisan db:seed
```

## Базовый URL
```
http://localhost:8081/api/organizations
```


* ## 📚 Основные возможности API
*
* - 🔎 Поиск организаций по названию: `filter[name]`
* - 🏢 Фильтрация по ID здания: `filter[building_id]`
* - 📂 Фильтрация по виду деятельности с учетом потомков: `filter[activity_id]`
* - 🌍 Географические фильтры:
*   - Радиус поиска: `latitude`, `longitude`, `radius`
*   - Bounding box: `min_lat`, `max_lat`, `min_lng`, `max_lng`
* - 🔑 Авторизация через заголовок `X-API-KEY`

* ---
*
* ## 🔍 Примеры запросов
*
* ### Получить список организаций
* GET /api/organizations
*
* ### Поиск по названию
* GET /api/organizations?filter[search]=продукты
*
* ### Фильтр по виду деятельности 
* GET /api/organizations?filter[activity_id]=2
*
* ### Фильтр по зданию
* GET /api/organizations?filter[building_id]=3
*
* ### Поиск по радиусу (1 км от точки)
* GET /api/organizations?filter[radius_filter]=true&latitude=55.752220&longitude=37.615560&radius=1
*
* ### Bounding box (карта)
* GET /api/organizations?filter[bbox][min_lat]=55.70&filter[bbox][max_lat]=55.80&filter[bbox][min_lng]=37.62&filter[bbox][max_lng]=37.70
*
* ### Получить организацию по ID
* GET /api/organizations/1
*
* ---
*
* ## 📖 Swagger UI
* Сгенерировать спецификацию:
* ```bash
* php artisan l5-swagger:generate
* ```
*
* Swagger будет доступен по адресу:
* ```
* http://localhost:8081/api/documentation
* ```
*
