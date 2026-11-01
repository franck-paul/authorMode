<?php

/**
 * @brief authorMode, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul contact@open-time.net
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\authorMode;

use Dotclear\App;
use Dotclear\Database\MetaRecord;
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
    public static function authors(WidgetsElement $widgetsElement): string
    {
        $settings = My::settings();

        if (!$settings->getBool('authormode_active', false)) {
            return '';
        }

        if ($widgetsElement->isOffline()) {
            return '';
        }

        if (!$widgetsElement->checkHomeOnly(App::url()->getType())) {
            return '';
        }

        $metaRecord = CoreHelper::getPostsUsers();
        if ($metaRecord->isEmpty()) {
            return '';
        }

        $post_user_id = App::frontend()->context()->posts instanceof MetaRecord ? App::frontend()->context()->posts->strField('user_id') : '';
        $user_id      = App::frontend()->context()->users instanceof MetaRecord ? App::frontend()->context()->users->strField('user_id') : '';

        $currentuser = match (App::url()->getType()) {
            'post'   => $post_user_id,
            'author' => $user_id,
            default  => '',
        };

        $items = [];

        if ($widgetsElement->getTitle() !== '') {
            $items[] = (new Text(null, $widgetsElement->renderTitle(Html::escapeHTML($widgetsElement->getTitle()))));
        }

        $lines = function () use ($metaRecord, $currentuser, $widgetsElement) {
            while ($metaRecord->fetch()) {
                $user_id = $metaRecord->strField('user_id');
                yield (new Li())
                    ->class($user_id === $currentuser ? 'current-author' : '')
                    ->items([
                        (new Link())
                            ->href(App::blog()->url() . App::url()->getBase('author') . '/' . $user_id)
                            ->text(Html::escapeHTML(
                                App::users()->getUserCN(
                                    $user_id,
                                    $metaRecord->strField('user_name'),
                                    $metaRecord->strField('user_firstname'),
                                    $metaRecord->strField('user_displayname')
                                )
                            )),
                        (bool) $widgetsElement->get('postcount') ? (new Text(null, ' (' . $metaRecord->intField('nb_post') . ')')) : (new None()),
                    ]);
            }
        };

        $items[] = (new Ul())
            ->items([
                ... $lines(),
            ]);

        if (is_null($widgetsElement->get('allauthors')) || (bool) $widgetsElement->get('allauthors')) {
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

        return $widgetsElement->renderDiv($widgetsElement->getContentOnly(), 'authors ' . $widgetsElement->getClass(), '', $res);
    }
}
