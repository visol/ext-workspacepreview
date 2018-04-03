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

/**
 * Module: 'TYPO3/CMS/Workspacepreview/Toolbar/WorkspacesMenu
 * toolbar menu for the workspaces functionality to switch between the workspaces
 * and jump to the workspaces module
 */

define([
  'jquery',
  'TYPO3/CMS/Backend/Viewport',
  'TYPO3/CMS/Workspaces/Toolbar/WorkspacesMenu'
], function($, Viewport, WorkspacesMenu) {
  'use strict';

  /**
   *
   * @type {{options: {containerSelector: string, menuItemSelector: string, activeMenuItemSelector: string, toolbarItemSelector: string, workspaceBodyClass: string, workspacesTitleInToolbarClass: string, workspaceModuleLinkSelector: string}}}
   * @exports TYPO3/CMS/Workspaces/Toolbar/WorkspacesMenu
   */
  var WorkspacesMenuOverride = {
    options: {
      containerSelector: '#visol-workspacepreview-backend-toolbaritems-workspaceselectortoolbaritem',
    }
  };

  /**
   * registers event listeners
   */
  WorkspacesMenu.initializeEvents = function() {

    // link to the module
    $(WorkspacesMenuOverride.options.containerSelector).on('click', WorkspacesMenu.options.workspaceModuleLinkSelector, function(evt) {
      evt.preventDefault();
      top.goToModule($(this).data('module'));
    });

    // observe all clicks on workspace links in the menu
    $(WorkspacesMenuOverride.options.containerSelector).on('click', WorkspacesMenu.options.menuItemSelector, function(evt) {
      console.log('click');
      evt.preventDefault();
      WorkspacesMenu.switchWorkspace($(this).data('workspaceid'));
    });
  };

  return WorkspacesMenuOverride;
});
