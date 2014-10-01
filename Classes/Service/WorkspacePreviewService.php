<?php
namespace Visol\Workspacepreview\Service;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Versioning\VersionState;

/**
 * Workspace service
 *
 * @author Workspaces Team (http://forge.typo3.org/projects/show/typo3v4-workspaces)
 */
class WorkspacePreviewService extends \TYPO3\CMS\Workspaces\Service\WorkspaceService {

	/**
	 * Generates a view link for a page.
	 *
	 * @static
	 * @param string $table Table to be used
	 * @param integer $uid Uid of the version(!) record
	 * @param array $liveRecord Optional live record data
	 * @param array $versionRecord Optional version record data
	 * @return string
	 */
	static public function viewSingleRecord($table, $uid, array $liveRecord = NULL, array $versionRecord = NULL) {
		$viewUrl = '';

		if ($table == 'pages') {
			$viewUrl = BackendUtility::viewOnClick(BackendUtility::getLiveVersionIdOfRecord('pages', $uid));
		} elseif ($table === 'pages_language_overlay' || $table === 'tt_content') {
			if ($liveRecord === NULL) {
				$liveRecord = BackendUtility::getLiveVersionOfRecord($table, $uid);
			}
			if ($versionRecord === NULL) {
				$versionRecord = BackendUtility::getRecord($table, $uid);
			}
			if (VersionState::cast($versionRecord['t3ver_state'])->equals(VersionState::MOVE_POINTER)) {
				$movePlaceholder = BackendUtility::getMovePlaceholder($table, $liveRecord['uid'], 'pid');
			}

			$previewPageId = (empty($movePlaceholder['pid']) ? $liveRecord['pid'] : $movePlaceholder['pid']);
			$additionalParameters = '&tx_workspacepreview_web_workspaceprewviewworkspacepreview[previewWS]=' . $versionRecord['t3ver_wsid'];

			$languageField = $GLOBALS['TCA'][$table]['ctrl']['languageField'];
			if ($versionRecord[$languageField] > 0) {
				$additionalParameters .= '&L=' . $versionRecord[$languageField];
			}

			$viewUrl = BackendUtility::viewOnClick($previewPageId, '', '', '', '', $additionalParameters);
		} else {
			if (isset($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['workspaces']['viewSingleRecord'])) {
				$_params = array(
					'table' => $table,
					'uid' => $uid,
					'record' => $liveRecord,
					'liveRecord' => $liveRecord,
					'versionRecord' => $versionRecord,
				);
				$_funcRef = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['workspaces']['viewSingleRecord'];
				$null = NULL;
				$viewUrl = GeneralUtility::callUserFunction($_funcRef, $_params, $null);
			}
		}

		return $viewUrl;
	}

	/**
	 * Generates a workspace splitted preview link.
	 *
	 * @param integer $uid The ID of the record to be linked
	 * @param boolean $addDomain Parameter to decide if domain should be added to the generated link, FALSE per default
	 * @return string the preview link without the trailing '/'
	 */
	public function generateWorkspaceSplittedPreviewLink($uid, $addDomain = FALSE) {
		// In case a $pageUid is submitted we need to make sure it points to a live-page
		if ($uid > 0) {
			$uid = $this->getLivePageUid($uid);
		}
		/** @var $uriBuilder \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder */
		$uriBuilder = $this->getObjectManager()->get('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
		// This seems to be very harsh to set this directly to "/typo3 but the viewOnClick also
		// has /index.php as fixed value here and dealing with the backPath is very error-prone
		// @todo make sure this would work in local extension installation too
		$backPath = '/' . TYPO3_mainDir;
		$redirect = $backPath . 'index.php?redirect_url=';
		// @todo this should maybe be changed so that the extbase URI Builder can deal with module names directly
		$originalM = GeneralUtility::_GET('M');
		GeneralUtility::_GETset('web_WorkspacepreviewWorkspacepreview', 'M');
		$viewScript = $backPath . $uriBuilder->uriFor('index', array(), 'Preview', 'workspacepreview', 'web_workspacepreviewworkspacepreview') . '&id=';
		GeneralUtility::_GETset($originalM, 'M');
		if ($addDomain === TRUE) {
			return BackendUtility::getViewDomain($uid) . $redirect . urlencode($viewScript) . $uid;
		} else {
			return $viewScript;
		}
	}

}
