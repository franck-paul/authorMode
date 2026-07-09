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
        return App::users()->getUserCN(
            $rs->strField('user_id'),
            $rs->strField('user_name'),
            $rs->strField('user_firstname'),
            $rs->strField('user_displayname')
        );
    }

    public static function getAuthorLink(MetaRecord $rs): string
    {
        $res = '%1$s';
        $url = $rs->strField('user_url');
        if ($url !== '') {
            $res = '<a href="%2$s">%1$s</a>';
        }

        $author_cn = $rs->getAuthorCN();

        return sprintf($res, $author_cn, $url);
    }

    public static function getAuthorEmail(MetaRecord $rs, bool $encoded = true): string
    {
        $user_email = $rs->strField('user_email');
        if ($encoded) {
            return strtr($user_email, ['@' => '%40', '.' => '%2e']);
        }

        return $user_email;
    }
}
