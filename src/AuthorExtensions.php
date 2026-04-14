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

class AuthorExtensions
{
    public static function getAuthorCN(MetaRecord $rs): string
    {
        $user_id          = is_string($user_id = $rs->user_id) ? $user_id : '';
        $user_name        = is_string($user_name = $rs->user_name) ? $user_name : '';
        $user_firstname   = is_string($user_firstname = $rs->user_firstname) ? $user_firstname : '';
        $user_displayname = is_string($user_displayname = $rs->user_displayname) ? $user_displayname : '';

        return App::users()->getUserCN(
            $user_id,
            $user_name,
            $user_firstname,
            $user_displayname
        );
    }

    public static function getAuthorLink(MetaRecord $rs): string
    {
        $res = '%1$s';
        $url = is_string($url = $rs->user_url) ? $url : '';
        if ($url !== '') {
            $res = '<a href="%2$s">%1$s</a>';
        }
        $author_cn = is_string($author_cn = $rs->getAuthorCN()) ? $author_cn : '';

        return sprintf($res, $author_cn, $url);
    }

    public static function getAuthorEmail(MetaRecord $rs, bool $encoded = true): string
    {
        $user_email = is_string($user_email = $rs->user_email) ? $user_email : '';
        if ($encoded) {
            return strtr($user_email, ['@' => '%40', '.' => '%2e']);
        }

        return $user_email;
    }
}
