<?php
namespace GPN\TestTask\Handler;

use Bitrix\Main\Type;
use GPN\TestTask\Infrastructure\Model;

class User 
{
    public function deactivationDispatcher(array $arFields): void
    {
        $userId = (int) $arFields['ID'];
        
        if(!$userId) {
            return;
        }
        
        if($arFields['ACTIVE'] == 'Y') {
            return;
        }

        $entityDispatcher = Model\DispatcherTable::getEntity();
        $query = new \Bitrix\Main\ORM\Query\Query($entityDispatcher);

        $query
            ->setSelect(
                [
                    'ID',
                    'ACTIVE',
                    'USER_ID'
                ]
            )
            ->setFilter(
                [
                    '=USER_ID' => $userId,
                    '=ACTIVE' => true
                ]
            )
        ;

        $dispatcher = $query->exec()->fetchObject();

        if(!$dispatcher) {
            return;
        }

        $dispatcher->setActive(false);
        $dispatcher->setDateBlock(new Type\DateTime());
        $dispatcher->save();
    }
}
