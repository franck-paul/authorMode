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

use dcAuth;
use dcCore;
use dcFavorites;
use dcPage;
use Dotclear\Database\MetaRecord;
use Dotclear\Helper\Html\Form\Fieldset;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Legend;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Textarea;
use Dotclear\Helper\Html\Html;

class BackendBehaviors
{
    public static function adminBeforeUserUpdate($cur)
    {
        $cur->user_desc = $_POST['user_desc'];
    }

    public static function adminAuthorHeaders()
    {
        $post_format = dcCore::app()->auth->getOption('post_format');
        $post_editor = dcCore::app()->auth->getOption('editor');

        $admin_post_behavior = '';
        if ($post_editor && !empty($post_editor[$post_format])) {
            $admin_post_behavior = dcCore::app()->callBehavior(
                'adminPostEditor',
                $post_editor[$post_format],
                'user_desc',
                ['#user_desc']
            );
        }

        return
        $admin_post_behavior .
        dcPage::jsConfirmClose('opts-forms') .
        dcPage::jsModuleLoad(My::id() . '/js/_user.js', dcCore::app()->getVersion('authorMode'));
    }

    public static function adminPreferencesForm()
    {
        $user_desc = '';
        $strReq    = 'SELECT user_desc ' .
        'FROM ' . dcCore::app()->con->escapeSystem(dcCore::app()->prefix . dcAuth::USER_TABLE_NAME) . ' ' .
        "WHERE user_id = '" . dcCore::app()->con->escape(dcCore::app()->auth->userID()) . "' ";
        $_rs = new MetaRecord(dcCore::app()->con->select($strReq));
        if (!$_rs->isEmpty()) {
            $user_desc = $_rs->user_desc;
        }

        echo
        (new Fieldset('author_mode'))
        ->legend((new Legend(__('Author Mode'))))
        ->fields([
            (new Para())->items([
                (new Textarea('user_desc'))
                    ->cols(50)
                    ->rows(8)
                    ->value(Html::escapeHTML($user_desc))
                    ->label((new Label(__('Author\'s description:'), Label::OUTSIDE_TEXT_BEFORE))),
            ]),
        ])
        ->render();
    }

    public static function adminUserForm($rs)
    {
        $user_desc = '';
        if ($rs instanceof MetaRecord && $rs->exists('user_desc')) {
            $user_desc = $rs->user_desc;
        }

        echo
        (new Fieldset('author_mode'))
        ->legend((new Legend(__('Author Mode'))))
        ->fields([
            (new Para())->items([
                (new Textarea('user_desc'))
                    ->cols(50)
                    ->rows(8)
                    ->value(Html::escapeHTML($user_desc))
                    ->label((new Label(__('Author\'s description:'), Label::OUTSIDE_TEXT_BEFORE))),
            ]),
        ])
        ->render();
    }

    public static function authorModeDashboardFavorites(dcFavorites $favs)
    {
        $favs->register('authorMode', [
            'title'       => __('Authors'),
            'url'         => My::makeUrl(),
            'small-icon'  => My::icons(),
            'large-icon'  => My::icons(),
            'permissions' => My::checkContext(My::MENU),
        ]);
    }
}
