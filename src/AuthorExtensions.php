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
    public static function getAuthorCN(MetaRecord $metaRecord): string
    {
        return App::users()->getUserCN(
            $metaRecord->strField('user_id'),
            $metaRecord->strField('user_name'),
            $metaRecord->strField('user_firstname'),
            $metaRecord->strField('user_displayname')
        );
    }

    public static function getAuthorLink(MetaRecord $metaRecord): string
    {
        $res = '%1$s';
        $url = $metaRecord->strField('user_url');
        if ($url !== '') {
            $res = '<a href="%2$s">%1$s</a>';
        }

        $author_cn = $metaRecord->getAuthorCN();

        return sprintf($res, $author_cn, $url);
    }

    public static function getAuthorEmail(MetaRecord $metaRecord, bool $encoded = true): string
    {
        $user_email = $metaRecord->strField('user_email');
        if ($encoded) {
            return strtr($user_email, ['@' => '%40', '.' => '%2e']);
        }

        return $user_email;
    }
}
