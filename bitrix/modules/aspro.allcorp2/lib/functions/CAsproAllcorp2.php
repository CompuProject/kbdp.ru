<?
namespace Aspro\Functions;

use Bitrix\Main\Application;
use Bitrix\Main\Web\DOM\Document;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\DOM\CssParser;
use Bitrix\Main\Text\HtmlFilter;
use Bitrix\Main\IO\File;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

if(!class_exists("CAsproAllcorp2"))
{
	class CAsproAllcorp2{
		const MODULE_ID = \CAllcorp2::MODULE_ID;

		/*public static function OnAsproShowPageTypeHandler($arTheme, &$arMainPageOrder){
			$arMainPageOrder[] = 'NEW_BLOCK';
		}*/

		/*public static function OnAsproParametersHandler(&$arParameters){
			//add new option with value
			$arParameters['TEST'] = array(
				'TITLE' => 'Test group',
				'OPTIONS' => array(
					'THEME_SWITCHER' => array(
						'TITLE' => 'Test title',
						'TYPE' => 'checkbox',
						'DEFAULT' => 'Y',
						'THEME' => 'N',
					)
				)
			);
			//add new value in exist option
			$arParameters['INDEX_PAGE']['OPTIONS']['INDEX_TYPE']['LIST']['index_test'] = 'index_test';
			$arParameters['INDEX_PAGE']['OPTIONS']['INDEX_TYPE']['SUB_PARAMS']['index4']['TEST'] = array(
				'TITLE' => 'Test title',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
				'ONE_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			);
		}*/

		//log to file
		public static function set_log($type="log", $path="log_file", $arMess=array()){
			$root = $_SERVER['DOCUMENT_ROOT'].'/upload/logs/'.self::MODULE_ID.'/'.$type.'/';
			if(!file_exists($root) || !is_dir($root))
				mkdir( $root, 0700, true );
			
			$path_date = $root.date('Y-m').'/';
			if(!file_exists($path_date) || !is_dir($path_date))
				mkdir( $path_date, 0700 );
			
			file_put_contents($path_date.$path.'.log', date('d-m-Y H-i-s', time()+\CTimeZone::GetOffset())."\n".print_r($arMess, true)."\n", LOCK_EX | FILE_APPEND);
		}

		protected static function _getAllFormFieldsHTML($WEB_FORM_ID, $RESULT_ID, $arAnswers)
		{
			global $APPLICATION;

			$strResult = "";

			$w = \CFormField::GetList($WEB_FORM_ID, "ALL", $by, $order, array("ACTIVE" => "Y"), $is_filtered);
			while ($wr=$w->Fetch())
			{
				$answer = "";
				$answer_raw = '';
				if (is_array($arAnswers[$wr["SID"]]))
				{
					$bHasDiffTypes = false;
					$lastType = '';
					foreach ($arAnswers[$wr['SID']] as $arrA)
					{
						if ($lastType == '') $lastType = $arrA['FIELD_TYPE'];
						elseif ($arrA['FIELD_TYPE'] != $lastType)
						{
							$bHasDiffTypes = true;
							break;
						}
					}

					foreach($arAnswers[$wr["SID"]] as $arrA)
					{
						if ($wr['ADDITIONAL'] == 'Y')
						{
							$arrA['FIELD_TYPE'] = $wr['FIELD_TYPE'];
						}

						$USER_TEXT_EXIST = (strlen(trim($arrA["USER_TEXT"]))>0);
						$ANSWER_TEXT_EXIST = (strlen(trim($arrA["ANSWER_TEXT"]))>0);
						$ANSWER_VALUE_EXIST = (strlen(trim($arrA["ANSWER_VALUE"]))>0);
						$USER_FILE_EXIST = (intval($arrA["USER_FILE_ID"])>0);

						if (
							$bHasDiffTypes
							&&
							!$USER_TEXT_EXIST
							&&
							(
								$arrA['FIELD_TYPE'] == 'text'
								||
								$arrA['FIELD_TYPE'] == 'textarea'
							)
						)
							continue;

						if (strlen(trim($answer))>0) $answer .= "<br />";
						if (strlen(trim($answer_raw))>0) $answer_raw .= ",";

						if ($ANSWER_TEXT_EXIST)
							$answer .= $arrA["ANSWER_TEXT"].': ';

						switch ($arrA['FIELD_TYPE'])
						{
							case 'text':
							case 'textarea':
							case 'hidden':
							case 'date':
							case 'password':

								if ($USER_TEXT_EXIST)
								{
									$answer .= htmlspecialcharsbx(trim($arrA["USER_TEXT"]));
									$answer_raw .= htmlspecialcharsbx(trim($arrA["USER_TEXT"]));
								}

							break;

							case 'email':
							case 'url':

								if ($USER_TEXT_EXIST)
								{
									$answer .= '<a href="'.($arrA['FIELD_TYPE'] == 'email' ? 'mailto:' : '').trim($arrA["USER_TEXT"]).'">'.htmlspecialcharsbx(trim($arrA["USER_TEXT"])).'</a>';
									$answer_raw .= htmlspecialcharsbx(trim($arrA["USER_TEXT"]));
								}

							break;

							case 'checkbox':
							case 'multiselect':
							case 'radio':
							case 'dropdown':

								if ($ANSWER_TEXT_EXIST)
								{
									$answer = htmlspecialcharsbx(substr($answer, 0, -2).' ');
									$answer_raw .= htmlspecialcharsbx($arrA['ANSWER_TEXT']);
								}

								if ($ANSWER_VALUE_EXIST)
								{
									$answer .= '('.htmlspecialcharsbx($arrA['ANSWER_VALUE']).') ';
									if (!$ANSWER_TEXT_EXIST)
										$answer_raw .= htmlspecialcharsbx($arrA['ANSWER_VALUE']);
								}

								if (!$ANSWER_VALUE_EXIST && !$ANSWER_TEXT_EXIST)
									$answer_raw .= $arrA['ANSWER_ID'];

								$answer .= '['.$arrA['ANSWER_ID'].']';

							break;

							case 'file':
							case 'image':

								if ($USER_FILE_EXIST)
								{
									$f = \CFile::GetByID($arrA["USER_FILE_ID"]);
									if ($fr = $f->Fetch())
									{
										$file_size = \CFile::FormatSize($fr["FILE_SIZE"]);
										$url = ($APPLICATION->IsHTTPS() ? "https://" : "http://").$_SERVER["HTTP_HOST"]. "/bitrix/tools/form_show_file.php?rid=".$RESULT_ID. "&hash=".$arrA["USER_FILE_HASH"]."&lang=".LANGUAGE_ID;

										if ($arrA["USER_FILE_IS_IMAGE"]=="Y")
										{
											$answer .= "<a href=\"$url\">".htmlspecialcharsbx($arrA["USER_FILE_NAME"])."</a> [".$fr["WIDTH"]." x ".$fr["HEIGHT"]."] (".$file_size.")";
										}
										else
										{
											$answer .= "<a href=\"$url&action=download\">".htmlspecialcharsbx($arrA["USER_FILE_NAME"])."</a> (".$file_size.")";
										}

										$answer_raw .= htmlspecialcharsbx($arrA['USER_FILE_NAME']);
									}
								}

							break;
						}
					}
				}

				$strResult .= $wr["TITLE"].":<br />".(strlen($answer)<=0 ? " " : $answer)."<br /><br />";
			}

			return $strResult;
		}

		protected static function _getAllFormFields($WEB_FORM_ID, $RESULT_ID, $arAnswers)
		{
			global $APPLICATION;

			$strResult = "";

			$w = \CFormField::GetList($WEB_FORM_ID, "ALL", $by, $order, array("ACTIVE" => "Y"), $is_filtered);
			while ($wr=$w->Fetch())
			{
				$answer = "";
				$answer_raw = '';
				if (is_array($arAnswers[$wr["SID"]]))
				{
					$bHasDiffTypes = false;
					$lastType = '';
					foreach ($arAnswers[$wr['SID']] as $arrA)
					{
						if ($lastType == '') $lastType = $arrA['FIELD_TYPE'];
						elseif ($arrA['FIELD_TYPE'] != $lastType)
						{
							$bHasDiffTypes = true;
							break;
						}
					}

					foreach($arAnswers[$wr["SID"]] as $arrA)
					{
						if ($wr['ADDITIONAL'] == 'Y')
						{
							$arrA['FIELD_TYPE'] = $wr['FIELD_TYPE'];
						}

						$USER_TEXT_EXIST = (strlen(trim($arrA["USER_TEXT"]))>0);
						$ANSWER_TEXT_EXIST = (strlen(trim($arrA["ANSWER_TEXT"]))>0);
						$ANSWER_VALUE_EXIST = (strlen(trim($arrA["ANSWER_VALUE"]))>0);
						$USER_FILE_EXIST = (intval($arrA["USER_FILE_ID"])>0);

						if (
							$bHasDiffTypes
							&& !$USER_TEXT_EXIST
							&& (
								$arrA['FIELD_TYPE'] == 'text'
								||
								$arrA['FIELD_TYPE'] == 'textarea'
							)
						)
						{
							continue;
						}

						if (strlen(trim($answer)) > 0)
							$answer .= "\n";
						if (strlen(trim($answer_raw)) > 0)
							$answer_raw .= ",";

						if ($ANSWER_TEXT_EXIST)
							$answer .= $arrA["ANSWER_TEXT"].': ';

						switch ($arrA['FIELD_TYPE'])
						{
							case 'text':
							case 'textarea':
							case 'email':
							case 'url':
							case 'hidden':
							case 'date':
							case 'password':

								if ($USER_TEXT_EXIST)
								{
									$answer .= trim($arrA["USER_TEXT"]);
									$answer_raw .= trim($arrA["USER_TEXT"]);
								}

							break;

							case 'checkbox':
							case 'multiselect':
							case 'radio':
							case 'dropdown':

								if ($ANSWER_TEXT_EXIST)
								{
									$answer = substr($answer, 0, -2).' ';
									$answer_raw .= $arrA['ANSWER_TEXT'];
								}

								if ($ANSWER_VALUE_EXIST)
								{
									$answer .= '('.$arrA['ANSWER_VALUE'].') ';
									if (!$ANSWER_TEXT_EXIST)
									{
										$answer_raw .= $arrA['ANSWER_VALUE'];
									}
								}

								if (!$ANSWER_VALUE_EXIST && !$ANSWER_TEXT_EXIST)
								{
									$answer_raw .= $arrA['ANSWER_ID'];
								}

								$answer .= '['.$arrA['ANSWER_ID'].']';

							break;

							case 'file':
							case 'image':

								if ($USER_FILE_EXIST)
								{
									$f = \CFile::GetByID($arrA["USER_FILE_ID"]);
									if ($fr = $f->Fetch())
									{
										$file_size = \CFile::FormatSize($fr["FILE_SIZE"]);
										$url = ($APPLICATION->IsHTTPS() ? "https://" : "http://").$_SERVER["HTTP_HOST"]. "/bitrix/tools/form_show_file.php?rid=".$RESULT_ID. "&hash=".$arrA["USER_FILE_HASH"]."&action=download&lang=".LANGUAGE_ID;

										if ($arrA["USER_FILE_IS_IMAGE"]=="Y")
										{
											$answer .= $arrA["USER_FILE_NAME"]." [".$fr["WIDTH"]." x ".$fr["HEIGHT"]."] (".$file_size.")\n".$url;
										}
										else
										{
											$answer .= $arrA["USER_FILE_NAME"]." (".$file_size.")\n".$url."&action=download";
										}
									}

									$answer_raw .= $arrA['USER_FILE_NAME'];
								}

							break;
						}
					}
				}

				$strResult .= $wr["TITLE"].":\r\n".(strlen($answer)<=0 ? " " : $answer)."\r\n\r\n";
			}

			return $strResult;
		}

		public static function prepareArray($arFields = array(), $arReplace = array(), $stamp = '_leads'){
			$arTmpFields = array();
			if($arFields && $arReplace)
			{
				foreach($arFields as $key => $value)
				{
					$key = str_replace($stamp, '', $key);
					if(in_array($key, $arReplace))
						$arTmpFields[$key] = $value;
				}
				// $arTmpFields = self::prepareArray($arFields, array('name', 'tags', 'budget'), '_leads');
			}
			return $arTmpFields;
		}

		public static function sendLeadCrmFromForm($WEB_FORM_ID, $RESULT_ID, $TYPE = 'ALL', $SITE_ID = SITE_ID, $CURL = false, $DECODE = false){
			$bIntegrationFlowlu = (Option::get(self::MODULE_ID, 'ACTIVE_LINK_FLOWLU', '', $SITE_ID) && (Option::get(self::MODULE_ID, 'ACTIVE_FLOWLU', 'N', $SITE_ID) == 'Y'));
			$bIntegrationAmoCrm = (Option::get(self::MODULE_ID, 'ACTIVE_LINK_AMO_CRM', '', $SITE_ID) && (Option::get(self::MODULE_ID, 'ACTIVE_AMO_CRM', 'N', $SITE_ID) == 'Y'));
			$result = "{'erorr':{'error_msg': 'error'}}";

			if($bIntegrationFlowlu || $bIntegrationAmoCrm)
			{
				$arAllMatchValues = array();

				$arMatchValuesFlowlu = unserialize(Option::get(self::MODULE_ID, 'FLOWLU_CRM_FIELDS_MATCH_'.$WEB_FORM_ID, '', $SITE_ID));
				$arMatchValuesAmoCrm = unserialize(Option::get(self::MODULE_ID, 'AMO_CRM_FIELDS_MATCH_'.$WEB_FORM_ID, '', $SITE_ID));

				//flowlu
				if($bIntegrationFlowlu && ($TYPE == 'ALL' || $TYPE == 'FLOWLU'))
					$arAllMatchValues['FLOWLU'] = $arMatchValuesFlowlu;
				//amocrm
				if($bIntegrationAmoCrm && ($TYPE == 'ALL' || $TYPE == 'AMO_CRM'))
					$arAllMatchValues['AMO_CRM'] = $arMatchValuesAmoCrm;

				if($arAllMatchValues)
				{
					//get fields
					\CForm::GetResultAnswerArray(
						$WEB_FORM_ID,
						$arrColumns,
						$arrAnswers,
						$arrAnswersVarname,
						array("RESULT_ID" => $RESULT_ID)
					);

					//get form
					\CFormResult::GetDataByID($RESULT_ID, array(), $arResultFields, $arAnswers);
				}

				if($arAllMatchValues)
				{
					$arPostFields = array();

					//fill main fieds
					foreach($arAllMatchValues as $crm => $arFields)
					{
						foreach($arFields as $key => $id)
						{
							switch($id)
							{
								case 'RESULT_ID':
									$arPostFields[$crm][$key] = $arResultFields['ID'];
								break;
								case 'FORM_SID':
									$arPostFields[$crm][$key] = $arResultFields['SID'];
								break;
								case 'FORM_NAME':
									$arPostFields[$crm][$key] = $arResultFields['NAME'];
								break;
								case 'SITE_ID':
									$arPostFields[$crm][$key] = $SITE_ID;
								break;
								case 'FORM_ALL':
									$arPostFields[$crm][$key] = self::_getAllFormFields($WEB_FORM_ID, $RESULT_ID, $arAnswers);
								break;
								case 'FORM_ALL_HTML':
									$arPostFields[$crm][$key] = self::_getAllFormFieldsHTML($WEB_FORM_ID, $RESULT_ID, $arAnswers);
								break;
							}
						}
					}

					//fill form fieds
					foreach($arAllMatchValues as $crm => $arFields)
					{
						foreach($arFields as $key => $id)
						{
							if($arrAnswers[$RESULT_ID][$id])
							{
								$bCanPushCrm = true;

								$arPostFields[$crm][$key] = (isset($arrAnswers[$RESULT_ID][$id][$id]['USER_TEXT']) && $arrAnswers[$RESULT_ID][$id][$id]['USER_TEXT'] ? $arrAnswers[$RESULT_ID][$id][$id]['USER_TEXT'] : $arrAnswers[$RESULT_ID][$id][$id]['ANSWER_TEXT']);
							}
						}
					}

					if($arPostFields)
					{
						foreach($arPostFields as $crm => $arFields)
						{
							if($crm == 'FLOWLU')
							{
								$url = str_replace('#DOMAIN#', Option::get(self::MODULE_ID, 'DOMAIN_'.$crm, '', $SITE_ID), \Aspro\Functions\CAsproAllcorp2CRM::FLOWLU_PATH);
								$arFields['api_key'] = Option::get(self::MODULE_ID, 'TOKEN_FLOWLU', '', $SITE_ID);
								$arFields['ref'] = 'form:aspro-allcorp2';
								$arFields['ref_id'] = $WEB_FORM_ID.'_'.$RESULT_ID;
								$name_field = 'name';
							}
							else
							{
								$name_field = 'name_leads';
								$url = str_replace('#DOMAIN#', Option::get(self::MODULE_ID, 'DOMAIN_'.$crm, '', $SITE_ID), \Aspro\Functions\CAsproAllcorp2CRM::AMO_CRM_PATH);
								if(!$arFields['tags_leads'])
									$arFields['tags_leads'] = Option::get(self::MODULE_ID, 'TAGS_AMO_CRM_TITLE', '', $SITE_ID);
							}
							if(!$arFields[$name_field])
								$arFields[$name_field] = Option::get(self::MODULE_ID, 'LEAD_NAME_'.$crm.'_TITLE', \Bitrix\Main\Localization\Loc::getMessage('ALLCORP2_MODULE_LEAD_NAME_'.$crm), $SITE_ID);

							$smCrmName = strtolower(str_replace('_', '', $crm));
							//log to file form request
							if(Option::get(self::MODULE_ID, 'USE_LOG_'.$crm, 'N', $SITE_ID) == 'Y')
							{
								\Aspro\Functions\CAsproAllcorp2::set_log('crm', $smCrmName.'_create_lead_request', $arFields);
							}

							//convert all to UTF8 encoding for send to flowlu
							foreach($arFields as $key => $value)
							{
								$arFields[$key] = iconv(LANG_CHARSET, 'UTF-8', $value);
							}

							$arFieldsLead = $arFields;

							if($crm == 'AMO_CRM')
							{
								//auth
								$result = \Aspro\Functions\CAsproAllcorp2CRM::authAmoCrm($SITE_ID);

								$arFieldsLeadTmp = $arFields;
								$arCustomFields = unserialize(Option::get(self::MODULE_ID, 'CUSTOM_FIELD_AMO_CRM', '', $SITE_ID));
								//prepare array
								$arFieldsLeadTmp = self::prepareArray($arFields, array('name', 'tags', 'budget'), '_leads');
								if($arCustomFields && $arCustomFields['leads'])
								{
									foreach($arCustomFields['leads'] as $key => $arProp)
									{
										if($arFields[$key.'_leads'])
										{
											$arFieldsLeadTmp['custom_fields'][] = array(
												'id' => $key,
												'values' => array(
													array(
														'value' => $arFields[$key.'_leads']
													)
												)
											);
										}
										elseif(isset($arProp['ENUMS']) && $arProp['ENUMS'])
										{
											foreach($arProp['ENUMS'] as $key2 => $value)
											{
												if($arFields[$key.'_'.$key2.'_leads'])
												{
													$arFieldsLeadTmp['custom_fields'][] = array(
														'id' => $key,
														'values' => array(
															array(
																'value' => $arFields[$key.'_'.$key2.'_leads'],
																'enum' => $value
															)
														)
													);
												}
											}
										}
									}
								}

								$arFieldsLead = array(
									'request' => array(
										'leads' => array(
											'add' => array(
												$arFieldsLeadTmp
											)
										)
									)
								);

								$CURL = $DECODE = true;
							}

							$result = \Aspro\Functions\CAsproAllcorp2CRM::query($url, \Aspro\Functions\CAsproAllcorp2CRM::$arCrmMethods[$crm]["CREATE_LEAD"], $arFieldsLead, $CURL, $DECODE);
							$arCrmResult = json_decode($result, true);
							unset($arFieldsLead);

							if(isset($arCrmResult['response']))
							{
								if($crm == 'AMO_CRM' && $arCrmResult['response']['leads']) // create contact and company for amocrm
								{
									$arLead = reset($arCrmResult['response']['leads']['add']);
									$leadID = $arLead['id'];

									//add notes
									if($arFields['notes_leads'])
									{
										$arFieldsNote = array(
											'request' => array(
												'notes' => array(
													'add' => array(
														array(
															'element_id' => $leadID,
															'element_type' => 2,
															'note_type' => 4,
															'text' => $arFields['notes_leads']
														),
													)
												)
											)
										);
										$resultNote = \Aspro\Functions\CAsproAllcorp2CRM::query($url, \Aspro\Functions\CAsproAllcorp2CRM::$arCrmMethods[$crm]["CREATE_NOTES"], $arFieldsNote, $CURL, $DECODE);
										
										unset($arFieldsNote);
										unset($resultNote);
									}

									//add company
									$company_id = 0;
									if($arCustomFields && $arCustomFields['companies'])
									{
										//prepare array
										$arFieldsCompanyTmp = self::prepareArray($arFields, array('name', 'tags'), '_companies');
										$arFieldsCompanyTmp['linked_leads_id'] = array($leadID);

										foreach($arCustomFields['companies'] as $key => $arProp)
										{
											if($arFields[$key.'_companies'])
											{
												$arFieldsCompanyTmp['custom_fields'][] = array(
													'id' => $key,
													'values' => array(
														array(
															'value' => $arFields[$key.'_companies']
														)
													)
												);
											}
											elseif(isset($arProp['ENUMS']) && $arProp['ENUMS'])
											{
												foreach($arProp['ENUMS'] as $key2 => $value)
												{
													if($arFields[$key.'_'.$key2.'_companies'])
													{
														$arFieldsCompanyTmp['custom_fields'][] = array(
															'id' => $key,
															'values' => array(
																array(
																	'value' => $arFields[$key.'_'.$key2.'_companies'],
																	'enum' => $value
																)
															)
														);
													}
												}
											}
										}
										$arFieldsCompany = array(
											'request' => array(
												'contacts' => array(
													'add' => array(
														$arFieldsCompanyTmp
													)
												)
											)
										);

										$resultCompany = \Aspro\Functions\CAsproAllcorp2CRM::query($url, \Aspro\Functions\CAsproAllcorp2CRM::$arCrmMethods[$crm]["CREATE_COMPANY"], $arFieldsCompany, $CURL, $DECODE);
										$resultCompany = json_decode($resultCompany, true);

										if(isset($resultCompany['response']['contacts']['add'][0]['id']))
											$company_id = $resultCompany['response']['contacts']['add'][0]['id'];

										//log to file crm response
										if(Option::get(self::MODULE_ID, 'USE_LOG_'.$crm, 'N', $SITE_ID) == 'Y')
										{
											\Aspro\Functions\CAsproAllcorp2::set_log('crm', $smCrmName.'_create_company_response', $resultCompany);
										}

										unset($arFieldsCompany);
										unset($resultCompany);
									}

									//add contact
									$arFieldsContactTmp = self::prepareArray($arFields, array('name', 'tags'), '_contacts');
									$arFieldsContactTmp['linked_leads_id'] = array($leadID);

									if($company_id)
										$arFieldsContactTmp['linked_company_id'] = $company_id;

									if($arCustomFields && $arCustomFields['contacts'])
									{
										foreach($arCustomFields['contacts'] as $key => $arProp)
										{
											if($arFields[$key.'_contacts'])
											{
												$arFieldsContactTmp['custom_fields'][] = array(
													'id' => $key,
													'values' => array(
														array(
															'value' => $arFields[$key.'_contacts']
														)
													)
												);
											}
											elseif(isset($arProp['ENUMS']) && $arProp['ENUMS'])
											{
												foreach($arProp['ENUMS'] as $key2 => $value)
												{
													if($arFields[$key.'_'.$key2.'_contacts'])
													{
														$arFieldsContactTmp['custom_fields'][] = array(
															'id' => $key,
															'values' => array(
																array(
																	'value' => $arFields[$key.'_'.$key2.'_contacts'],
																	'enum' => $value
																)
															)
														);
													}
												}
											}
										}
									}

									$arFieldsContact = array(
										'request' => array(
											'contacts' => array(
												'add' => array(
													$arFieldsContactTmp
												)
											)
										)
									);
									
									$resultContact = \Aspro\Functions\CAsproAllcorp2CRM::query($url, \Aspro\Functions\CAsproAllcorp2CRM::$arCrmMethods['AMO_CRM']['CREATE_CONTACT'], $arFieldsContact, $CURL, $DECODE);
									
									//log to file crm response
									if(Option::get(self::MODULE_ID, 'USE_LOG_'.$crm, 'N', $SITE_ID) == 'Y')
									{
										\Aspro\Functions\CAsproAllcorp2::set_log('crm', $smCrmName.'_create_contact_response', json_decode($resultContact, true));
									}
									
									unset($arFieldsContact);
									unset($resultContact);

								}

								if((isset($arCrmResult['response']['id']) && $arCrmResult['response']['id']) || (isset($arCrmResult['response']['leads']) && $leadID))
								{
									$arFormResultOption = unserialize(Option::get(self::MODULE_ID, 'CRM_SEND_FORM_'.$RESULT_ID, '', $SITE_ID));
									if(!isset($arFormResultOption['FLOWLU']) && (isset($arCrmResult['response']['id']) && $arCrmResult['response']['id']))
										$arFormResultOption['FLOWLU'] = $arCrmResult['response']['id'];
									if(!isset($arFormResultOption['AMO_CRM']) && (isset($arCrmResult['response']['leads']) && $leadID))
										$arFormResultOption['AMO_CRM'] = $leadID;
									Option::set(self::MODULE_ID, 'CRM_SEND_FORM_'.$RESULT_ID, serialize($arFormResultOption), $SITE_ID);
								}
							}

							//log to file crm response
							if(Option::get(self::MODULE_ID, 'USE_LOG_'.$crm, 'N', $SITE_ID) == 'Y')
							{
								\Aspro\Functions\CAsproAllcorp2::set_log('crm', $smCrmName.'_create_lead_response', $arCrmResult);
							}
						}
					}
				}
			}
			return $result;
		}

		public static function showPrice($arItem = array(), $arParams = array(), $bOrderViewBasket = false, $bWideBlock = false, $bShowSchema = true){?>
			<?if(strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'])):?>
				<div class="price<?=($bOrderViewBasket ? '  inline' : '')?>">
					<div class="price_new">
						<span class="price_val" <?if($bShowSchema):?>itemprop="price" content="<?=($arParams['SHOW_PRICE'] ? $arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'] : $arItem['PROPERTIES']['FILTER_PRICE']['VALUE']);?>"<?endif;?>><?=\CAllcorp2::FormatPriceShema($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'], ($arParams['SHOW_PRICE'] ? false : $bShowSchema))?></span>
					</div>
					<?if($arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']):?>
						<div class="price_old">
							<?if($bWideBlock):?>
								<?=GetMessage('PRICE_DISCOUNT')?>
							<?endif;?>
							<span class="price_val"><?=$arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE']?></span>
						</div>
					<?endif;?>
				</div>
			<?endif;?>
		<?}

		public static function showBasketButton($arItem = array(), $arParams, $bOrderButton = false, $bOrderViewBasket = false, $basketURL = '', $bDetail = false){?>
			<div class="footer-button">
				<?if(!$basketURL)
					$basketURL = SITE_DIR.'cart/';?>
				<?if($bDetail):?>
					<?if($bOrderViewBasket):?>
						<div class="buy_block lg clearfix">
							<div class="counter pull-left">
								<div class="wrap">
									<span class="minus ctrl bgtransition"></span>
									<div class="input"><input type="text" value="1" class="count" /></div>
									<span class="plus ctrl bgtransition"></span>
								</div>
							</div>
							<div class="buttons pull-right">
								<span class="btn btn-default pull-right to_cart animate-load" data-quantity="1"><span><?=GetMessage('BUTTON_TO_CART')?></span></span>
								<a href="<?=$basketURL?>" class="btn btn-default pull-right in_cart"><span><?=GetMessage('BUTTON_IN_CART')?></span></a>
							</div>
						</div>
					<?endif;?>
					<div class="wrapper-block-btn order<?=($bOrderViewBasket ? ' basketTrue' : '')?>">
						<?if(!$bOrderViewBasket):?>
							<div class="wrapper">
								<span class="btn btn-default btn-lg animate-load" data-event="jqm" data-param-id="<?=\CAllcorp2::getFormID("aspro_allcorp2_order_product");?>" data-name="order_product" data-autoload-product="<?=\CAllcorp2::formatJsName($arItem['NAME'])?>"><?=(strlen($arParams['S_ORDER_SERVISE']) ? $arParams['S_ORDER_SERVISE'] : GetMessage('S_ORDER_SERVISE'))?></span>
							</div>
						<?endif;?>
						<?// ask question?>
						<?if($arItem['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
							<div class="wrapper">
								<span class="btn btn-default btn-lg btn-transparent-bg wide-block animate-load" data-event="jqm" data-param-id="<?=\CAllcorp2::getFormID("aspro_allcorp2_question");?>" data-autoload-need_product="<?=\CAllcorp2::formatJsName($arItem['NAME'])?>" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span></span>
							</div>
						<?endif;?>
					</div>
				<?else:?>
					<?if($bOrderButton):?>
						<?// element order button?>
						<?if($bOrderButton && !$bOrderViewBasket):?>
							<span>
							<span class="btn btn-default animate-load" <?=(strlen(($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']) && strlen($arItem['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE'])) ? 'style="margin-top:16px;"' : '')?> data-event="jqm" data-param-id="<?=\CAllcorp2::getFormID('aspro_allcorp2_order_product');?>" data-autoload-product="<?=\CAllcorp2::formatJsName($arItem["NAME"]);?>" data-name="order_product"><?=(strlen($arParams['S_ORDER_PRODUCT']) ? $arParams['S_ORDER_PRODUCT'] : GetMessage('TO_ORDER'))?></span>
							</span>
						<?endif;?>
						<?// element buy block?>
						<?if($bOrderViewBasket && $bOrderButton):?>
							<div class="buy_block clearfix">
								<div class="counter">
									<div class="wrap">
										<span class="minus ctrl bgtransition"></span>
										<div class="input"><input type="text" value="1" class="count" maxlength="20" /></div>
										<span class="plus ctrl bgtransition"></span>
									</div>
								</div>
								<div class="buttons">
									<span class="btn btn-default to_cart animate-load" data-quantity="1"><span><?=GetMessage('BUTTON_TO_CART')?></span></span>
									<a href="<?=$basketURL;?>" class="btn btn-default in_cart"><span><?=GetMessage('BUTTON_IN_CART')?></span></a>
								</div>
							</div>
						<?endif;?>
					<?else:?>
						<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn btn-default"><?=(strlen($arParams['S_MORE_PRODUCT']) ? $arParams['S_MORE_PRODUCT'] : GetMessage('TO_ALL'))?></a>
					<?endif;?>
				<?endif;?>
			</div>
		<?}
	}

}?>