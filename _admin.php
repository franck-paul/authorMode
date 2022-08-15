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
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

require_once __DIR__ . '/_widgets.php';

$_menu['Blog']->addItem(
    'authorMode',
    'plugin.php?p=authorMode',
    'index.php?pf=authorMode/icon.png',
    preg_match('/plugin.php\?p=authorMode(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->isSuperAdmin()
);

dcCore::app()->addBehavior('adminUserHeaders', ['authorModeBehaviors', 'adminAuthorHeaders']);
dcCore::app()->addBehavior('adminPreferencesHeaders', ['authorModeBehaviors', 'adminAuthorHeaders']);
dcCore::app()->addBehavior('adminUserForm', ['authorModeBehaviors', 'adminAuthorForm']);        // user.php
dcCore::app()->addBehavior('adminPreferencesForm', ['authorModeBehaviors', 'adminAuthorForm']); //preferences.php
dcCore::app()->addBehavior('adminBeforeUserCreate', ['authorModeBehaviors', 'adminBeforeUserUpdate']);
dcCore::app()->addBehavior('adminBeforeUserUpdate', ['authorModeBehaviors', 'adminBeforeUserUpdate']);
dcCore::app()->addBehavior('adminBeforeUserOptionsUpdate', ['authorModeBehaviors', 'adminBeforeUserUpdate']); //preferences.php
dcCore::app()->addBehavior('adminDashboardFavorites', 'authorModeDashboardFavorites');

class authorModeBehaviors
{
    public static function adminBeforeUserUpdate($cur, $user_id = '')
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
        dcPage::jsToolBar() .
        $admin_post_behavior .
        dcPage::jsConfirmClose('opts-forms') .
        dcPage::jsModuleLoad('authorMode/_user.js', dcCore::app()->getVersion('authorMode'));
    }

    public static function adminAuthorForm($rs)
    {
        $user_desc = '';
        if ($rs instanceof dcCore) {
            $strReq = 'SELECT user_desc ' .
            'FROM ' . $rs->con->escapeSystem($rs->prefix . 'user') . ' ' .
            "WHERE user_id = '" . $rs->con->escape($rs->auth->userID()) . "' ";
            $_rs = $rs->con->select($strReq);
            if (!$_rs->isEmpty()) {
                $user_desc = $_rs->user_desc;
            }
        } elseif ($rs instanceof record && $rs->exists('user_desc')) {
            $user_desc = $rs->user_desc;
        }

        echo
        '<p><label>' . __('Author\'s description:') .
        dcPage::help('users', 'user_desc') . '</label>' .
        form::textarea('user_desc', 50, 8, html::escapeHTML($user_desc), '', '4') .
            '</p>';
    }
}

function authorModeDashboardFavorites($core, $favs)
{
    $favs->register('authorMode', [
        'title'       => __('Authors'),
        'url'         => 'plugin.php?p=authorMode',
        'small-icon'  => 'index.php?pf=authorMode/icon.png',
        'large-icon'  => 'index.php?pf=authorMode/icon-big.png',
        'permissions' => 'usage,contentadmin',
    ]);
}
