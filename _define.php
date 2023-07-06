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
    'authorMode',
    'Post entries per author + author desc handling',
    'xave, Pierre Van Glabeke, Franck Paul',
    '3.4',
    [
        'requires'    => [['core', '2.26']],
        'permissions' => dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_USAGE,
            dcAuth::PERMISSION_CONTENT_ADMIN,
        ]),
        'type' => 'plugin',

        'details'    => 'https://open-time.net/?q=authorMode',
        'support'    => 'https://github.com/franck-paul/authorMode',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/authorMode/master/dcstore.xml',
    ]
);
