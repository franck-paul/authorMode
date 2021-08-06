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

require_once dirname(__FILE__) . '/_widgets.php';

$_menu['Blog']->addItem('authorMode', 'plugin.php?p=authorMode', 'index.php?pf=authorMode/icon.png',
    preg_match('/plugin.php\?p=authorMode(&.*)?$/', $_SERVER['REQUEST_URI']),
    $core->auth->isSuperAdmin());

$core->addBehavior('adminUserHeaders', ['authorModeBehaviors', 'adminAuthorHeaders']);
$core->addBehavior('adminPreferencesHeaders', ['authorModeBehaviors', 'adminAuthorHeaders']);
$core->addBehavior('adminUserForm', ['authorModeBehaviors', 'adminAuthorForm']);        // user.php
$core->addBehavior('adminPreferencesForm', ['authorModeBehaviors', 'adminAuthorForm']); //preferences.php
$core->addBehavior('adminBeforeUserCreate', ['authorModeBehaviors', 'adminBeforeUserUpdate']);
$core->addBehavior('adminBeforeUserUpdate', ['authorModeBehaviors', 'adminBeforeUserUpdate']);
$core->addBehavior('adminBeforeUserOptionsUpdate', ['authorModeBehaviors', 'adminBeforeUserUpdate']); //preferences.php
$core->addBehavior('adminDashboardFavorites', 'authorModeDashboardFavorites');

class authorModeBehaviors
{
    public static function adminBeforeUserUpdate($cur, $user_id = '')
    {
        $cur->user_desc = $_POST['user_desc'];
    }

    public static function adminAuthorHeaders()
    {
        global $core;

        $post_format = $core->auth->getOption('post_format');
        $post_editor = $core->auth->getOption('editor');

        $admin_post_behavior = '';
        if ($post_editor && !empty($post_editor[$post_format])) {
            $admin_post_behavior = $core->callBehavior('adminPostEditor', $post_editor[$post_format],
                'user_desc', ['#user_desc']
            );
        }

        return
        dcPage::jsToolBar() .
        $admin_post_behavior .
        dcPage::jsConfirmClose('opts-forms') .
        dcPage::jsLoad(urldecode(dcPage::getPF('authorMode/_user.js')), $core->getVersion('authorMode'));
    }

    public static function adminAuthorForm($rs)
    {
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
        } else {
            $user_desc = '';
        }

        echo
        '<p><label>' . __('Author\'s description:') .
        dcPage::help('users', 'user_desc') . '</label>' .
        form::textarea('user_desc', 50, 8, html::escapeHTML($user_desc), '', 4) .
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
        'permissions' => 'usage,contentadmin'
    ]);
}
