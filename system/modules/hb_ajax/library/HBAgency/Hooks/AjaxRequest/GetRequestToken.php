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

namespace HBAgency\Hooks\AjaxRequest;

use HBAgency\AjaxInput;
use Haste\Http\Response\HtmlResponse;



/**
 * Class GetRequestToken
 *
 * Get a request token
 * @copyright  HBAgency 2014
 * @author     Blair Winans <bwinans@hbagency.com>
 * @author     Adam Fisher <afisher@hbagency.com>
 * @package    HBAjax
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