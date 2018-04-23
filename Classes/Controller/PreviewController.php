<?php
namespace Visol\Workspacepreview\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;


/**
 * Implements the preview controller of the workspace module.
 */
class PreviewController extends \TYPO3\CMS\Workspaces\Controller\PreviewController
{

    protected function initializeIndexAction()
    {
        $this->pageRenderer->loadRequireJsModule('TYPO3/CMS/Workspacepreview/PreviewOverwrite');
    }
}
