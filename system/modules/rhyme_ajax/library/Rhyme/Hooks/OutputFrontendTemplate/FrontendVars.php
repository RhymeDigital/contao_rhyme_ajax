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

namespace Rhyme\Hooks\OutputFrontendTemplate;


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
    public function run($strBuffer, $strTemplate)
    {
        if(strpos($strTemplate, 'fe_') !== false)
        {
            $objTemplate = new \FrontendTemplate('rhyme_jsvars');
            $strBuffer = str_replace('</head>', $objTemplate->parse() . "\n" . '</head>', $strBuffer);
        }
        
        return $strBuffer;
    }
}
