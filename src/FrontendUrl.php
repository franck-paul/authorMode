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
use Dotclear\Core\Frontend\Url;

class FrontendUrl extends Url
{
    public static function Author(?string $args): void
    {
        $n = self::getPageNumber($args);

        if ($args == '' && !$n) {
            self::p404();
        } else {
            if ($n) {
                App::frontend()->setPageNumber($n);
            }

            App::frontend()->context()->users = CoreHelper::getPostsUsers($args);

            if (App::frontend()->context()->users->isEmpty()) {
                self::p404();
            }

            self::serveDocument('author.html');
        }

        exit;
    }

    public static function Authors(?string $args): void
    {
        App::frontend()->context()->users = CoreHelper::getPostsUsers($args);

        if (App::frontend()->context()->users->isEmpty()) {
            self::p404();
        }

        self::serveDocument('authors.html');
        exit;
    }

    public static function feed($args): void
    {
        $mime     = 'application/xml';
        $author   = '';
        $type     = '';
        $comments = false;

        if (preg_match('#^(.+)/(atom|rss2)(/comments)?$#', (string) $args, $m)) {
            $author   = $m[1];
            $type     = $m[2];
            $comments = isset($m[3]);
            ;
        } else {
            self::p404();
        }

        App::frontend()->context()->users = CoreHelper::getPostsUsers($author);

        if (App::frontend()->context()->users->isEmpty()) {
            self::p404();
        }

        if ($type === 'atom') {
            $mime = 'application/atom+xml';
        }

        $tpl = $type;
        if ($comments) {
            $tpl .= '-comments';
            App::frontend()->context()->nb_comment_per_page = App::blog()->settings()->system->nb_comment_per_feed;
        } else {
            App::frontend()->context()->nb_entry_per_page = App::blog()->settings()->system->nb_post_per_feed;
            App::frontend()->context()->short_feed_items  = App::blog()->settings()->system->short_feed_items;
        }

        $tpl .= '.xml';

        self::serveDocument($tpl, $mime);
        exit;
    }
}
