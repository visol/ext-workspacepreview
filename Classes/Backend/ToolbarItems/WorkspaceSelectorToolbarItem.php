<?php
namespace Visol\Workspacepreview\Backend\ToolbarItems;

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

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class to render the workspace selector
 */
class WorkspaceSelectorToolbarItem extends \TYPO3\CMS\Workspaces\Backend\ToolbarItems\WorkspaceSelectorToolbarItem
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $pageRenderer = $this->getPageRenderer();
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Workspacepreview/Toolbar/WorkspacesMenuOverride');
    }

    /**
     * Get drop down
     *
     * @return string
     */
    public function getDropDown()
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);

        $topItem = null;
        $additionalItems = [];
        $backendUser = $this->getBackendUser();
        $view = $this->getFluidTemplateObject('DropDown.html');
        $activeWorkspace = (int)$backendUser->workspace;
        foreach ($this->availableWorkspaces as $workspaceId => $label) {
            $workspaceId = (int)$workspaceId;
            $item = [
                'isActive'    => $workspaceId === $activeWorkspace,
                'label'       => $label,
                'link'        => $uriBuilder->buildUriFromRoute('main', ['changeWorkspace' => $workspaceId]),
                'workspaceId' => $workspaceId
            ];
            if ($topItem === null) {
                $topItem = $item;
            } else {
                $additionalItems[] = $item;
            }
        }

        $view->assign('topItem', $topItem);
        $view->assign('additionalItems', $additionalItems);
        return $view->render();
    }
}
