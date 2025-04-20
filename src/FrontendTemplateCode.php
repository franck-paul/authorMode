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

class FrontendTemplateCode
{
    /**
     * PHP code for tpl:Authors block
     */
    public static function Authors(
        string $_post_type_,
        string $_sort_by_,
        string $_order_,
        string $_content_HTML
    ): void {
        global $params; // @phpcode-remove
        if (!App::frontend()->context()->exists('users')) {
            if ($_post_type_ !== '') {
                $params['post_type'] = addslashes($_post_type_);
            }
            if ($_sort_by_ !== '' && $_order_ !== '') {
                $params['order'] = $_sort_by_ . ' ' . $_order_ ;
            }
            if ($_post_type_ === '' && $_sort_by_ === '' && $_order_ === '') {
                $params = [];
            }
            App::frontend()->context()->users = \Dotclear\Plugin\authorMode\CoreHelper::getPostsUsers($params);
            unset($params);
        }
        while (App::frontend()->context()->users->fetch()) : ?>
            $_content_HTML
        <?php endwhile;
        App::frontend()->context()->users = null;
    }

    /**
     * PHP code for tpl:AuthorsHeader block
     */
    public static function AuthorsHeader(
        string $_content_HTML
    ): void {
        if (App::frontend()->context()->users->isStart()) : ?>
            $_content_HTML
        <?php endif;
    }

    /**
     * PHP code for tpl:AuthorsFooter block
     */
    public static function AuthorsFooter(
        string $_content_HTML
    ): void {
        if (App::frontend()->context()->users->isEnd()) : ?>
            $_content_HTML
        <?php endif;
    }

    /**
     * PHP code for tpl:AuthorDesc value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorDesc(
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::frontend()->context()->users->user_desc,
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:AuthorPostsURL value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorPostsURL(
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::blog()->url() . App::url()->getBase('author') . '/' . App::frontend()->context()->users->user_id,
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:AuthorNbPosts value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorNbPosts(
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::frontend()->context()->users->nb_post,
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:AuthorCommonName value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorCommonName(
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::frontend()->context()->users->getAuthorCN(),
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:AuthorDisplayName value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorDisplayName(
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::frontend()->context()->users->user_displayname,
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:AuthorFirstName value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorFirstName(
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::frontend()->context()->users->user_firstname,
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:AuthorName value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorName(
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::frontend()->context()->users->user_name,
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:AuthorID value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorID(
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::frontend()->context()->users->user_id,
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:AuthorEmail value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorEmail(
        bool $_spam_protected_,
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::frontend()->context()->users->getAuthorEmail($_spam_protected_),
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:AuthorLink value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorLink(
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::frontend()->context()->users->getAuthorLink(),
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:AuthorURL value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorURL(
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::frontend()->context()->users->user_url,
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:AuthorFeedURL value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorFeedURL(
        string $_type_,
        array $_params_,
        string $_tag_
    ): void {
        echo \Dotclear\Core\Frontend\Ctx::global_filters(
            App::blog()->url() . App::url()->getBase('author_feed') . '/' . rawurlencode((string) App::frontend()->context()->users->user_id) . '/' . $_type_,
            $_params_,
            $_tag_
        );
    }
}
