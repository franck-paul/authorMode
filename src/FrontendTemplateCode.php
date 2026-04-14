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
        if (!App::frontend()->context()->exists('users')) {
            $authormode_params = [];
            if ($_post_type_ !== '') {
                $authormode_params['post_type'] = addslashes($_post_type_);
            }
            if ($_sort_by_ !== '' && $_order_ !== '') {
                $authormode_params['order'] = $_sort_by_ . ' ' . $_order_ ;
            }
            if ($_post_type_ === '' && $_sort_by_ === '' && $_order_ === '') {
                $authormode_params = [];
            }
            App::frontend()->context()->users = \Dotclear\Plugin\authorMode\CoreHelper::getPostsUsers($authormode_params);
            unset($authormode_params);
        }
        if (!App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            return;
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord && App::frontend()->context()->users->isStart()) : ?>
            $_content_HTML
        <?php endif;
    }

    /**
     * PHP code for tpl:AuthorsFooter block
     */
    public static function AuthorsFooter(
        string $_content_HTML
    ): void {
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord && App::frontend()->context()->users->isEnd()) : ?>
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_user_desc = is_string($authormode_user_desc = App::frontend()->context()->users->user_desc) ? $authormode_user_desc : '';
            echo App::frontend()->context()::global_filters(
                $authormode_user_desc,
                $_params_,
                $_tag_
            );
            unset($authormode_user_desc);
        }
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_user_id = is_string($authormode_user_id = App::frontend()->context()->users->user_id) ? $authormode_user_id : '';
            echo App::frontend()->context()::global_filters(
                App::blog()->url() . App::url()->getBase('author') . '/' . $authormode_user_id,
                $_params_,
                $_tag_
            );
            unset($authormode_user_id);
        }
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_nb_post = is_numeric($authormode_nb_post = App::frontend()->context()->users->nb_post) ? (int) $authormode_nb_post : 0;
            echo App::frontend()->context()::global_filters(
                (string) $authormode_nb_post,
                $_params_,
                $_tag_
            );
            unset($authormode_nb_post);
        }
    }

    /**
     * PHP code for tpl:AuthorEntriesCount value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function AuthorEntriesCount(
        string $_singular_,
        string $_plural_,
        array $_params_,
        string $_tag_
    ): void {
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_nb_post = is_numeric($authormode_nb_post = App::frontend()->context()->users->nb_post) ? (int) $authormode_nb_post : 0;
            echo App::frontend()->context()::global_filters(
                sprintf(__($_singular_, $_plural_, $authormode_nb_post), $authormode_nb_post),
                $_params_,
                $_tag_
            );
            unset($authormode_nb_post);
        }
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_author_cn = is_string($authormode_author_cn = App::frontend()->context()->users->getAuthorCN()) ? $authormode_author_cn : '';
            echo App::frontend()->context()::global_filters(
                $authormode_author_cn,
                $_params_,
                $_tag_
            );
            unset($authormode_author_cn);
        }
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_user_displayname = is_string($authormode_user_displayname = App::frontend()->context()->users->user_displayname) ? $authormode_user_displayname : '';
            echo App::frontend()->context()::global_filters(
                $authormode_user_displayname,
                $_params_,
                $_tag_
            );
            unset($authormode_user_displayname);
        }
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_user_displayname = is_string($authormode_user_displayname = App::frontend()->context()->users->user_displayname) ? $authormode_user_displayname : '';
            echo App::frontend()->context()::global_filters(
                $authormode_user_displayname,
                $_params_,
                $_tag_
            );
            unset($authormode_user_displayname);
        }
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_user_name = is_string($authormode_user_name = App::frontend()->context()->users->user_name) ? $authormode_user_name : '';
            echo App::frontend()->context()::global_filters(
                $authormode_user_name,
                $_params_,
                $_tag_
            );
            unset($authormode_user_name);
        }
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_user_id = is_string($authormode_user_id = App::frontend()->context()->users->user_id) ? $authormode_user_id : '';
            echo App::frontend()->context()::global_filters(
                $authormode_user_id,
                $_params_,
                $_tag_
            );
            unset($authormode_user_id);
        }
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_author_email = is_string($authormode_author_email = App::frontend()->context()->users->getAuthorEmail($_spam_protected_)) ? $authormode_author_email : '';
            echo App::frontend()->context()::global_filters(
                $authormode_author_email,
                $_params_,
                $_tag_
            );
            unset($authormode_author_email);
        }
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_author_link = is_string($authormode_author_link = App::frontend()->context()->users->getAuthorLink()) ? $authormode_author_link : '';
            echo App::frontend()->context()::global_filters(
                $authormode_author_link,
                $_params_,
                $_tag_
            );
            unset($authormode_author_link);
        }
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_user_url = is_string($authormode_user_url = App::frontend()->context()->users->user_url) ? $authormode_user_url : '';
            echo App::frontend()->context()::global_filters(
                $authormode_user_url,
                $_params_,
                $_tag_
            );
            unset($authormode_user_url);
        }
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
        if (App::frontend()->context()->users instanceof \Dotclear\Database\MetaRecord) {
            $authormode_user_id = is_string($authormode_user_id = App::frontend()->context()->users->user_id) ? $authormode_user_id : '';
            echo App::frontend()->context()::global_filters(
                App::blog()->url() . App::url()->getBase('author_feed') . '/' . rawurlencode($authormode_user_id) . '/' . $_type_,
                $_params_,
                $_tag_
            );
            unset($authormode_user_id);
        }
    }
}
