# test_articul

Компонент вывода списка пользователей в любой части сайта.
-----------------------------------

Установка: скачайте репозиторий в папку сайта /local/components/
В нужном месте добавьте код вывода компонента:
```php
<?$APPLICATION->IncludeComponent(
    "smax:user.list", 
    "", 
    array(
        'AJAX_MODE' => 'Y', 
        'SHOW_CSV_DOWNLOAD' => 'Y', 
        'SHOW_XML_DOWNLOAD' => 'Y'
    )
);?>
```
