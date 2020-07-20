<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);

$arComponentParameters = array(
    'GROUPS' => array(
    ),
    'PARAMETERS' => array(
        'SHOW_CSV_DOWNLOAD' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('SMAX_USER_COMPONENT_SHOW_CSV_DOWNLOAD'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y'
        ),
        'SHOW_XML_DOWNLOAD' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('SMAX_USER_COMPONENT_SHOW_XML_DOWNLOAD'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y'
        ),
        'SHOW_NAV' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('SMAX_USER_COMPONENT_SHOW_NAV'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y'
        ),
        'CACHE_TIME' => array(
            'DEFAULT' => 3600
        )
    )
);
?>