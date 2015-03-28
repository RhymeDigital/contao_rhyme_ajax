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
 * Class GetRequestToken
 *
 * Get a request token
 * @copyright  Rhyme Digital 2015
 * @author     Blair Winans <blair@rhyme.digital>
 * @author     Adam Fisher <adam@rhyme.digital>
 * @package    RhymeAjax
 */
class GetRequestToken extends \Controller
{
    /**
     * Load a module if we need to
     * @return string
     */
    public function run()
    {
        if (AjaxInput::post('action')=='getRequestToken')
        {
            $objResponse = new HtmlResponse(\RequestToken::get());
            $objResponse->send();
        }
    }

}