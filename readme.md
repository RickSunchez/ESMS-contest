При первом запуске подтягивает данные со Steam API, нужно немного подождать.

...или

Поменять количество подгружаемых игр в файле `app/core/model.php` в методе `mesh()`

```php
$apps = $this -> get_app_details(<games_count>);
```
