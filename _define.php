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
    '6.1',
    [
        'date'     => '2003-08-13T13:42:00+0100',
        'requires' => [
            ['core', '2.34'],
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
