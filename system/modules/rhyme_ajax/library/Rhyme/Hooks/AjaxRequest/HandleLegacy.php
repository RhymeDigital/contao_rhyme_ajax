<?php

/**
 * Ajax handler for Contao Open Source CMS
 *
 * Copyright (c) 2015 Rhyme.Digital
 *
 * @package RhymeAjax
 * @link    http://rhyme.digital
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Rhyme\Hooks\AjaxRequest;

use Rhyme\AjaxInput;
use Haste\Http\Response\HtmlResponse;



/**
 * Class HandleLegacy
 *
 * Handles legacy ajax.php requests
 * @copyright  Rhyme.Digital 2015
 * @author     Blair Winans <blair@rhyme.digital>
 * @author     Adam Fisher <adam@rhyme.digital>
 * @package    RhymeAjax
 */
class HandleLegacy extends \Controller
{
    /**
     * Load a module if we need to
     * @return string
     */
    public function run()
    {
        $varResponse = false;
        
        //Legacy Frontend Module Requests
        if (AjaxInput::get('action')=='fmd' && AjaxInput::get('id') > 0)
        {
            //Restore Input vars
            \System::log('AjaxInput 1 "'.AjaxInput::get('id'), __METHOD__, TL_ERROR);
            AjaxInput::restore();
            \System::log('AjaxInput 2 "'.AjaxInput::get('id'), __METHOD__, TL_ERROR);
            $strBuffer = static::getFrontendModuleAjax(AjaxInput::get('id'));
            $objResponse = new HtmlResponse($strBuffer);
            $objResponse->send();
        }
        
        //Legacy Content Element Requests
        if (AjaxInput::get('action')=='cte' && AjaxInput::get('id') > 0)
        {
            //Restore Input vars
            AjaxInput::restore();
            $strBuffer = static::getContentElementAjax(AjaxInput::get('id'));
            $objResponse = new HtmlResponse($strBuffer);
            $objResponse->send();
        }
        
        //Legacy Form Field Requests
        if (AjaxInput::get('action')=='ffl' && AjaxInput::get('id') > 0)
        {
            //Restore Input vars
            AjaxInput::restore();
            $strBuffer = static::getFormFieldAjax(AjaxInput::get('id'));
            $objResponse = new HtmlResponse($strBuffer);
            $objResponse->send();
        }
        
    }


    /**
	 * Generate a front end module and return it as string
	 *
	 * @param mixed  $intId     A module ID or a Model object
	 * @param string $strColumn The name of the column
	 *
	 * @return string The module HTML markup
	 */
	protected static function getFrontendModuleAjax($intId, $strColumn='main')
	{
    	
		if (!is_object($intId) && !strlen($intId))
		{
			return '';
		}

		if (is_object($intId))
		{
			$objRow = $intId;
		}
		else
		{
			$objRow = \ModuleModel::findByPk($intId);

			if ($objRow === null)
			{
				return '';
			}
		}
		
		// Check the visibility (see #6311)
		if (!\Controller::isVisibleElement($objRow))
		{
			return '';
		}

		$strClass = \Module::findClass($objRow->type);
		
		
		// Return if the class does not exist
		if (!class_exists($strClass))
		{
			\System::log('Module class "'.$strClass.'" (module "'.$objRow->type.'") does not exist', __METHOD__, TL_ERROR);
			return '';
		}

		$objRow->typePrefix = 'mod_';
		$objModule = new $strClass($objRow, $strColumn);
		
		$strBuffer = AjaxInput::get('g') == '1' ? $objModule->generate() : $objModule->generateAjax();
        
		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getFrontendModule']) && is_array($GLOBALS['TL_HOOKS']['getFrontendModule']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getFrontendModule'] as $callback)
			{
				$strBuffer = \System::importStatic($callback[0])->$callback[1]($objRow, $strBuffer, $objModule);
			}
		}

		return $strBuffer;
	}


    /**
	 * Generate a content element and return it as string
	 *
	 * @param mixed  $intId     A content element ID or a Model object
	 * @param string $strColumn The column the element is in
	 *
	 * @return string The content element HTML markup
	 */
	protected static function getContentElementAjax($intId, $strColumn='main')
	{
		if (is_object($intId))
		{
			$objRow = $intId;
		}
		else
		{
			if (!strlen($intId) || $intId < 1)
			{
				return '';
			}

			$objRow = \ContentModel::findByPk($intId);

			if ($objRow === null)
			{
				return '';
			}
		}

		// Check the visibility (see #6311)
		if (!static::isVisibleElement($objRow))
		{
			return '';
		}

		$strClass = \ContentElement::findClass($objRow->type);

		// Return if the class does not exist
		if (!class_exists($strClass))
		{
			\System::log('Content element class "'.$strClass.'" (content element "'.$objRow->type.'") does not exist', __METHOD__, TL_ERROR);
			return '';
		}

		$objRow->typePrefix = 'ce_';
		$objElement = new $strClass($objRow, $strColumn);
		$strBuffer = AjaxInput::get('g') == '1' ? $objElement->generate() : $objElement->generateAjax();

		// HOOK: add custom logic
		if (isset($GLOBALS['TL_HOOKS']['getContentElement']) && is_array($GLOBALS['TL_HOOKS']['getContentElement']))
		{
			foreach ($GLOBALS['TL_HOOKS']['getContentElement'] as $callback)
			{
				$strBuffer = static::importStatic($callback[0])->$callback[1]($objRow, $strBuffer, $objElement);
			}
		}

		return $strBuffer;
	}
	

    /**
	 * Generate a form field
	 * @param  int
	 * @return string
	 */
	protected static function getFormFieldAjax($strId)
	{
		if (!strlen($strId) || !isset($_SESSION['AJAX-FFL'][$strId]))
		{
			return '';
		}
		
		$arrConfig = $_SESSION['AJAX-FFL'][$strId];
		
		$strClass = strlen($GLOBALS['TL_FFL'][$arrConfig['type']]) ? $GLOBALS['TL_FFL'][$arrConfig['type']] : $GLOBALS['BE_FFL'][$arrConfig['type']];
		
		if (!$this->classFileExists($strClass))
		{
			\System::log('Form field class "'.$strClass.'" (form field "'.$arrConfig['type'].'") does not exist', 'Ajax getFormField()', TL_ERROR);
			return '';
		}

		$objField = new $strClass($arrConfig);

		return $objField->generateAjax();
	}

}