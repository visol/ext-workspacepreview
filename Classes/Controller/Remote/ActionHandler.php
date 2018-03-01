<?php
namespace Visol\Workspacepreview\Controller\Remote;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Workspaces\Domain\Record\WorkspaceRecord;
use TYPO3\CMS\Workspaces\Service\StagesService;
use TYPO3\CMS\Workspaces\Service\WorkspaceService;

/**
 * Class ActionHandler
 */
class ActionHandler extends \TYPO3\CMS\Workspaces\Controller\Remote\ActionHandler
{

    /**
     * @param int $id Current Page id to select Workspace items from.
     * @return array
     */
    public function sendPageToPublishStage($id)
    {
        $workspaceService = GeneralUtility::makeInstance(WorkspaceService::class);
        $workspaceItemsArray = $workspaceService->selectVersionsInWorkspace($this->stageService->getWorkspaceId(), ($filter = 1), ($stage = -99), $id, ($recursionLevel = 0), ($selectionType = 'tables_modify'));
        list($currentStage, $nextStage) = $this->getStageService()->getNextStageForElementCollection($workspaceItemsArray);

        $stageRecord = WorkspaceRecord::get($this->getCurrentWorkspace())->getStage(StagesService::STAGE_PUBLISH_EXECUTE_ID);

        $publishStage = [
            'uid' => $stageRecord->getUid(),
            'title' => $stageRecord->getTitle(),
        ];

        // get only the relevant items for processing
        $workspaceItemsArray = $workspaceService->selectVersionsInWorkspace($this->stageService->getWorkspaceId(), ($filter = 1), $currentStage['uid'], $id, ($recursionLevel = 0), ($selectionType = 'tables_modify'));
        $result = [
            'title' => 'Status message: Page send to next stage - ID: ' . $id . ' - Next stage title: ' . $publishStage['title'],
            'affects' => $workspaceItemsArray,
            'stageId' => $publishStage['uid'],
            'comments' => [
                'type' => 'textarea',
                'value' => '',
            ],
        ];
        return $result;
    }
}
