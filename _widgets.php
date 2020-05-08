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

$core->addBehavior('initWidgets', ['widgetsAuthorMode', 'init']);

class widgetsAuthorMode
{
    public static function authors($w)
    {
        global $core;

        if (!$core->blog->settings->authormode->authormode_active) {
            return;
        }

        if ($w->offline) {
            return;
        }

        if (($w->homeonly == 1 && !$core->url->isHome($core->url->type)) ||
            ($w->homeonly == 2 && $core->url->isHome($core->url->type))) {
            return;
        }

        $rs = authormodeUtils::getPostsUsers();
        if ($rs->isEmpty()) {
            return;
        }

        switch ($core->url->type) {
            case 'post':
                $currentuser = $GLOBALS['_ctx']->posts->user_id;
                break;
            case 'author':
                $currentuser = $GLOBALS['_ctx']->users->user_id;
                break;
            default:
                $currentuser = '';
        }

        $res =
            ($w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '') .
            '<ul>';

        $res .=
        '<li class="listauthors"><strong><a href="' . $core->blog->url . $core->url->getBase("authors") . '">' .
        __('List of authors') . '</a></strong></li>';

        while ($rs->fetch()) {
            $res .= '<li' .
            ($rs->user_id == $currentuser ? ' class="current-author"' : '') .
            '><a href="' . $core->blog->url . $core->url->getBase('author') . '/' .
            $rs->user_id . '">' .
            html::escapeHTML(
                dcUtils::getUserCN($rs->user_id, $rs->user_name,
                    $rs->user_firstname, $rs->user_displayname)) . '</a>' .
                ($w->postcount ? ' (' . $rs->nb_post . ')' : '') .
                '</li>';
        }
        $res .= '</ul>';

        return $w->renderDiv($w->content_only, 'authors ' . $w->class, '', $res);

    }

    public static function init($w)
    {
        $w
            ->create('authors', __('AuthorMode: authors'), ['widgetsAuthorMode', 'authors'], null, __('List of authors'))
            ->addTitle(__('Authors'))
            ->setting('postcount', __('With entries counts'), 0, 'check')
            ->addHomeOnly()
            ->addContentOnly()
            ->addClass()
            ->addOffline();
    }
}
