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

use Dotclear\Helper\Html\Html;

if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

require_once __DIR__ . '/_widgets.php';

dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(
    'authorMode',
    'plugin.php?p=authorMode',
    'index.php?pf=authorMode/icon.png',
    preg_match('/plugin.php\?p=authorMode(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->isSuperAdmin()
);

class authorModeBehaviors
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
        dcPage::jsModuleLoad('authorMode/js/_user.js', dcCore::app()->getVersion('authorMode'));
    }

    public static function adminPreferencesForm()
    {
        $user_desc = '';
        $strReq    = 'SELECT user_desc ' .
        'FROM ' . dcCore::app()->con->escapeSystem(dcCore::app()->prefix . dcAuth::USER_TABLE_NAME) . ' ' .
        "WHERE user_id = '" . dcCore::app()->con->escape(dcCore::app()->auth->userID()) . "' ";
        $_rs = new dcRecord(dcCore::app()->con->select($strReq));
        if (!$_rs->isEmpty()) {
            $user_desc = $_rs->user_desc;
        }

        echo
        '<p><label>' . __('Author\'s description:') . '</label>' .
        form::textarea('user_desc', 50, 8, Html::escapeHTML($user_desc), '', '4') .
        '</p>';
    }

    public static function adminUserForm($rs)
    {
        $user_desc = '';
        if ($rs instanceof dcRecord && $rs->exists('user_desc')) {
            $user_desc = $rs->user_desc;
        }

        echo
        '<p><label>' . __('Author\'s description:') . '</label>' .
        form::textarea('user_desc', 50, 8, Html::escapeHTML($user_desc), '', '4') .
        '</p>';
    }

    public static function authorModeDashboardFavorites($favs)
    {
        $favs->register('authorMode', [
            'title'       => __('Authors'),
            'url'         => 'plugin.php?p=authorMode',
            'small-icon'  => 'index.php?pf=authorMode/icon.png',
            'large-icon'  => 'index.php?pf=authorMode/icon-big.png',
            'permissions' => dcCore::app()->auth->makePermissions([
                dcAuth::PERMISSION_USAGE,
                dcAuth::PERMISSION_CONTENT_ADMIN,
            ]),
        ]);
    }
}

dcCore::app()->addBehaviors([
    'adminUserHeaders'             => [authorModeBehaviors::class, 'adminAuthorHeaders'],
    'adminPreferencesHeaders'      => [authorModeBehaviors::class, 'adminAuthorHeaders'],
    'adminUserForm'                => [authorModeBehaviors::class, 'adminUserForm'],        // user.php
    'adminPreferencesFormV2'       => [authorModeBehaviors::class, 'adminPreferencesForm'], //preferences.php
    'adminBeforeUserCreate'        => [authorModeBehaviors::class, 'adminBeforeUserUpdate'],
    'adminBeforeUserUpdate'        => [authorModeBehaviors::class, 'adminBeforeUserUpdate'],
    'adminBeforeUserOptionsUpdate' => [authorModeBehaviors::class, 'adminBeforeUserUpdate'], //preferences.php
    'adminDashboardFavoritesV2'    => [authorModeBehaviors::class, 'authorModeDashboardFavorites'],
]);
