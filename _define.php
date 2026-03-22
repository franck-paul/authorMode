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
    '8.4.1',
    [
        'date'     => '2026-03-22T16:05:39+0100',
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
