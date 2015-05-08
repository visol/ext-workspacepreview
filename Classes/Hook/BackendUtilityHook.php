<?php
namespace Visol\Workspacepreview\Hook;

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

/**
 * Befunc service
 *
 * @author Workspaces Team (http://forge.typo3.org/projects/show/typo3v4-workspaces)
 */
class BackendUtilityHook implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * Gets a singleton instance of this object.
	 *
	 * @return \TYPO3\CMS\Workspaces\Hook\BackendUtilityHook
	 */
	static public function getInstance() {
		return GeneralUtility::makeInstance(__CLASS__);
	}

	/**
	 * Hooks into the \TYPO3\CMS\Backend\Utility\BackendUtility::viewOnClick and redirects to the workspace preview
	 * only if we're in a workspace and if the frontend-preview is disabled.
	 *
	 * @param int $pageUid
	 * @param string $backPath
	 * @param array $rootLine
	 * @param string $anchorSection
	 * @param string $viewScript
	 * @param string $additionalGetVars
	 * @param bool $switchFocus
	 * @return void
	 */
	public function preProcess(&$pageUid, $backPath, $rootLine, $anchorSection, &$viewScript, $additionalGetVars, $switchFocus) {
		if ($GLOBALS['BE_USER']->workspace !== 0) {
			$viewScript = $this->getWorkspacePreviewService()->generateWorkspaceSplittedPreviewLink($pageUid);
		}
	}

	/**
	 * Gets an instance of the workspaces service.
	 *
	 * @return \Visol\Workspacepreview\Service\WorkspacePreviewService
	 */
	protected function getWorkspacePreviewService() {
		return GeneralUtility::makeInstance('Visol\\Workspacepreview\\Service\\WorkspacePreviewService');
	}

}