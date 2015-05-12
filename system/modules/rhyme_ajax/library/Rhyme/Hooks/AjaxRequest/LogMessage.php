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

namespace Rhyme\Hooks\AjaxRequest;

use Rhyme\AjaxInput;
use Haste\Http\Response\HtmlResponse;



/**
 * Class LogMessage
 *
 * Log a message
 * @copyright  Rhyme Digital 2015
 * @author     Blair Winans <blair@rhyme.digital>
 * @author     Adam Fisher <adam@rhyme.digital>
 * @package    RhymeAjax
 */
class LogMessage extends \Controller
{
    /**
     * Log a message in Contao
     * @return void
     */
    public function run()
    {
        if (AjaxInput::get('action')=='logMessage' && AjaxInput::get('logDetails'))
        {
			\System::log(AjaxInput::get('logDetails'), AjaxInput::get('logMethod') ?: __METHOD__, AjaxInput::get('logCategory') ?: TL_GENERAL);
            $objResponse = new HtmlResponse(1);
            $objResponse->send();
        }
    }

}