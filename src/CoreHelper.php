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
use dcBlog;
use dcCore;
use Dotclear\Database\MetaRecord;
use Exception;

class CoreHelper
{
    public static function getPostsUsers($params = null)
    {
        $settings = dcCore::app()->blog->settings->get(My::id());

        if ($params !== null && is_string($params)) {
            $params = ['author' => $params];
        }

        $strReq = 'SELECT P.user_id, user_name, user_firstname, ' .
        'user_displayname, user_desc, COUNT(P.post_id) as nb_post ' .
        'FROM ' . dcCore::app()->prefix . dcAuth::USER_TABLE_NAME . ' U ' .
        'LEFT JOIN ' . dcCore::app()->prefix . 'post P ON P.user_id = U.user_id ' .
        "WHERE blog_id = '" . dcCore::app()->con->escape(dcCore::app()->blog->id) . "' " .
        'AND P.post_status = ' . dcBlog::POST_PUBLISHED . ' ';

        if (!empty($params['author'])) {
            $strReq .= " AND P.user_id = '" . dcCore::app()->con->escape($params['author']) . "' ";
        }

        if (!empty($params['post_type'])) {
            $strReq .= " AND P.post_type = '" . dcCore::app()->con->escape($params['post_type']) . "' ";
        } elseif ($settings->authormode_default_posts_only) {
            $strReq .= " AND P.post_type = 'post' ";
        }

        $strReq .= 'GROUP BY P.user_id, user_name, user_firstname, user_displayname, user_desc ';

        if (!empty($params['order'])) {
            $strReq .= 'ORDER BY ' . dcCore::app()->con->escape($params['order']) . ' ';
        } elseif ($settings->authormode_default_alpha_order) {
            $strReq .= 'ORDER BY user_displayname, user_firstname, user_name ';
        }

        try {
            $rs = new MetaRecord(dcCore::app()->con->select($strReq));
            $rs->extend(AuthorExtensions::class);

            return $rs;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
