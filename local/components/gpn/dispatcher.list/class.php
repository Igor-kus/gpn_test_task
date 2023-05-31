<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use GPN\TestTask\Infrastructure\Model;
use Bitrix\Main\Loader;

class DispatcherList extends \CBitrixComponent
{
    private $CACHE_TIME_DEFAULT = 3600;

    protected function checkModules(): void
    {
        if (!Loader::includeModule('gpn.testtask')) {
            throw new \Exception(Loc::getMessage('GPN_TESTTASK_NOT_LOAD'));
        }
    }

    protected function getResult(): array
    {
        $arResult = [];
        if (!$this->startResultCache()) return $this->arResult;
        
        $entityDispatcher = Model\DispatcherTable::getEntity();
        $query = new \Bitrix\Main\ORM\Query\Query($entityDispatcher);

        $query
            ->setSelect(
                [
                    'ID',
                    'USER_ID',
                    'USER',
                    'USER.LAST_NAME',
                    'USER.NAME',
                    'USER.LAST_LOGIN',
                    'LEVEL_PERMITION',
                    'COMMENT',
                    'OBJECT',
                    'OBJECT.ID',
                    'OBJECT.NAME'
                ]
            )
            ->setFilter(
                [
                    '=ACTIVE' => true
                ]
            )
        ;

        $dispatcherCollection = $query->exec()->fetchCollection();

        foreach($dispatcherCollection as $dispatcherObject) {
            $arResult['items'][] = [
                'LAST_NAME' => $dispatcherObject->getUser()->getLastName(),
                'NAME' => $dispatcherObject->getUser()->getName(),
                'LEVEL_PERMITION' => $dispatcherObject->getLevelPermition(),
                'LAST_LOGIN' => $dispatcherObject->getUser()->getLastLogin() ? $dispatcherObject->getUser()->getLastLogin()->toString() : '',
                'COMMENT' => $dispatcherObject->getComment(),
                'OBJECT' => [
                    'ID' => $dispatcherObject->getObject()->getId(),
                    'NAME' => $dispatcherObject->getObject()->getName(),
                ]
            ];
        }

        $this->arResult = $arResult;

        $this->SetResultCacheKeys(
            $this->arResult
        );
        
        return $this->arResult;
    }

    public function onPrepareComponentParams($arParams): array
    {
        $arParams = parent::onPrepareComponentParams($arParams);

        if(!$arParams['CACHE_TIME']){
            $arParams['CACHE_TIME'] = $this->CACHE_TIME_DEFAULT;
        } else {
            $arParams['CACHE_TIME'] = (int) $arParams['CACHE_TIME'];
        }

        return $arParams;
    }

    public function executeComponent()
    {
        try {
            $this->checkModules();
            $this->arResult = $this->getResult();
            $this->includeComponentTemplate();
        } catch (\Exception $exception) {
            ShowError($exception->getMessage());
        }
    }

}