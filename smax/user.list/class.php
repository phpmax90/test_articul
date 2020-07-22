<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc as Loc;
use \Bitrix\Main\UserTable;
use Bitrix\Main\Engine\Contract\Controllerable;

class UserListComponent extends CBitrixComponent implements Controllerable
{
    protected $navParams = [];
    protected $returned;
    protected $filter = ['ACTIVE' => 'Y'];

    public function onIncludeComponentLang()
    {
        $this->includeComponentLang(basename(__FILE__));
        Loc::loadMessages(__FILE__);
    }

    public function configureActions()
    {
        return [
            'getFile' => [
                'prefilters' => [],
                'postfilters' => []
            ]
        ];
    }
    public function getFileAction()
    {
        $type = $_POST['type'];

        if($type == 'csv')
        {
            $file = $this->generateFileCsv();
            copy($_SERVER['DOCUMENT_ROOT'].'/upload/'.$file, $_SERVER['DOCUMENT_ROOT'].'/upload/users.csv');
        }
        else
        {
            $file = $this->generateFileXml();
            copy($_SERVER['DOCUMENT_ROOT'].'/upload/'.$file, $_SERVER['DOCUMENT_ROOT'].'/upload/users.xml');
        }

        unlink($_SERVER['DOCUMENT_ROOT'].'/upload/'.$file);

        return ['success' => true];
    }

    public function onPrepareComponentParams($params)
    {
        $result = array(
            'CACHE_TIME' => intval($params['CACHE_TIME']) > 0 ? intval($params['CACHE_TIME']) : 3600,
            'AJAX' => $params['AJAX'] == 'N' ? 'N' : $_REQUEST['AJAX'] == 'Y' ? 'Y' : 'N',
            'SHOW_NAV' => $params['SHOW_NAV'] == 'N' ? 'N' : 'Y',
            'SHOW_CSV_DOWNLOAD' => $params['SHOW_CSV_DOWNLOAD'] == 'N' ? 'N' : 'Y',
            'SHOW_XML_DOWNLOAD' => $params['SHOW_XML_DOWNLOAD'] == 'N' ? 'N' : 'Y',
            'COUNT' => intval($params['COUNT']) > 0 ? intval($params['COUNT']) : 10,
        );
        return $result;
    }

    protected function getResult()
    {
        $nav = new \Bitrix\Main\UI\PageNavigation("users");
        $nav->allowAllRecords(true)
            ->setPageSize($this->arParams['COUNT'])
            ->initFromUri();

        $result = UserTable::getList(
            [
                'select' => array('ID', 'NAME', 'EMAIL'),
                'filter' => $this->filter,
                'order'  => array('ID' => 'DESC'),
                'limit'  => $nav->GetLimit(),
                'offset'  => $nav->GetOffset(),
                'count_total' => true,
                'cache'  => array("ttl" => $this->arParams['CACHE_TIME'])
            ]
        );

        $nav->setRecordCount(UserTable::getCount($this->filter));

        while ($arUser = $result->fetch())
        {
            $this->arResult['ITEMS'][] = array(
                'ID' => $arUser['ID'],
                'NAME' => $arUser['NAME'],
                'EMAIL' => $arUser['EMAIL'],
            );
        }

        if ($this->arParams['SHOW_NAV'] == 'Y' && $this->arParams['COUNT'] > 0)
        {
            $this->arResult['NAV'] = $nav;
        }
    }

    public function getUserList(){
        $list = [];
        $result = UserTable::getList(
            [
                'select' => array('ID', 'NAME', 'EMAIL'),
                'filter' => $this->filter,
                'order'  => array('ID' => 'DESC')
            ]
        );

        while ($arUser = $result->fetch())
        {
            $list[] = $arUser;
        }

        return $list;
    }

    public function generateFileCsv()
    {
        $tmp_file = 'users_'.uniqid().'.csv';
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/upload/'.$tmp_file;
        $fp = fopen($filePath, 'w+');
        @fclose($fp);

        $fields_type = 'R';
        $delimiter = ";";
        $csvFile = new \CCSVData($fields_type, false);
        $csvFile->SetFieldsType($fields_type);
        $csvFile->SetDelimiter($delimiter);
        $csvFile->SetFirstHeader(true);

        $result = $this->getUserList();

        foreach ($result as $arUser){
            $csvFile->SaveFile($filePath, [$arUser['ID'], $arUser['NAME'], $arUser['EMAIL']]);
        }

        return $tmp_file;
    }

    public function generateFileXml()
    {
        $tmp_file = $_SERVER['DOCUMENT_ROOT'] . '/upload/users_'.uniqid().'.xml';
        $export = new \Bitrix\Main\XmlWriter(array(
            'file' => '/upload/'.$tmp_file,
            'create_file' => true,
            'charset' => 'UTF-8',
            'lowercase' => false
        ));
        $export->openFile();

        $export->writeBeginTag('users');

        $result = $this->getUserList();

        foreach ($result as $arUser){
            $export->writeBeginTag('item');
            $export->writeFullTag('ID', $arUser['ID']);
            $export->writeFullTag('NAME', $arUser['NAME']);
            $export->writeFullTag('EMAIL', $arUser['EMAIL']);
            $export->writeEndTag('item');
        }

        $export->writeEndTag('users');
        $export->closeFile();

        return $tmp_file;
    }

    public function downloadFile($type){
        global $APPLICATION;

        $APPLICATION->restartBuffer();

        if(!in_array($type, ['csv', 'xml'])) return false;

        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/upload/users.'.$type;
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filePath));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);

        exit;
    }

    public function executeComponent()
    {
        if(isset($_GET['get_file'])) $this->downloadFile($_GET['get_file']);

        $this->getResult();
        $this->includeComponentTemplate();

        return $this->returned;
    }
}
?>