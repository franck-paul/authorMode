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
declare(strict_types=1);

if (isset($this) && is_object($this) && method_exists($this, 'registerModule') && isset($this->id) && is_string($this->id)) {
    $this->registerModule(
        'Author Mode',
        'Post entries per author + author desc handling',
        'xave, Pierre Van Glabeke, Franck Paul',
        '9.0',
        [
            'date'     => '2026-08-03T09:45:04+0200',
            'requires' => [
                ['core', '2.39'],
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
}
