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
use Dotclear\Database\MetaRecord;

class CoreHelper
{
    /**
     * Gets the posts users.
     *
     * @param      null|array<string, mixed>|string       $params  The parameters
     *
     * @return     MetaRecord  The posts users.
     */
    public static function getPostsUsers($params = null): MetaRecord
    {
        $settings = My::settings();

        if ($params !== null && is_string($params)) {
            $params = ['author' => $params];
        }

        $strReq = 'SELECT P.user_id, user_name, user_firstname, ' .
        'user_displayname, user_desc, COUNT(P.post_id) as nb_post ' .
        'FROM ' . App::con()->prefix() . App::auth()::USER_TABLE_NAME . ' U ' .
        'LEFT JOIN ' . App::con()->prefix() . 'post P ON P.user_id = U.user_id ' .
        "WHERE blog_id = '" . App::con()->escapeStr(App::blog()->id()) . "' " .
        'AND P.post_status = ' . App::blog()::POST_PUBLISHED . ' ';

        if (!empty($params['author'])) {
            $strReq .= " AND P.user_id = '" . App::con()->escapeStr($params['author']) . "' ";
        }

        if (!empty($params['post_type'])) {
            $strReq .= " AND P.post_type = '" . App::con()->escapeStr($params['post_type']) . "' ";
        } elseif ($settings->authormode_default_posts_only) {
            $strReq .= " AND P.post_type = 'post' ";
        }

        $strReq .= 'GROUP BY P.user_id, user_name, user_firstname, user_displayname, user_desc ';

        if (!empty($params['order'])) {
            $strReq .= 'ORDER BY ' . App::con()->escapeStr($params['order']) . ' ';
        } elseif ($settings->authormode_default_alpha_order) {
            $strReq .= 'ORDER BY user_displayname, user_firstname, user_name ';
        }

        $rs = new MetaRecord(App::con()->select($strReq));
        $rs->extend(AuthorExtensions::class);

        return $rs;
    }
}
