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
use Dotclear\Core\Process;
use Exception;

class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        try {
            $old_version = App::version()->getVersion(My::id());
            // Rename settings namespace
            if (version_compare((string) $old_version, '3.0', '<') && App::blog()->settings()->exists('authormode')) {
                App::blog()->settings()->delWorkspace(My::id());
                App::blog()->settings()->renWorkspace('authormode', My::id());
            }

            $settings = My::settings();

            $settings->put('authormode_active', false, App::blogWorkspace()::NS_BOOL, '', false, true);
            $settings->put('authormode_url_author', 'author', App::blogWorkspace()::NS_STRING, '', false, true);
            $settings->put('authormode_url_authors', 'authors', App::blogWorkspace()::NS_STRING, '', false, true);
            $settings->put('authormode_default_alpha_order', true, App::blogWorkspace()::NS_BOOL, '', false, true);
            $settings->put('authormode_default_posts_only', true, App::blogWorkspace()::NS_BOOL, '', false, true);
        } catch (Exception $exception) {
            App::error()->add($exception->getMessage());
        }

        return true;
    }
}
