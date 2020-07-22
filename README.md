# test_articul

Компонент вывода списка пользователей в любой части сайта.
-----------------------------------

Установка: скачайте репозиторий в папку сайта /local/components/
***
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
***
В файл init.php добавляем события сбрасывания кэша, при каких-либо действиях с пользователями при неоходимости:
```php
use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();
$eventManager->addEventHandlerCompatible("main", "OnAfterUserAdd", function(&$fields) {
    \Bitrix\Main\UserTable::getEntity()->cleanCache();
});
$eventManager->addEventHandlerCompatible("main", "OnAfterUserUpdate", function(&$fields) {
    \Bitrix\Main\UserTable::getEntity()->cleanCache();
});
$eventManager->addEventHandlerCompatible("main", "OnAfterUserDelete", function(&$fields) {
    \Bitrix\Main\UserTable::getEntity()->cleanCache();
});
```
