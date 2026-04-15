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
use Dotclear\Plugin\TemplateHelper\Code;

class FrontendBehaviors
{
    public static function addTplPath(): string
    {
        App::frontend()->template()->appendPath(My::tplPath());

        return '';
    }

    public static function publicBreadcrumb(string $context): string
    {
        return match ($context) {
            'author'  => __('Author\'s page'),
            'authors' => __('List of authors'),
            default   => '',
        };
    }

    public static function publicHeadContent(): string
    {
        echo
        My::cssLoad('authorMode.css');

        return '';
    }

    /**
     * @param      string   $b      The block
     */
    public static function templateBeforeBlock(string $b): string
    {
        if ($b === 'Comments') {
            return Code::getPHPCode(
                self::userID(...),
            );
        }

        return '';
    }

    // Template code methods

    private static function userID(
    ): void {
        global $params; // @phpcode-remove
        if (!isset($params) || !is_array($params)) {
            $params = [];
        }
        if (App::frontend()->context()->exists('users') && App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_user_id    = is_string($authormode_user_id = App::frontend()->context()->users->user_id) ? $authormode_user_id : '';
            $authormode_params_sql = is_string($authormode_params_sql = $params['sql']) ? $authormode_params_sql : '';
            $params['sql']         = $authormode_params_sql . " AND P.user_id = '" . $authormode_user_id . "' ";
            unset($authormode_user_id, $authormode_params_sql);
        }
    }
}
