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
use Dotclear\Database\Statement\JoinStatement;
use Dotclear\Database\Statement\SelectStatement;

class CoreHelper
{
    /**
     * Gets the posts users.
     *
     * @param      null|array<string, mixed>|string       $params  The parameters
     *
     * @return     MetaRecord  The posts users.
     */
    public static function getPostsUsers($params = null): MetaRecord
    {
        $settings = My::settings();

        if ($params !== null && is_string($params)) {
            $params = ['author' => $params];
        }

        $sql = new SelectStatement();
        $sql
            ->columns([
                'P.user_id',
                'user_name',
                'user_firstname',
                'user_displayname',
                'user_desc',
                $sql->count('P.post_id', 'nb_post'),
            ])
            ->from($sql->as(App::con()->prefix() . App::auth()::USER_TABLE_NAME, 'U'))
            ->join(
                (new JoinStatement())
                    ->left()
                    ->from($sql->as(App::con()->prefix() . App::blog()::POST_TABLE_NAME, 'P'))
                    ->on('P.user_id = U.user_id')
                    ->statement()
            )
            ->where('blog_id = ' . $sql->quote(App::blog()->id()))
            ->and('P.post_status > ' . App::status()->post()->threshold())
        ;

        if (!empty($params['author'])) {
            $sql->and('P.user_id = ' . $sql->quote($params['author']));
        }

        if (!empty($params['post_type'])) {
            $sql->and('P.post_type = ' . $sql->quote($params['post_type']));
        } elseif ($settings->authormode_default_posts_only) {
            $sql->and('P.post_type = ' . $sql->quote('post'));
        }

        $sql->group([
            'P.user_id',
            'user_name',
            'user_firstname',
            'user_displayname',
            'user_desc',
        ]);

        if (!empty($params['order'])) {
            $sql->order($sql->escape($params['order']));
        } elseif ($settings->authormode_default_alpha_order) {
            $sql->order([
                'user_displayname',
                'user_firstname',
                'user_name',
            ]);
        }

        $rs = $sql->select();
        if ($rs) {
            $rs->extend(AuthorExtensions::class);
        }

        return $rs ?? MetaRecord::newFromArray([]);
    }
}
