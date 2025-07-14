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

class Prepend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::PREPEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        $settings = My::settings();

        if (App::blog()->isDefined() && $settings->authormode_active) {
            if ($settings->authormode_url_author !== null) {
                $url_prefix = $settings->authormode_url_author;
                if (empty($url_prefix)) {
                    $url_prefix = 'author';
                }

                $feed_prefix = App::url()->getBase('feed') . '/' . $url_prefix;
                App::url()->register('author', $url_prefix, '^' . $url_prefix . '/(.+)$', FrontendUrl::Author(...));
                App::url()->register('author_feed', $feed_prefix, '^' . $feed_prefix . '/(.+)$', FrontendUrl::feed(...));
                unset($url_prefix, $feed_prefix);
            }

            if ($settings->authormode_url_authors !== null) {
                $url_prefix = $settings->authormode_url_authors;
                if (empty($url_prefix)) {
                    $url_prefix = 'authors';
                }

                App::url()->register('authors', $url_prefix, '^' . $url_prefix . '$', FrontendUrl::Authors(...));
                unset($url_prefix);
            }
        }

        return true;
    }
}
