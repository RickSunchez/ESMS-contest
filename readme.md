## v0

При первом запуске подтягивает данные со Steam API, нужно немного подождать.

...или

Поменять количество подгружаемых игр в файле `app/core/model.php` в методе `mesh()`

```php
$apps = $this -> get_app_details(<games_count>);
```

## v1

- setup .env file:

```
DB_CONNECTION=sqlite
DB_DATABASE=<file_name>
STEAM_APPS=<file_name>
STEAM_CACHE=<file_name>
```

- migrate `php artisan migrate`
- seed `php artisan db:seed --class=VideogamesSeeder`
- serve `php artisan serve`

Api examples in a postman file: `Videogames.postman_collection.json`;