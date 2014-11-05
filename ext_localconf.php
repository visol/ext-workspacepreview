<?php
defined('TYPO3_MODE') or die();

//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['workspaces'] = 'TYPO3\\CMS\\Workspaces\\Hook\\DataHandlerHook';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['viewOnClickClass']['workspaces'] = 'Visol\\Workspacepreview\\Hook\\BackendUtilityHook';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['hook_eofe']['workspaces'] = 'Visol\\Workspacepreview\\Hook\\TypoScriptFrontendControllerHook->hook_eofe';
//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/alt_doc.php']['makeEditForm_accessCheck']['workspaces'] = 'TYPO3\\CMS\\Workspaces\\Hook\\BackendUtilityHook->makeEditForm_accessCheck';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\CMS\Workspaces\Service\StagesService'] =
	array('className' => 'Visol\Workspacepreview\Xclass\StagesService');