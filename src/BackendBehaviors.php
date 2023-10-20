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
use Dotclear\Core\Backend\Favorites;
use Dotclear\Core\Backend\Page;
use Dotclear\Database\Cursor;
use Dotclear\Database\MetaRecord;
use Dotclear\Database\Statement\SelectStatement;
use Dotclear\Helper\Html\Form\Fieldset;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Legend;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Textarea;
use Dotclear\Helper\Html\Html;
use Dotclear\Interface\Core\AuthInterface;

class BackendBehaviors
{
    public static function adminBeforeUserUpdate(Cursor $cur): string
    {
        $cur->user_desc = $_POST['user_desc'];

        return '';
    }

    public static function adminAuthorHeaders(): string
    {
        $post_format = App::auth()->getOption('post_format');
        $post_editor = App::auth()->getOption('editor');

        $admin_post_behavior = '';
        if ($post_editor && !empty($post_editor[$post_format])) {
            $admin_post_behavior = App::behavior()->callBehavior(
                'adminPostEditor',
                $post_editor[$post_format],
                'user_desc',
                ['#user_desc']
            );
        }

        return
        $admin_post_behavior .
        Page::jsConfirmClose('opts-forms') .
        My::jsLoad('_user.js');
    }

    public static function adminPreferencesForm(): string
    {
        $user_desc = '';

        $sql = new SelectStatement();
        $sql
            ->column('user_desc')
            ->from(App::con()->prefix() . AuthInterface::USER_TABLE_NAME)
            ->where('user_id = ' . $sql->quote((string) App::auth()->userID()))
        ;

        $rs = $sql->select();
        if ($rs && !$rs->isEmpty()) {
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

        return '';
    }

    public static function adminUserForm(?MetaRecord $rs): string
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

        return '';
    }

    public static function authorModeDashboardFavorites(Favorites $favs): string
    {
        $favs->register('authorMode', [
            'title'       => __('Authors'),
            'url'         => My::manageUrl(),
            'small-icon'  => My::icons(),
            'large-icon'  => My::icons(),
            'permissions' => My::checkContext(My::MENU),
        ]);

        return '';
    }
}
