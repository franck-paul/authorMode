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
if (!defined('DC_CONTEXT_ADMIN')) {
    exit;
}

$new_version = dcCore::app()->plugins->moduleInfo('authorMode', 'version');
$cur_version = dcCore::app()->getVersion('authorMode');
if (version_compare($cur_version, $new_version, '>=')) {
    return;
}

dcCore::app()->blog->settings->addNameSpace('authormode');
if ($cur_version === null) {
    dcCore::app()->blog->settings->authormode->put('authormode_active', false, 'boolean');
    dcCore::app()->blog->settings->authormode->put('authormode_url_author', 'author', 'string');
    dcCore::app()->blog->settings->authormode->put('authormode_url_authors', 'authors', 'string');
    dcCore::app()->blog->settings->authormode->put('authormode_default_alpha_order', true, 'boolean');
    dcCore::app()->blog->settings->authormode->put('authormode_default_posts_only', true, 'boolean');
} elseif (version_compare($cur_version, '1.1', '<=')) {
    dcCore::app()->blog->settings->authormode->put('authormode_default_alpha_order', true, 'boolean');
    dcCore::app()->blog->settings->authormode->put('authormode_default_posts_only', true, 'boolean');
}
dcCore::app()->setVersion('authorMode', $new_version);

return true;
