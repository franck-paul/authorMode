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

$core = &$GLOBALS['core'];

class rsAuthor
{
    public static function getAuthorCN($rs)
    {
        return dcUtils::getUserCN($rs->user_id, $rs->user_name,
            $rs->user_firstname, $rs->user_displayname);
    }

    public static function getAuthorLink($rs)
    {
        $res = '%1$s';
        $url = $rs->user_url;
        if ($url) {
            $res = '<a href="%2$s">%1$s</a>';
        }

        return sprintf($res, $rs->getAuthorCN(), $url);
    }

    public static function getAuthorEmail($rs, $encoded = true)
    {
        if ($encoded) {
            return strtr($rs->user_email, array('@' => '%40', '.' => '%2e'));
        }
        return $rs->user_email;
    }
}

$core->blog->settings->addNameSpace('authormode');
if ($core->blog->settings->authormode->authormode_active) {
    if ($core->blog->settings->authormode->authormode_url_author !== null) {
        $url_prefix = $core->blog->settings->authormode->authormode_url_author;
        if (empty($url_prefix)) {
            $url_prefix = 'author';
        }
        $feed_prefix = $core->url->getBase('feed') . '/' . $url_prefix;
        $core->url->register('author', $url_prefix, '^' . $url_prefix . '/(.+)$', array('urlAuthor', 'author'));
        $core->url->register('author_feed', $feed_prefix, '^' . $feed_prefix . '/(.+)$', array('urlAuthor', 'feed'));
        unset($url_prefix, $feed_prefix);
    }

    if ($core->blog->settings->authormode->authormode_url_authors !== null) {
        $url_prefix = $core->blog->settings->authormode->authormode_url_authors;
        if (empty($url_prefix)) {
            $url_prefix = 'authors';
        }
        $core->url->register('authors', $url_prefix, '^' . $url_prefix . '$', array('urlAuthor', 'authors'));
        unset($url_prefix);
    }
}
