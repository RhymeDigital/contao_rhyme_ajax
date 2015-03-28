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

namespace HBAgency;


/**
 * Safely store $POST and $GET vars from ajax requests
 * so that they are ONLY used by AJAX-enabled modules
 *
 * @copyright  Rhyme Digital 2015
 * @author     Blair Winans <blair@rhyme.digital>
 * @author     Adam Fisher <adam@rhyme.digital>
 * @package    RhymeAjax
 */
class AjaxInput extends \Input
{
    
    /**
	 * Cache
	 * @var array
	 */
	protected static $arrCache = array();

	/**
	 * Clean the input
	 */
	public static function initialize()
	{
		//Parent class gets initialized first, 
		//so our only job here is to store the items in the cache
        \Input::resetCache();
        
		//$_GET values
		foreach($_GET as $key => $val)
		{
    		parent::get($key);
		}
		
		//$_POST values
		foreach($_POST as $key => $val)
		{
    		parent::post($key);
		}
		
		//Clear the $_GET and $_POST arrays
		unset($_GET);
		unset($_POST);
	}
	
	/**
	 * Return a $_GET variable
	 *
	 * @param string  $strKey            The variable name
	 * @param boolean $blnDecodeEntities If true, all entities will be decoded
	 * @param boolean $blnKeepUnused     If true, the parameter will not be marked as used (see #4277)
	 *
	 * @return mixed The cleaned variable value
	 */
	public static function get($strKey, $blnDecodeEntities=false, $blnKeepUnused=false)
	{
	    $strCacheKey = $blnDecodeEntities ? 'getDecoded' : 'getEncoded';
	    
	    return static::$arrCache[$strCacheKey][$strKey];
	}
	
	/**
	 * Return a $_GET variable
	 *
	 * @param string  $strKey            The variable name
	 * @param boolean $blnDecodeEntities If true, all entities will be decoded
	 * @param boolean $blnKeepUnused     If true, the parameter will not be marked as used (see #4277)
	 *
	 * @return mixed The cleaned variable value
	 */
	public static function post($strKey, $blnDecodeEntities=false, $blnKeepUnused=false)
	{
	    $strCacheKey = $blnDecodeEntities ? 'postDecoded' : 'postEncoded';
	    
	    return static::$arrCache[$strCacheKey][$strKey];
	}
	
	/**
	 * Restore the GET and POST vars to Contao's Input class
	 */
	public static function restore()
	{
    	if(isset(static::$arrCache['getEncoded']))
    	{
        	foreach(static::$arrCache['getEncoded'] as $strKey => $varValue)
        	{
                \Input::setGet($strKey, $varValue);
        	}
        }
    	
    	if(isset(static::$arrCache['postEncoded']))
    	{
        	foreach(static::$arrCache['postEncoded'] as $strKey => $varValue)
        	{
                \Input::setPost($strKey, $varValue);
        	}
        }
	}

	/**
	 * Reset the internal cache
	 */
	public static function resetCache()
	{
		//Do nothing here so we always keep the vars
	}

}
