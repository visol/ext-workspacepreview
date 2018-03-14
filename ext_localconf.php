<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Workspaces\Backend\ToolbarItems\WorkspaceSelectorToolbarItem::class] = [
    'className' => Visol\Workspacepreview\Backend\ToolbarItems\WorkspaceSelectorToolbarItem::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Workspaces\Controller\Remote\ActionHandler::class] = [
    'className' => Visol\Workspacepreview\Controller\Remote\ActionHandler::class
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Workspaces\Controller\PreviewController::class] = [
    'className' => Visol\Workspacepreview\Controller\PreviewController::class
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['EXT:workspaces/Resources/Private/Language/locallang.xlf'][] = 'EXT:workspacepreview/Resources/Private/Language/locallang-override.xlf';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']['EXT:workspaces/Resources/Private/Language/locallang.xlf'][] = 'EXT:workspacepreview/Resources/Private/Language/de.locallang-override.xlf';