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

        $selectStatement = new SelectStatement();
        $selectStatement
            ->columns([
                'P.user_id',
                'user_name',
                'user_firstname',
                'user_displayname',
                'user_desc',
                $selectStatement->count('P.post_id', 'nb_post'),
            ])
            ->from($selectStatement->as(App::db()->con()->prefix() . App::auth()::USER_TABLE_NAME, 'U'))
            ->join(
                (new JoinStatement())
                    ->left()
                    ->from($selectStatement->as(App::db()->con()->prefix() . App::blog()::POST_TABLE_NAME, 'P'))
                    ->on('P.user_id = U.user_id')
                    ->statement()
            )
            ->where('blog_id = ' . $selectStatement->quote(App::blog()->id()))
            ->and('P.post_status = ' . App::status()->post()::PUBLISHED)
        ;

        if (!empty($params['author']) && is_string($params['author'])) {
            $selectStatement->and('P.user_id = ' . $selectStatement->quote($params['author']));
        }

        if (!empty($params['post_type']) && is_string($params['post_type'])) {
            $selectStatement->and('P.post_type = ' . $selectStatement->quote($params['post_type']));
        } elseif ($settings->getBool('authormode_default_posts_only')) {
            $selectStatement->and('P.post_type = ' . $selectStatement->quote('post'));
        }

        $selectStatement->group([
            'P.user_id',
            'user_name',
            'user_firstname',
            'user_displayname',
            'user_desc',
        ]);

        if (!empty($params['order']) && is_string($params['order'])) {
            $selectStatement->order($selectStatement->escape($params['order']));
        } elseif ($settings->getBool('authormode_default_alpha_order')) {
            $selectStatement->order([
                'user_displayname',
                'user_firstname',
                'user_name',
            ]);
        }

        $rs = $selectStatement->select();
        if ($rs) {
            $rs->extend(AuthorExtensions::class);
        }

        return $rs ?? MetaRecord::newFromArray([]);
    }
}
