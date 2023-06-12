<?php
/**
 * @brief authorMode, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\authorMode;

use dcCore;
use dcUtils;
use Dotclear\Helper\Html\Html;
use Dotclear\Plugin\widgets\WidgetsElement;

class FrontendWidgets
{
    public static function authors(WidgetsElement $w)
    {
        $settings = dcCore::app()->blog->settings->get(My::id());

        if (!$settings->authormode_active) {
            return;
        }

        if ($w->offline) {
            return;
        }

        if (($w->homeonly == 1 && !dcCore::app()->url->isHome(dcCore::app()->url->type)) || ($w->homeonly == 2 && dcCore::app()->url->isHome(dcCore::app()->url->type))) {
            return;
        }

        $rs = CoreHelper::getPostsUsers();
        if ($rs->isEmpty()) {
            return;
        }

        switch (dcCore::app()->url->type) {
            case 'post':
                $currentuser = dcCore::app()->ctx->posts->user_id;

                break;
            case 'author':
                $currentuser = dcCore::app()->ctx->users->user_id;

                break;
            default:
                $currentuser = '';
        }

        $res = ($w->title ? $w->renderTitle(Html::escapeHTML($w->title)) : '') .
            '<ul>';

        $res .= '<li class="listauthors"><strong><a href="' . dcCore::app()->blog->url . dcCore::app()->url->getBase('authors') . '">' .
        __('List of authors') . '</a></strong></li>';

        while ($rs->fetch()) {
            $res .= '<li' .
            ($rs->user_id == $currentuser ? ' class="current-author"' : '') .
            '><a href="' . dcCore::app()->blog->url . dcCore::app()->url->getBase('author') . '/' .
            $rs->user_id . '">' .
            Html::escapeHTML(
                dcUtils::getUserCN(
                    $rs->user_id,
                    $rs->user_name,
                    $rs->user_firstname,
                    $rs->user_displayname
                )
            ) . '</a>' .
                ($w->postcount ? ' (' . $rs->nb_post . ')' : '') .
                '</li>';
        }
        $res .= '</ul>';

        return $w->renderDiv($w->content_only, 'authors ' . $w->class, '', $res);
    }
}
