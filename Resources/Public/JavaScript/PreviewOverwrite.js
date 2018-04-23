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
  'TYPO3/CMS/Backend/Severity',
  'TYPO3/CMS/Backend/Modal',
], function($, Workspaces, Severity, Modal) {
  'use strict';

  var PreviewOverwrite = {
    identifiers: {
      liveView: '#live-view',
      discardRedirectAction: '[data-action="discard-redirect"]',
      stageButtonsContainer: '.t3js-stage-buttons',
      sendToPublishStageAction: '[data-action="send-to-publish-stage"]',
      previewLinksButton: '.t3js-preview-link',
    },
    elements: {}
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
      .on('click', PreviewOverwrite.identifiers.discardRedirectAction, PreviewOverwrite.renderDiscardRedirectWindow)
      .on('click', PreviewOverwrite.identifiers.sendToPublishStageAction, PreviewOverwrite.renderSendPageToPublishStageWindow)
      .on('click', PreviewOverwrite.identifiers.copyToClipboard, PreviewOverwrite.copyToClipboard)
      .on('click', PreviewOverwrite.identifiers.previewLinksButton, PreviewOverwrite.generatePreviewLinks);
    ;


  };

  /**
   * Renders the staging buttons
   *
   * @param {String} buttons
   */
  PreviewOverwrite.renderStageButtons = function(buttons) {
    PreviewOverwrite.elements.$stageButtonsContainer = $(PreviewOverwrite.identifiers.stageButtonsContainer);
    PreviewOverwrite.elements.$stageButtonsContainer.html(buttons);
  };

  /**
   * Fetches and renders available preview links
   * Credits: EXT:workspaces/Resources/Public/JavaScript/Backend.js
   */
  PreviewOverwrite.generatePreviewLinks = function() {
    Workspaces.sendRemoteRequest(
      Workspaces.generateRemoteActionsPayload('generateWorkspacePreviewLinksForAllLanguages', [
        TYPO3.settings.Workspaces.id
      ])
    ).done(function(response) {
      var result = response[0].result,
        $list = $('<dl />');

      $.each(result, function(language, url) {
        $list.append(
          $('<dt />').text(language),
          $('<dd />').append(
            $('<a />', {href: url, target: '_blank'}).text(url)
          )
        );
      });

      Modal.show(
        TYPO3.lang['previewLink'],
        $list,
        Severity.info,
        [{
          text: TYPO3.lang['ok'],
          active: true,
          btnClass: 'btn-info',
          name: 'ok',
          trigger: function() {
            Modal.currentModal.trigger('modal-dismiss');
          }
        }],
        ['modal-inner-scroll']
      );
    });
  };








  /**
   * Renders the discard window
   *
   * @private
   */
  PreviewOverwrite.renderDiscardRedirectWindow = function() {
    var $modal = Modal.confirm(
      TYPO3.lang['window.discardAll.title'],
      TYPO3.lang['window.discardAll.message'],
      Severity.warning,
      [
        {
          text: TYPO3.lang['cancel'],
          active: true,
          btnClass: 'btn-default',
          name: 'cancel',
          trigger: function() {
            $modal.modal('hide');
          }
        }, {
        text: TYPO3.lang['ok'],
        btnClass: 'btn-warning',
        name: 'ok'
      }
      ]
    );
    $modal.on('button.clicked', function(e) {
      if (e.target.name === 'ok') {
        Workspaces.sendRemoteRequest([
          Workspaces.generateRemoteActionsPayload('discardStagesFromPage', [TYPO3.settings.Workspaces.id]),
          Workspaces.generateRemoteActionsPayload('updateStageChangeButtons', [TYPO3.settings.Workspaces.id])
        ]).done(function(response) {
          $modal.modal('hide');

          PreviewOverwrite.redirectToLiveUrl();
        });
      }
    });
  };

  /**
   * Renders the "send page to publish stage" window
   *
   * @private
   */
  PreviewOverwrite.renderSendPageToPublishStageWindow = function() {
    var $me = $(this),
      actionName = 'sendPageToPublishStage';

    Workspaces.sendRemoteRequest(
      Workspaces.generateRemoteActionsPayload(actionName, [TYPO3.settings.Workspaces.id])
    ).done(function(response) {
      var $modal = Modal.confirm(
        TYPO3.lang['window.publishAll.title'],
        TYPO3.lang['window.publishAll.message'],
        Severity.warning,
        [
          {
            text: TYPO3.lang['cancel'],
            active: true,
            btnClass: 'btn-default',
            name: 'cancel',
            trigger: function() {
              $modal.modal('hide');
            }
          }, {
          text: TYPO3.lang['label_doaction_publish'],
          btnClass: 'btn-warning',
          name: 'ok'
        }
        ]
      );

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

            PreviewOverwrite.redirectToLiveUrl();
          });
        }
      });
    });
  };

  /**
   * Determine and redirect to live URL
   *
   * @private
   */
  PreviewOverwrite.redirectToLiveUrl = function() {
    PreviewOverwrite.elements.$liveView = $(PreviewOverwrite.identifiers.liveView);

    var liveUrl = PreviewOverwrite.elements.$liveView.attr('src');

    if (liveUrl.indexOf('newPage') > -1) {
      // this page was not published before, so we must extract the page id and redirect
      var queryString = {};
      liveUrl.replace(
        new RegExp("([^?=&]+)(=([^&]*))?", "g"),
        function($0, $1, $2, $3) { queryString[$1] = $3; }
      );
      location.href = '/index.php?id=' + queryString.id;
    } else {
      // this page is already visible in the frontend, therefore it is safe to redirect
      location.href = liveUrl;
    }
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
