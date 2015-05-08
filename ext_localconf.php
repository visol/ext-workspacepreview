<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_befunc.php']['viewOnClickClass']['workspaces'] = 'Visol\\Workspacepreview\\Hook\\BackendUtilityHook';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['hook_eofe']['workspaces'] = 'Visol\\Workspacepreview\\Hook\\TypoScriptFrontendControllerHook->hook_eofe';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\CMS\Workspaces\Service\StagesService'] = array('className' => 'Visol\Workspacepreview\Xclass\StagesService');
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\CMS\Workspaces\ExtDirect\WorkspaceSelectorToolbarItem'] = array('className' => 'Visol\Workspacepreview\Xclass\ExtDirect\WorkspaceSelectorToolbarItem');

$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['EXT:workspaces/Resources/Private/Language/locallang.xlf'][] = 'EXT:workspacepreview/Resources/Private/Language/locallang-override.xlf';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['EXT:lang/locallang_mod_user_ws.xlf'][] = 'EXT:workspacepreview/Resources/Private/Language/locallang-override.xlf';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']['EXT:workspaces/Resources/Private/Language/locallang.xlf'][] = 'EXT:workspacepreview/Resources/Private/Language/de.locallang-override.xlf';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']['EXT:lang/locallang_mod_user_ws.xlf'][] = 'EXT:workspacepreview/Resources/Private/Language/de.locallang-override.xlf';
