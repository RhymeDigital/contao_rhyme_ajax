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

namespace Rhyme\Hooks\GetPageLayout;


/**
 * Add a Request Token to the Frontend for global use
 *
 * @copyright  Rhyme Digital 2015
 * @author     Blair Winans <blair@rhyme.digital>
 * @author     Adam Fisher <adam@rhyme.digital>
 * @package    RhymeAjax
 */
class FrontendVars extends \Frontend
{

    /**
     * Add the token
     */
    public function run($objPage, &$objLayout, $objPageRegular)
    {
        if(TL_MODE=='FE')
        {
            $objTemplate = new \FrontendTemplate('rhyme_jsvars');
            array_insert($GLOBALS['TL_HEAD'], 0, array(
                $objTemplate->parse();
            ));
        }
    }
}
