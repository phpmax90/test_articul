<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array(
	"NAME" => Loc::getMessage('SMAX_USER_COMPONENT_NAME'),
	"DESCRIPTION" => Loc::getMessage('SMAX_USER_DESCRIPTION'),
	"SORT" => 20
);

?>