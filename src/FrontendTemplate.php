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

class FrontendTemplate
{
    public static function Authors($attr, $content)
    {
        $p = '';
        if (isset($attr['post_type'])) {
            $p .= "\$params['post_type'] = '" . addslashes($attr['post_type']) . "';\n";
        }
        if (isset($attr['sortby'])) {
            $order  = 'asc';
            $sortby = match ($attr['sortby']) {
                'id'    => 'user_id',
                'posts' => 'nb_post',
                'name'  => 'user_displayname, user_firstname, user_name',
                default => null
            };

            if (isset($attr['order']) && preg_match('/^(desc|asc)$/i', (string) $attr['order'])) {
                $order = (string) $attr['order'];
            }
            if (isset($sortby)) {
                $p .= "\$params['order'] = '" . $sortby . ' ' . $order . "';\n";
            }
        }

        if (empty($p)) {
            $p = '$params = null;' . "\n";
        } else {
            $p = '$params = array();' . "\n" . $p;
        }

        return "<?php\n" .
            'if (!dcCore::app()->ctx->exists("users")) { ' .
            $p .
            'dcCore::app()->ctx->users = ' . CoreHelper::class . '::getPostsUsers($params); unset($params);' . "\n" .
            ' } ' .
            "?>\n" .
            '<?php while (dcCore::app()->ctx->users->fetch()) : ?>' . $content . '<?php endwhile; dcCore::app()->ctx->users = null; ?>';
    }

    public static function AuthorsHeader($attr, $content)
    {
        return
            '<?php if (dcCore::app()->ctx->users->isStart()) : ?>' .
            $content .
            '<?php endif; ?>';
    }

    public static function AuthorsFooter($attr, $content)
    {
        return
            '<?php if (dcCore::app()->ctx->users->isEnd()) : ?>' .
            $content .
            '<?php endif; ?>';
    }

    public static function AuthorDesc($attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->users->user_desc') . '; ?>';
    }

    public static function AuthorPostsURL($attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' .
        sprintf($f, 'dcCore::app()->blog->url.dcCore::app()->url->getBase("author").
            "/".dcCore::app()->ctx->users->user_id') . '; ?>';
    }

    public static function AuthorNbPosts($attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->users->nb_post') . '; ?>';
    }

    public static function AuthorCommonName($attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->users->getAuthorCN()') . '; ?>';
    }

    public static function AuthorDisplayName($attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->users->user_displayname') . '; ?>';
    }

    public static function AuthorFirstName($attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->users->user_firstname') . '; ?>';
    }

    public static function AuthorName($attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->users->user_name') . '; ?>';
    }

    public static function AuthorID($attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->users->user_id') . '; ?>';
    }

    public static function AuthorEmail($attr)
    {
        $p = 'true';
        if (isset($attr['spam_protected']) && !$attr['spam_protected']) {
            $p = 'false';
        }

        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->users->getAuthorEmail(' . $p . ')') . '; ?>';
    }

    public static function AuthorLink($attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->users->getAuthorLink()') . '; ?>';
    }

    public static function AuthorURL($attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->users->user_url') . '; ?>';
    }

    public static function AuthorFeedURL($attr)
    {
        $type = !empty($attr['type']) ? (string) $attr['type'] : 'rss2';

        if (!preg_match('#^(rss2|atom)$#', $type)) {
            $type = 'rss2';
        }

        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->blog->url.dcCore::app()->url->getBase("author_feed")."/".' .
            'rawurlencode(dcCore::app()->ctx->users->user_id)."/' . $type . '"') . '; ?>';
    }
}
