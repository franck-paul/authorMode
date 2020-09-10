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

if (!defined('DC_RC_PATH')) {return;}

$this->registerModule(
    "authorMode",                                     // Name
    "Post entries per author + author desc handling", // Description
    "xave, Pierre Van Glabeke, Franck Paul",          // Author
    '1.10',                                           // Version
    [
        'requires'    => [['core', '2.16']],                                     // Dependencies
        'permissions' => 'usage,contentadmin',                                   // Permissions
        'type'        => 'plugin',                                               // Type
        'details'     => 'https://plugins.dotaddict.org/dc2/details/authorMode', // Details URL
        'support'     => 'https://github.com/franck-paul/authorMode'            // Support URL
    ]
);
