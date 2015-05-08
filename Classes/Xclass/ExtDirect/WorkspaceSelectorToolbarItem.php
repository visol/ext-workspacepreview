<?php
namespace Visol\Workspacepreview\Xclass\ExtDirect;

/**
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

class WorkspaceSelectorToolbarItem extends \TYPO3\CMS\Workspaces\ExtDirect\WorkspaceSelectorToolbarItem {

	/**
	 * Creates the selector for workspaces
	 *
	 * @return 	string		workspace selector as HTML select
	 */
	public function render() {
		$title = $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:toolbarItems.workspace', TRUE);
		$this->addJavascriptToBackend();

		$index = 0;
		/** @var \TYPO3\CMS\Workspaces\Service\WorkspaceService $wsService */
		$wsService = GeneralUtility::makeInstance('TYPO3\\CMS\\Workspaces\\Service\\WorkspaceService');
		$availableWorkspaces = $wsService->getAvailableWorkspaces();
		$activeWorkspace = (int)$GLOBALS['BE_USER']->workspace;
		$stateCheckedIcon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('status-status-checked');
		$stateUncheckedIcon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('empty-empty', array(
			'title' => $GLOBALS['LANG']->getLL('bookmark_inactive')
		));

		$workspaceSections = array(
			'top' => array(),
			'items' => array(),
		);

		foreach ($availableWorkspaces as $workspaceId => $label) {
			$workspaceId = (int)$workspaceId;
			$iconState = ($workspaceId === $activeWorkspace ? $stateCheckedIcon : $stateUncheckedIcon);
			$classValue = ($workspaceId === $activeWorkspace ? ' class="selected"' : '');
			$sectionName = ($index++ === 0 ? 'top' : 'items');
			$workspaceSections[$sectionName][] = '<li' . $classValue . '>' . '<a href="backend.php?changeWorkspace=' . $workspaceId . '" id="ws-' . $workspaceId . '" class="ws">' . $iconState . ' ' . htmlspecialchars($label) . '</a></li>';
		}

		if (count($workspaceSections['top']) > 0) {
			// Go to workspace module link
//			if ($GLOBALS['BE_USER']->check('modules', 'web_WorkspacesWorkspaces')) {
//				$workspaceSections['top'][] = '<li>' . '<a href="javascript:top.goToModule(\'web_WorkspacesWorkspaces\');" target="content" id="goToWsModule">' . $stateUncheckedIcon . ' ' . $GLOBALS['LANG']->getLL('bookmark_workspace', TRUE) . '</a></li>';
//			}
			$workspaceSections['top'][] = '<li class="divider"></li>';
		} else {
			$workspaceSections['top'][] = '<li>' . $stateUncheckedIcon . ' ' . $GLOBALS['LANG']->getLL('bookmark_noWSfound', TRUE) . '</li>';
		}


		$workspaceMenu = array(
			'<a href="#" class="toolbar-item">' . \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('apps-toolbar-menu-workspace', array('title' => $title)) . '</a>',
			'<div class="toolbar-item-menu" style="display: none">' ,
				'<ul class="top">',
					implode(LF, $workspaceSections['top']),
				'</ul>',
				'<ul class="items">',
					implode(LF, $workspaceSections['items']),
				'</ul>',
			'</div>'
		);

		return implode(LF, $workspaceMenu);
	}

}


if (!(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_AJAX)) {
	$GLOBALS['TYPO3backend']->addToolbarItem('workSpaceSelector', 'TYPO3\\CMS\\Workspaces\\ExtDirect\\WorkspaceSelectorToolbarItem');
}
