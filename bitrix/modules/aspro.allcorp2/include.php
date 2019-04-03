<?php
/**
 * Allcorp2 module
 * @copyright 2017 Aspro
 */

CModule::AddAutoloadClasses(
	'aspro.allcorp2',
	array(
		'allcorp2' => 'install/index.php',
		'CAllcorp2' => 'classes/general/CAllcorp2.php',
		'CCache' => 'classes/general/CCache.php',
		'CAllcorp2Tools' => 'classes/general/CAllcorp2Tools.php',
		'CAllcorp2Events' => 'classes/general/CAllcorp2Events.php',
		'CInstargramAllcorp2' => 'classes/general/CInstargramAllcorp2.php',
		'CAllcorp2Regionality' => 'classes/general/CAllcorp2Regionality.php',
		'Aspro\\Functions\\CAsproAllcorp2' => 'lib/functions/CAsproAllcorp2.php', //for only solution functions
		'Aspro\\Functions\\CAsproAllcorp2CRM' => 'lib/functions/CAsproAllcorp2CRM.php', //for crm
		'Aspro\\Functions\\CAsproAllcorp2Custom' => 'lib/functions/CAsproAllcorp2Custom.php', //for user custom functions
		'Aspro\\Functions\\CAsproAllcorp2ReCaptcha' => 'lib/functions/CAsproAllcorp2ReCaptcha.php', //for google reCaptcha
	)
);

/* custom events */

// AddEventHandler('aspro.allcorp2', 'OnAsproShowPageType', array('\Aspro\Functions\CAsproAllcorp2', 'OnAsproShowPageTypeHandler')); 
// function - CAllcorp2::ShowPageType

// AddEventHandler('aspro.allcorp2', 'OnAsproParameters', array('\Aspro\Functions\CAsproAllcorp2', 'OnAsproParametersHandler')); 
// function - CAllcorp2::$arParametrsList