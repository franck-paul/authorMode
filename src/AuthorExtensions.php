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

class AuthorExtensions
{
    public static function getAuthorCN(MetaRecord $rs): string
    {
        return App::users()->getUserCN(
            $rs->user_id,
            $rs->user_name,
            $rs->user_firstname,
            $rs->user_displayname
        );
    }

    public static function getAuthorLink(MetaRecord $rs): string
    {
        $res = '%1$s';
        $url = $rs->user_url;
        if ($url) {
            $res = '<a href="%2$s">%1$s</a>';
        }

        return sprintf($res, $rs->getAuthorCN(), $url);
    }

    public static function getAuthorEmail(MetaRecord $rs, bool $encoded = true): string
    {
        if ($encoded) {
            return strtr($rs->user_email, ['@' => '%40', '.' => '%2e']);
        }

        return $rs->user_email;
    }
}
