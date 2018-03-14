<?php
defined('TYPO3_MODE') or die();


if (TYPO3_MODE === 'BE' && !(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_INSTALL)) {

    // Default User TSConfig to be added in any case.
    TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('
		# Hide the module in the BE.
		options.hideModules.web := addToList(WorkspacesWorkspaces)
	');
}