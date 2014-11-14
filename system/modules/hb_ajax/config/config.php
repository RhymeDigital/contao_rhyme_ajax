<?php

/**
 * Ajax handler for Contao Open Source CMS
 *
 * Copyright (c) 2014 HBAgency
 *
 * @package HBAjax
 * @link    http://www.hbagency.com
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['ajaxRequest'][]		= array('HBAgency\Hooks\AjaxRequest\GetRequestToken', 'run');
$GLOBALS['TL_HOOKS']['getPageLayout'][]		= array('HBAgency\Hooks\GetPageLayout\FrontendVars', 'run');
