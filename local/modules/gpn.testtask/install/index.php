<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Loader;
use GPN\TestTask\Handler;

Loc::loadMessages(__FILE__);

class gpn_testtask extends CModule
{
    public $MODULE_ID = 'gpn.testtask';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__.'/version.php';
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('GPN_TESTTASK_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('GPN_TESTTASK_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('GPN_TESTTASK_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('GPN_TESTTASK_PARTNER_URI');
    }

    public function GetPath()
    {
        return dirname(__DIR__);
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallEvents();
        $this->InstallDB();
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $step = (int) $request->get('step');

        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('GPN_TESTTASK_UNINSTALL_TITLE'),
                $this->GetPath() . '/install/unstep1.php'
            );
        } elseif ($step === 2) {

            $this->UnInstallEvents();

            if ($request->get('savedata') !== 'Y') {
                $this->UnInstallDB();
            }

            ModuleManager::unRegisterModule($this->MODULE_ID);

            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('GPN_TESTTASK_UNINSTALL_TITLE'),
                $this->GetPath() . '/install/unstep2.php'
            );
        }
    }

    public function InstallEvents(): void
    {
        $eventManager = EventManager::getInstance();

        foreach($this->getEventsList() as $event){
            $eventManager->registerEventHandler(...$event);
        }
    }

    public function UnInstallEvents(): void
    {
        $eventManager = EventManager::getInstance();

        foreach($this->getEventsList() as $event){
            $eventManager->unRegisterEventHandler(...$event);
        }
    }

    public function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        $connection = Application::getConnection();

        foreach($this->getModels() as $model){

            $className = $this->getModelClassName($model);

            $tableName = $className::getTableName();
            if (!$connection->isTableExists($tableName)) {
                $className::getEntity()->createDbTable();
            }
        }
    }

    public function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        $connection = Application::getConnection();

        foreach(array_reverse($this->getModels()) as $model){

            $className = $this->getModelClassName($model);

            $tableName = $className::getTableName();
            if ($connection->isTableExists($tableName)) {
                $connection->dropTable($tableName);
            }
        }
    }

    protected function getModels(): array
    {
        return [
            'Object',
            'Dispatcher',
        ];
    }

    protected function getModelClassName(string $model): string
    {
        return 'GPN\\TestTask\\Infrastructure\\Model\\' . $model . 'Table';
    }

    protected function getEventsList(): array
    {
        return [
            ['main', 'OnAfterUserUpdate', $this->MODULE_ID, Handler\User::class, 'deactivationDispatcher'],
        ];
    }


}