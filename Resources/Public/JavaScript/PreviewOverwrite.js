/*
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
 * RequireJS module for workspace preview
 */
define([
  'jquery',
  'TYPO3/CMS/Workspaces/Workspaces',
  'TYPO3/CMS/Workspaces/Preview',
], function($, Workspaces, Preview) {
  'use strict';

  var PreviewOverwrite = {
    identifiers: {
      sendToPublishStageAction: '[data-action="send-to-publish-stage"]',
      copyToClipboard: '[data-action="copy-to-clipboard"]',
    }
  };

  /**
   * Initializes the preview module
   */
  PreviewOverwrite.initialize = function() {
      PreviewOverwrite.registerEvents();
  };

  /**
   * Registers the events
   */
  PreviewOverwrite.registerEvents = function() {
    $(document)
      .on('click', PreviewOverwrite.identifiers.sendToPublishStageAction, PreviewOverwrite.renderSendPageToPublishStageWindow)
      .on('click', PreviewOverwrite.identifiers.copyToClipboard, PreviewOverwrite.copyToClipboard)
    ;
  };


  /**
   * Copy the content of the current element to clipboard
   *
   * @private
   */
  PreviewOverwrite.copyToClipboard = function() {
    var $me = $(this);

    $me.select();

    try {
        var successful = document.execCommand('copy');
    } catch (err) {
        console.log('Oops, unable to copy');
    }
  };

    /**
     * Renders the "send page to stage" window
     *
     * @private
     */
    PreviewOverwrite.renderSendPageToPublishStageWindow = function() {
        var $me = $(this),
            actionName = 'sendPageToPublishStage';

        Workspaces.sendRemoteRequest(
            Workspaces.generateRemoteActionsPayload(actionName, [TYPO3.settings.Workspaces.id])
        ).done(function(response) {
            var $modal = Workspaces.renderSendToStageWindow(response);
            $modal.on('button.clicked', function(e) {
                if (e.target.name === 'ok') {
                    var $form = $(e.currentTarget).find('form'),
                        serializedForm = $form.serializeObject();

                    serializedForm.affects = response[0].result.affects;
                    serializedForm.stageId = $me.data('stageId');

                    Workspaces.sendRemoteRequest([
                        Workspaces.generateRemoteActionsPayload('sentCollectionToStage', [serializedForm]),
                        Workspaces.generateRemoteActionsPayload('updateStageChangeButtons', [TYPO3.settings.Workspaces.id])
                    ]).done(function(response) {
                        $modal.modal('hide');

                        Preview.renderStageButtons(response[1].result);
                    });
                }
            });
        });
    };

  /**
   * Serialize a form to a JavaScript object
   *
   * @see http://stackoverflow.com/a/1186309/4828813
   * @return {Object}
   */
  $.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
      if (typeof o[this.name] !== 'undefined') {
        if (!o[this.name].push) {
          o[this.name] = [o[this.name]];
        }
        o[this.name].push(this.value || '');
      } else {
        o[this.name] = this.value || '';
      }
    });
    return o;
  };

  $(document).ready(function() {
      PreviewOverwrite.initialize();
  });
});