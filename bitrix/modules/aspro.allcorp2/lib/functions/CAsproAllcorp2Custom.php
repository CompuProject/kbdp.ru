<?
namespace Aspro\Functions;

use Bitrix\Main\Application;
use Bitrix\Main\Web\DOM\Document;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\DOM\CssParser;
use Bitrix\Main\Text\HtmlFilter;
use Bitrix\Main\IO\File;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

//user custom functions

if(!class_exists("CAsproAllcorp2Custom"))
{
	class CAsproAllcorp2Custom{
		const MODULE_ID = \CAllcorp2::moduleID;

	}
}?>