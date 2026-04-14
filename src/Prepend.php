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
use Dotclear\Helper\Process\TraitProcess;

class Prepend
{
    use TraitProcess;

    public static function init(): bool
    {
        return self::status(My::checkContext(My::PREPEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        // Variable data helpers
        $_Bool = fn (mixed $var): bool => (bool) $var;
        $_Str  = fn (mixed $var, string $default = ''): string => $var !== null && is_string($val = $var) ? $val : $default;

        $settings = My::settings();

        if (App::blog()->isDefined() && $_Bool($settings->authormode_active)) {
            $url_prefix = $_Str($settings->authormode_url_author);
            if ($url_prefix === '') {
                $url_prefix = 'author';
            }

            $feed_prefix = App::url()->getBase('feed') . '/' . $url_prefix;
            App::url()->register('author', $url_prefix, '^' . $url_prefix . '/(.+)$', FrontendUrl::Author(...));
            App::url()->register('author_feed', $feed_prefix, '^' . $feed_prefix . '/(.+)$', FrontendUrl::feed(...));

            $url_prefix = $_Str($settings->authormode_url_authors);
            if ($url_prefix !== '') {
                $url_prefix = 'authors';
            }

            App::url()->register('authors', $url_prefix, '^' . $url_prefix . '$', FrontendUrl::Authors(...));
        }

        return true;
    }
}
