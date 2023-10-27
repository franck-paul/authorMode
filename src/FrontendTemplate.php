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

use ArrayObject;
use Dotclear\App;

class FrontendTemplate
{
    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     * @param      string                                            $content   The content
     *
     * @return     string
     */
    public static function Authors(array|ArrayObject $attr, string $content): string
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

        $p = $p === '' ? '$params = null;' . "\n" : '$params = array();' . "\n" . $p;

        return '<?php
if (!App::frontend()->context()->exists("users")) { ' .
            $p .
            'App::frontend()->context()->users = ' . CoreHelper::class . '::getPostsUsers($params); unset($params);' . "\n" .
            ' } ' .
            "?>\n" .
            '<?php while (App::frontend()->context()->users->fetch()) : ?>' . $content . '<?php endwhile; App::frontend()->context()->users = null; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     * @param      string                                            $content   The content
     *
     * @return     string
     */
    public static function AuthorsHeader(array|ArrayObject $attr, string $content): string
    {
        return
            '<?php if (App::frontend()->context()->users->isStart()) : ?>' .
            $content .
            '<?php endif; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     * @param      string                                            $content   The content
     *
     * @return     string
     */
    public static function AuthorsFooter(array|ArrayObject $attr, string $content): string
    {
        return
            '<?php if (App::frontend()->context()->users->isEnd()) : ?>' .
            $content .
            '<?php endif; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorDesc(array|ArrayObject $attr): string
    {
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'App::frontend()->context()->users->user_desc') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorPostsURL(array|ArrayObject $attr): string
    {
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' .
        sprintf($f, 'App::blog()->url().App::url()->getBase("author").
            "/".App::frontend()->context()->users->user_id') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorNbPosts(array|ArrayObject $attr): string
    {
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'App::frontend()->context()->users->nb_post') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorCommonName(array|ArrayObject $attr): string
    {
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'App::frontend()->context()->users->getAuthorCN()') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorDisplayName(array|ArrayObject $attr): string
    {
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'App::frontend()->context()->users->user_displayname') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorFirstName(array|ArrayObject $attr): string
    {
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'App::frontend()->context()->users->user_firstname') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorName(array|ArrayObject $attr): string
    {
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'App::frontend()->context()->users->user_name') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorID(array|ArrayObject $attr): string
    {
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'App::frontend()->context()->users->user_id') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorEmail(array|ArrayObject $attr): string
    {
        $p = 'true';
        if (isset($attr['spam_protected']) && !$attr['spam_protected']) {
            $p = 'false';
        }

        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'App::frontend()->context()->users->getAuthorEmail(' . $p . ')') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorLink(array|ArrayObject $attr): string
    {
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'App::frontend()->context()->users->getAuthorLink()') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorURL(array|ArrayObject $attr): string
    {
        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'App::frontend()->context()->users->user_url') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function AuthorFeedURL(array|ArrayObject $attr): string
    {
        $type = empty($attr['type']) ? 'rss2' : (string) $attr['type'];

        if (!preg_match('#^(rss2|atom)$#', $type)) {
            $type = 'rss2';
        }

        $f = App::frontend()->template()->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'App::blog()->url().App::url()->getBase("author_feed")."/".' .
            'rawurlencode(App::frontend()->context()->users->user_id)."/' . $type . '"') . '; ?>';
    }
}
