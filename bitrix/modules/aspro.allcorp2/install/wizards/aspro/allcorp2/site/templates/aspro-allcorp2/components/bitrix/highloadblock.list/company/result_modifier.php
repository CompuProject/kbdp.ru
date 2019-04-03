<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?
if($arResult['rows'])
{
	\Bitrix\Main\Type\Collection::sortByColumn($arResult['rows'], array('SORT' => array(SORT_NUMERIC, SORT_ASC)));
}
?>