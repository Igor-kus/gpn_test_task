<?php
namespace GPN\TestTask\Infrastructure\Model;

use Bitrix\Main\Event;
use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM;

class ObjectTable extends ORM\Data\DataManager
{
    public static function getTableName(): string
    {
        return 'gpn_testtask_object';
    }

    public static function getMap(): array
    {
        return [
            new ORM\Fields\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),

            new ORM\Fields\DatetimeField('DATE_CREATE', [
                'default_value' => new Type\DateTime(),
                'required' => true,
                'title' => Loc::getMessage('GPN_MODEL_TESTTASK_DATE_CREATE')
            ]),

            new ORM\Fields\StringField('NAME', [
                'required' => true,
                'title' => Loc::getMessage('GPN_MODEL_TESTTASK_NAME')
             ]),

            new ORM\Fields\StringField('ADDRESS', [
                'nullable' => true,
                'title' => Loc::getMessage('GPN_MODEL_TESTTASK_ADDRESS')
             ]),

            new ORM\Fields\TextField('COMMENT', [
                'nullable' => true,
                'title' => Loc::getMessage('GPN_MODEL_TESTTASK_COMMENT')
             ]),

             (new ORM\Fields\Relations\OneToMany('DISPATCHERS', DispatcherTable::class, 'OBJECT')),
        ];
    }
}