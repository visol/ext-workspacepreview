<?php
namespace Visol\Workspacepreview\Xclass;

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

/**
 * Stages service
 *
 * @author Workspaces Team (http://forge.typo3.org/projects/show/typo3v4-workspaces)
 */
class StagesService extends \TYPO3\CMS\Workspaces\Service\StagesService {
	/**
	 * Path to the locallang file
	 *
	 * @var string
	 */
	private $pathToLocallang = 'LLL:EXT:workspaces/Resources/Private/Language/locallang.xlf';

	/**
	 * Building an array with all stage ids and titles related to the given workspace
	 *
	 * @return array id and title of the stages
	 */
	public function getStagesForWS() {
		$stages = array();
		if (isset($this->workspaceStageCache[$this->getWorkspaceId()])) {
			$stages = $this->workspaceStageCache[$this->getWorkspaceId()];
		} else {
			$stages[] = array(
				'uid' => self::STAGE_EDIT_ID,
				'title' => $GLOBALS['LANG']->sL(($this->pathToLocallang . ':actionSendToStage')) . ' "'
					. $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_mod_user_ws.xlf:stage_editing') . '"'
			);
			$workspaceRec = BackendUtility::getRecord('sys_workspace', $this->getWorkspaceId());
			if ($workspaceRec['custom_stages'] > 0) {
				// Get all stage records for this workspace
				$where = 'parentid=' . $this->getWorkspaceId() . ' AND parenttable='
					. $GLOBALS['TYPO3_DB']->fullQuoteStr('sys_workspace', self::TABLE_STAGE) . ' AND deleted=0';
				$workspaceStageRecs = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', self::TABLE_STAGE, $where, '', 'sorting', '', 'uid');
				foreach ($workspaceStageRecs as $stage) {
					$stage['title'] = $GLOBALS['LANG']->sL(($this->pathToLocallang . ':actionSendToStage')) . ' "' . $stage['title'] . '"';
					$stages[] = $stage;
				}
			}
//			$stages[] = array(
//				'uid' => self::STAGE_PUBLISH_ID,
//				'title' => $GLOBALS['LANG']->sL(($this->pathToLocallang . ':actionSendToStage')) . ' "'
//					. $GLOBALS['LANG']->sL('LLL:EXT:workspaces/Resources/Private/Language/locallang_mod.xlf:stage_ready_to_publish') . '"'
//			);
			$stages[] = array(
				'uid' => self::STAGE_PUBLISH_EXECUTE_ID,
				'title' => $GLOBALS['LANG']->sL($this->pathToLocallang . ':publish_execute_action_option')
			);
			$this->workspaceStageCache[$this->getWorkspaceId()] = $stages;
		}
		return $stages;
	}
}
