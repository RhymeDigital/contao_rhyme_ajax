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


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['ajaxRequest'][]		= array('Rhyme\Hooks\AjaxRequest\GetRequestToken', 'run');
$GLOBALS['TL_HOOKS']['ajaxRequest'][]		= array('Rhyme\Hooks\AjaxRequest\HandleLegacy', 'run');
$GLOBALS['TL_HOOKS']['ajaxRequest'][]		= array('Rhyme\Hooks\AjaxRequest\LogMessage', 'run');
$GLOBALS['TL_HOOKS']['outputFrontendTemplate'][]		= array('Rhyme\Hooks\OutputFrontendTemplate\FrontendVars', 'run');
