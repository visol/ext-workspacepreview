<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Simple Workspace Preview',
	'description' => 'Light-weight Workspace Preview module to establish a self-publishing workflow',
	'category' => 'be',
	'shy' => 0,
	'version' => '2.0.0',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => '',
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Lorenz Ulrich',
	'author_email' => 'lorenz.ulrich@visol.ch',
	'author_company' => 'visol digitale Dienstleistungen GmbH',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '8.7.0-8.7.999',
                    'workspaces' => '8.7.0-8.7.999',
                ],
            'conflicts' =>
                [],
            'suggests' =>
                [],
        ],
	'suggests' => [
	],
);

?>
