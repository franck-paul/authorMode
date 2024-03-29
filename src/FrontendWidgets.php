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

use Dotclear\App;
use Dotclear\Helper\Html\Html;
use Dotclear\Plugin\widgets\WidgetsElement;

class FrontendWidgets
{
    public static function authors(WidgetsElement $w): string
    {
        $settings = My::settings();

        if (!$settings->authormode_active) {
            return '';
        }

        if ($w->offline) {
            return '';
        }

        if (($w->homeonly == 1 && !App::url()->isHome(App::url()->getType())) || ($w->homeonly == 2 && App::url()->isHome(App::url()->getType()))) {
            return '';
        }

        $rs = CoreHelper::getPostsUsers();
        if ($rs->isEmpty()) {
            return '';
        }

        $currentuser = match (App::url()->getType()) {
            'post'   => App::frontend()->context()->posts->user_id,
            'author' => App::frontend()->context()->users->user_id,
            default  => '',
        };

        $res = ($w->title ? $w->renderTitle(Html::escapeHTML($w->title)) : '') .
            '<ul>';

        while ($rs->fetch()) {
            $res .= '<li' .
            ($rs->user_id == $currentuser ? ' class="current-author"' : '') .
            '><a href="' . App::blog()->url() . App::url()->getBase('author') . '/' .
            $rs->user_id . '">' .
            Html::escapeHTML(
                App::users()->getUserCN(
                    $rs->user_id,
                    $rs->user_name,
                    $rs->user_firstname,
                    $rs->user_displayname
                )
            ) . '</a>' .
                ($w->get('postcount') ? ' (' . $rs->nb_post . ')' : '') .
                '</li>';
        }

        $res .= '</ul>';

        if (is_null($w->get('allauthors')) || $w->get('allauthors')) {
            $res .= '<p class="listauthors"><strong><a href="' . App::blog()->url() . App::url()->getBase('authors') . '">' . __('List of authors') . '</a></strong></p>';
        }

        return $w->renderDiv((bool) $w->content_only, 'authors ' . $w->class, '', $res);
    }
}
