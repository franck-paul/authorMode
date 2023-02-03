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

if (!dcCore::app()->newVersion(basename(__DIR__), dcCore::app()->plugins->moduleInfo(basename(__DIR__), 'version'))) {
    return;
}

$old_version = dcCore::app()->getVersion(basename(__DIR__));

if ($old_version === null) {
    dcCore::app()->blog->settings->authormode->put('authormode_active', false, 'boolean');
    dcCore::app()->blog->settings->authormode->put('authormode_url_author', 'author', 'string');
    dcCore::app()->blog->settings->authormode->put('authormode_url_authors', 'authors', 'string');
    dcCore::app()->blog->settings->authormode->put('authormode_default_alpha_order', true, 'boolean');
    dcCore::app()->blog->settings->authormode->put('authormode_default_posts_only', true, 'boolean');
} elseif (version_compare($old_version, '1.1', '<=')) {
    dcCore::app()->blog->settings->authormode->put('authormode_default_alpha_order', true, 'boolean');
    dcCore::app()->blog->settings->authormode->put('authormode_default_posts_only', true, 'boolean');
}

return true;
