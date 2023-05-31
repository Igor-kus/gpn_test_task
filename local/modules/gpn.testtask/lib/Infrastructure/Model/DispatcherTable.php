<?php
namespace GPN\TestTask\Infrastructure\Model;

use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM;

class DispatcherTable extends ORM\Data\DataManager
{
    public static function getTableName(): string
    {
        return 'gpn_testtask_dispatcher';
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

            new ORM\Fields\BooleanField('ACTIVE', [
                'default_value' => true,
                'title' => Loc::getMessage('GPN_MODEL_TESTTASK_ACTIVE')
            ]),

            new ORM\Fields\DatetimeField('DATE_BLOCK', [
                'nullable' => true,
                'title' => Loc::getMessage('GPN_MODEL_TESTTASK_DATE_BLOCK')
            ]),

            new ORM\Fields\IntegerField('USER_ID', [
                'primary' => true,
                'title' => Loc::getMessage('GPN_MODEL_TESTTASK_USER_ID')
            ]),

            new ORM\Fields\TextField('COMMENT', [
                'nullable' => true,
                'title' => Loc::getMessage('GPN_MODEL_TESTTASK_COMMENT')
             ]),

            new ORM\Fields\IntegerField('LEVEL_PERMITION', [
                'required' => true,
                'title' => Loc::getMessage('GPN_MODEL_TESTTASK_LEVEL_PERMITION'),
                'validation' => new ORM\Fields\Validators\RangeValidator(1, 12),
            ]),

            new ORM\Fields\IntegerField('OBJECT_ID', [
                'required' => true,
                'title' => Loc::getMessage('GPN_MODEL_TESTTASK_OBJECT_ID'),
            ]),

            new ORM\Fields\Relations\Reference(
                'USER',
                \Bitrix\Main\UserTable::class,
                ORM\Query\Join::on('this.USER_ID', 'ref.ID')
            ),

            new ORM\Fields\Relations\Reference(
                'OBJECT',
                ObjectTable::class,
                ORM\Query\Join::on('this.OBJECT_ID', 'ref.ID')
            )
        ];
    }
}