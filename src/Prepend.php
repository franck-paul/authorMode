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

use dcCore;
use dcNsProcess;

class Prepend extends dcNsProcess
{
    protected static $init = false; /** @deprecated since 2.27 */
    public static function init(): bool
    {
        static::$init = My::checkContext(My::PREPEND);

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        $settings = dcCore::app()->blog->settings->get(My::id());

        if (dcCore::app()->blog) {
            if ($settings->authormode_active) {
                if ($settings->authormode_url_author !== null) {
                    $url_prefix = $settings->authormode_url_author;
                    if (empty($url_prefix)) {
                        $url_prefix = 'author';
                    }
                    $feed_prefix = dcCore::app()->url->getBase('feed') . '/' . $url_prefix;
                    dcCore::app()->url->register('author', $url_prefix, '^' . $url_prefix . '/(.+)$', [FrontendUrl::class, 'author']);
                    dcCore::app()->url->register('author_feed', $feed_prefix, '^' . $feed_prefix . '/(.+)$', [FrontendUrl::class, 'feed']);
                    unset($url_prefix, $feed_prefix);
                }

                if ($settings->authormode_url_authors !== null) {
                    $url_prefix = $settings->authormode_url_authors;
                    if (empty($url_prefix)) {
                        $url_prefix = 'authors';
                    }
                    dcCore::app()->url->register('authors', $url_prefix, '^' . $url_prefix . '$', [FrontendUrl::class, 'authors']);
                    unset($url_prefix);
                }
            }
        }

        return true;
    }
}
