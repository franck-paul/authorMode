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
use Dotclear\Helper\Html\Form\Li;
use Dotclear\Helper\Html\Form\Link;
use Dotclear\Helper\Html\Form\None;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Set;
use Dotclear\Helper\Html\Form\Strong;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Form\Ul;
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

        $items = [];

        if ($w->title) {
            $items[] = (new Text(null, $w->renderTitle(Html::escapeHTML($w->title))));
        }

        $lines = function () use ($rs, $currentuser, $w) {
            while ($rs->fetch()) {
                yield (new Li())
                    ->class($rs->user_id === $currentuser ? 'current-author' : '')
                    ->items([
                        (new Link())
                            ->href(App::blog()->url() . App::url()->getBase('author') . '/' . $rs->user_id)
                            ->text(Html::escapeHTML(
                                App::users()->getUserCN(
                                    $rs->user_id,
                                    $rs->user_name,
                                    $rs->user_firstname,
                                    $rs->user_displayname
                                )
                            )),
                        $w->get('postcount') ? (new Text(null, ' (' . $rs->nb_post . ')')) : (new None()),
                    ]);
            }
        };

        $items[] = (new Ul())
            ->items([
                ... $lines(),
            ]);

        if (is_null($w->get('allauthors')) || $w->get('allauthors')) {
            $items[] = (new Para())
                ->class('listauthors')
                ->items([
                    (new Link())
                        ->href(App::blog()->url() . App::url()->getBase('authors'))
                        ->items([
                            new Strong(__('List of authors')),
                        ]),
                ]);
        }

        $res = (new Set())
            ->items($items)
        ->render();

        return $w->renderDiv((bool) $w->content_only, 'authors ' . $w->class, '', $res);
    }
}
