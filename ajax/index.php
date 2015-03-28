<?php

/**
 * Ajax handler for Contao Open Source CMS
 *
 * Copyright (c) 2015 Rhyme Digital
 *
 * @package RhymeAjax
 * @link    http://rhyme.digital
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

namespace Contao;

use Rhyme\AjaxInput;

/**
 * Set the script name
 */
define('TL_SCRIPT', 'ajax/index.php');


/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require __DIR__ . '/../system/initialize.php';


/**
 * Class AjaxFrontend
 *
 * Main front end ajax controller.
 * @copyright  Rhyme Digital 2015
 * @author     Blair Winans <blair@rhyme.digital>
 * @author     Adam Fisher <adam@rhyme.digital>
 * @package    RhymeAjax
 */
class AjaxFrontend extends \PageRegular
{

	/**
	 * Initialize the object
	 */
	public function __construct()
	{
	    //Safely store the $_GET and $_POST vals in AjaxInput
	    AjaxInput::initialize();
	
		// Load the user object before calling the parent constructor
		$this->import('FrontendUser', 'User');
		parent::__construct();

		// Check whether a user is logged in
		define('BE_USER_LOGGED_IN', $this->getLoginStatus('BE_USER_AUTH'));
		define('FE_USER_LOGGED_IN', $this->getLoginStatus('FE_USER_AUTH'));

		// No back end user logged in
		if (!$_SESSION['DISABLE_CACHE'])
		{
			// Maintenance mode (see #4561 and #6353)
			if (Config::get('maintenanceMode'))
			{
				header('HTTP/1.1 503 Service Unavailable');
				die('This site is currently down for maintenance. Please come back later.');
			}
		}
	}


    /**
	 * Process the AJAX request, store POST vars in AJAXInput object, and run hooks
	 *
	 * @param	void
	 * @return	void
	 */
	public function run()
	{
	    $intPage = (int) AjaxInput::get('pageId');

		if (!$intPage)
		{
			$intPage = (int) AjaxInput::get('page');
		}

		if ($intPage > 0)
		{
			// Get the current page object
			global $objPage;
			$objPage = $this->getPageDetails($intPage);

			// Define the static URL constants
			define('TL_FILES_URL', ($objPage->staticFiles != '' && !$GLOBALS['TL_CONFIG']['debugMode']) ? $objPage->staticFiles . TL_PATH . '/' : '');
			define('TL_SCRIPT_URL', ($objPage->staticSystem != '' && !$GLOBALS['TL_CONFIG']['debugMode']) ? $objPage->staticSystem . TL_PATH . '/' : '');
			define('TL_PLUGINS_URL', ($objPage->staticPlugins != '' && !$GLOBALS['TL_CONFIG']['debugMode']) ? $objPage->staticPlugins . TL_PATH . '/' : '');

			// Get the page layout
			$objLayout = $this->getPageLayout($objPage);
			$objPage->template = strlen($objLayout->template) ? $objLayout->template : 'fe_page';
			$objPage->templateGroup = $objLayout->getRelated('pid')->templates;

			// Store the output format
			list($strFormat, $strVariant) = explode('_', $objLayout->doctype);
			$objPage->outputFormat = $strFormat;
			$objPage->outputVariant = $strVariant;

			// Use the global date format if none is set
			if ($objPage->dateFormat == '')
			{
				$objPage->dateFormat = $GLOBALS['TL_CONFIG']['dateFormat'];
			}
			if ($objPage->timeFormat == '')
			{
				$objPage->timeFormat = $GLOBALS['TL_CONFIG']['timeFormat'];
			}
			if ($objPage->datimFormat == '')
			{
				$objPage->datimFormat = $GLOBALS['TL_CONFIG']['datimFormat'];
			}

			// Set the admin e-mail address
			if ($objPage->adminEmail != '')
			{
				list($GLOBALS['TL_ADMIN_NAME'], $GLOBALS['TL_ADMIN_EMAIL']) = $this->splitFriendlyName($objPage->adminEmail);
			}
			else
			{
				list($GLOBALS['TL_ADMIN_NAME'], $GLOBALS['TL_ADMIN_EMAIL']) = $this->splitFriendlyName($GLOBALS['TL_CONFIG']['adminEmail']);
			}

			$GLOBALS['TL_LANGUAGE'] = $objPage->language;
		}

		$this->User->authenticate();

		// Set language from _GET
		if (strlen(AjaxInput::get('language')))
		{
			$GLOBALS['TL_LANGUAGE'] = AjaxInput::get('language');
		}

		unset($GLOBALS['TL_HOOKS']['outputFrontendTemplate']);
		unset($GLOBALS['TL_HOOKS']['parseFrontendTemplate']);

		\System::loadLanguageFile('default');	
	
		// HOOK: 
		if (isset($GLOBALS['TL_HOOKS']['ajaxRequest']) && is_array($GLOBALS['TL_HOOKS']['ajaxRequest']))
		{
			foreach ($GLOBALS['TL_HOOKS']['ajaxRequest'] as $callback)
			{
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]();
			}
		}
		
		// Generate a 412 error response if no output
		header('HTTP/1.1 412 Precondition Failed');
		die('Contao: Invalid AJAX call.');
	}


}


/**
 * Instantiate the controller
 */
$objAjaxFrontend = new AjaxFrontend();
$objAjaxFrontend->run();
