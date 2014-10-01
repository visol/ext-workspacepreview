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

/**
 * Frontend hooks
 *
 * @author Workspaces Team (http://forge.typo3.org/projects/show/typo3v4-workspaces)
 */
class TypoScriptFrontendControllerHook {

	/**
	 * @param array $params
	 * @param \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $pObj
	 * @return mixed
	 */
	public function hook_eofe($params, $pObj) {
		if ($pObj->fePreview != 2) {
			return;
		}
		$previewParts = $GLOBALS['TSFE']->cObj->cObjGetSingle('FLUIDTEMPLATE', array(
			'file' => 'EXT:workspacepreview/Resources/Private/Templates/Preview/Preview.html',
			'variables.' => array(
				'backendDomain' => 'TEXT',
				'backendDomain.' => array('value' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY'))
			)
		));
		$GLOBALS['TSFE']->content = str_ireplace('</body>', $previewParts . '</body>', $GLOBALS['TSFE']->content);
	}

}
