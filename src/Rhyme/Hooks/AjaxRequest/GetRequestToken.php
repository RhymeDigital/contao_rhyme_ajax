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
use Symfony\Component\HttpFoundation\Response;



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
     * Get a request token
     * @return void
     */
    public function run()
    {
        if (AjaxInput::post('action')=='getRequestToken')
        {
            $objResponse = new Response(\RequestToken::get());
            $objResponse->send();
        }
    }

}