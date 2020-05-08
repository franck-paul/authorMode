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

if (!defined('DC_CONTEXT_ADMIN')) {exit;}

$new_version = $core->plugins->moduleInfo('authorMode', 'version');
$cur_version = $core->getVersion('authorMode');
if (version_compare($cur_version, $new_version, '>=')) {
    return;
}

$core->blog->settings->addNameSpace('authormode');
if ($cur_version === null) {
    $core->blog->settings->authormode->put('authormode_active', false, 'boolean');
    $core->blog->settings->authormode->put('authormode_url_author', 'author', 'string');
    $core->blog->settings->authormode->put('authormode_url_authors', 'authors', 'string');
    $core->blog->settings->authormode->put('authormode_default_alpha_order', true, 'boolean');
    $core->blog->settings->authormode->put('authormode_default_posts_only', true, 'boolean');
} elseif (version_compare($cur_version, '1.1', '<=')) {
    $core->blog->settings->authormode->put('authormode_default_alpha_order', true, 'boolean');
    $core->blog->settings->authormode->put('authormode_default_posts_only', true, 'boolean');
}
$core->setVersion('authorMode', $new_version);
return true;
