<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

use Bitrix\Main\Localization\Loc;
?>

<?if(!$arResult) {
    echo Loc::getMessage('GPN_EMPTY');
    return;   
}?>

<table border="1">
    <tr>
        <th><?=Loc::getMessage('GPN_DISPATCHER_LAST_NAME')?></th>
        <th><?=Loc::getMessage('GPN_DISPATCHER_NAME')?></th>
        <th><?=Loc::getMessage('GPN_DISPATCHER_LEVEL_PERMITION')?></th>
        <th><?=Loc::getMessage('GPN_DISPATCHER_LAST_LOGIN')?></th>
        <th><?=Loc::getMessage('GPN_DISPATCHER_COMMENT')?></th>
        <th><?=Loc::getMessage('GPN_DISPATCHER_OBJECT')?></th>
    </tr>
    <?foreach($arResult['items'] as $dispatcher):?>
`       <tr>
            <td><?=$dispatcher['LAST_NAME']?></td>
            <td><?=$dispatcher['NAME']?></td>
            <td><?=$dispatcher['LEVEL_PERMITION']?></td>
            <td><?=$dispatcher['LAST_LOGIN']?></td>
            <td><?=$dispatcher['COMMENT']?></td>
            <td>[<?=$dispatcher['OBJECT']['ID']?>] <?=$dispatcher['OBJECT']['NAME']?></td>
        </tr>
    <?endforeach;?>
</table>