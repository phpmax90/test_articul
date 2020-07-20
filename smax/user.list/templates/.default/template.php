<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?
use Bitrix\Main\Localization\Loc as Loc;
use Bitrix\Main\UI\Extension;

Extension::load('ui.bootstrap4');
Loc::loadMessages(__FILE__);
$this->setFrameMode(true);
?>

<?if(count($arResult['ITEMS'])):?>
    <div class="row mb-2">
        <?if($arParams['SHOW_CSV_DOWNLOAD'] == 'Y'):?>
            <div class="col-6 text-center">
                <a href="?task=getCsv" class="btn btn-warning" role="button">Выгрузить CSV</a>
            </div>
        <?endif;?>
        <?if($arParams['SHOW_XML_DOWNLOAD'] == 'Y'):?>
            <div class="col-6 text-center">
                <a href="?task=getXml" class="btn btn-warning" role="button">Выгрузить XML</a>
            </div>
        <?endif;?>
    </div>
    <div class="users-list">
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>E-mail</th>
            </tr>
            </thead>
            <tbody>
            <? foreach ($arResult['ITEMS'] as $item):?>
                <tr>
                    <td><?=$item['ID']?></td>
                    <td><?=$item['NAME']?></td>
                    <td><?=$item['EMAIL']?></td>
                </tr>
            <? endforeach;?>
            </tbody>
        </table>

        <?if($arParams['SHOW_NAV'] == 'Y'):?>
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:main.pagenavigation",
                "",
                array(
                    "NAV_OBJECT" => $arResult['NAV'],
                ),
                false
            );
            ?>
        <?endif;?>
    </div>
<?endif;?>
