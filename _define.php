<?php

/**
 * @brief authorMode, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author xave, Pierre Van Glabeke, Franck Paul
 *
 * @copyright GPL-2.0
 */
$this->registerModule(
    'Author Mode',
    'Post entries per author + author desc handling',
    'xave, Pierre Van Glabeke, Franck Paul',
    '8.2',
    [
        'date'     => '2025-09-12T18:43:37+0200',
        'requires' => [
            ['core', '2.36'],
            ['TemplateHelper'],
        ],
        'permissions' => 'My',
        'type'        => 'plugin',

        'details'    => 'https://open-time.net/?q=authorMode',
        'support'    => 'https://github.com/franck-paul/authorMode',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/authorMode/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
