<?php
/**
 * @brief authorMode, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author xave, Pierre Van Glabeke, Franck Paul
 *
 * @copyright GPL-2.0
 */
if (!defined('DC_RC_PATH')) {
    return;
}

if (!dcCore::app()->blog->settings->authormode->authormode_active) {
    return;
}

require_once __DIR__ . '/_widgets.php';

class behaviorAuthorMode
{
    public static function block()
    {
        $args = func_get_args();
        array_shift($args);

        if ($args[0] == 'Comments') {
            return '<?php if (dcCore::app()->ctx->exists("users")) { ' .
                "@\$params['sql'] .= \"AND P.user_id = '\".dcCore::app()->ctx->users->user_id.\"' \";" .
//                "unset(\$params['limit']); " .
                "} ?>\n";
        }
    }

    public static function addTplPath()
    {
        $tplset = dcCore::app()->themes->moduleInfo(dcCore::app()->blog->settings->system->theme, 'tplset');
        if (!empty($tplset) && is_dir(__DIR__ . '/' . dcPublic::TPL_ROOT . '/' . $tplset)) {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), __DIR__ . '/' . dcPublic::TPL_ROOT . '/' . $tplset);
        } else {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), __DIR__ . '/' . dcPublic::TPL_ROOT . '/' . DC_DEFAULT_TPLSET);
        }
    }
}

dcCore::app()->addBehavior('templateBeforeBlockV2', [behaviorAuthorMode::class, 'block']);
dcCore::app()->addBehavior('publicBeforeDocumentV2', [behaviorAuthorMode::class, 'addTplPath']);

class tplAuthor
{
    public static function Authors($attr, $content)
    {
        $p = '';
        if (isset($attr['post_type'])) {
            $p .= "\$params['post_type'] = '" . addslashes($attr['post_type']) . "';\n";
        }
        if (isset($attr['sortby'])) {
            $order = 'asc';
            switch ($attr['sortby']) {
                case 'id':$sortby = 'user_id';

                    break;
                case 'posts':$sortby = 'nb_post';

                    break;
                case 'name':$sortby = 'user_displayname, user_firstname, user_name';

                    break;
            }
            if (isset($attr['order']) && preg_match('/^(desc|asc)$/i', $attr['order'])) {
                $order = $attr['order'];
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
            'dcCore::app()->ctx->users = authormodeUtils::getPostsUsers($params); unset($params);' . "\n" .
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
        $type = !empty($attr['type']) ? $attr['type'] : 'rss2';

        if (!preg_match('#^(rss2|atom)$#', $type)) {
            $type = 'rss2';
        }

        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, 'dcCore::app()->blog->url.dcCore::app()->url->getBase("author_feed")."/".' .
            'rawurlencode(dcCore::app()->ctx->users->user_id)."/' . $type . '"') . '; ?>';
    }
}

dcCore::app()->tpl->addValue('AuthorCommonName', [tplAuthor::class, 'AuthorCommonName']);
dcCore::app()->tpl->addValue('AuthorDisplayName', [tplAuthor::class, 'AuthorDisplayName']);
dcCore::app()->tpl->addValue('AuthorEmail', [tplAuthor::class, 'AuthorEmail']);
dcCore::app()->tpl->addValue('AuthorID', [tplAuthor::class, 'AuthorID']);
dcCore::app()->tpl->addValue('AuthorLink', [tplAuthor::class, 'AuthorLink']);
dcCore::app()->tpl->addValue('AuthorName', [tplAuthor::class, 'AuthorName']);
dcCore::app()->tpl->addValue('AuthorFirstName', [tplAuthor::class, 'AuthorFirstName']);
dcCore::app()->tpl->addValue('AuthorURL', [tplAuthor::class, 'AuthorURL']);
dcCore::app()->tpl->addValue('AuthorDesc', [tplAuthor::class, 'AuthorDesc']);
dcCore::app()->tpl->addValue('AuthorPostsURL', [tplAuthor::class, 'AuthorPostsURL']);
dcCore::app()->tpl->addValue('AuthorNbPosts', [tplAuthor::class, 'AuthorNbPosts']);
dcCore::app()->tpl->addValue('AuthorFeedURL', [tplAuthor::class, 'AuthorFeedURL']);

dcCore::app()->tpl->addBlock('Authors', [tplAuthor::class, 'Authors']);
dcCore::app()->tpl->addBlock('AuthorsHeader', [tplAuthor::class, 'AuthorsHeader']);
dcCore::app()->tpl->addBlock('AuthorsFooter', [tplAuthor::class, 'AuthorsFooter']);

class urlAuthor extends dcUrlHandlers
{
    public static function Author($args)
    {
        $n = self::getPageNumber($args);

        if ($args == '' && !$n) {
            self::p404();
        } else {
            if ($n) {
                dcCore::app()->public->setPageNumber($n);
            }
            dcCore::app()->ctx->users = authormodeUtils::getPostsUsers($args);

            if (dcCore::app()->ctx->users->isEmpty()) {
                self::p404();
            }

            self::serveDocument('author.html');
        }
        exit;
    }

    public static function Authors($args)
    {
        dcCore::app()->ctx->users = authormodeUtils::getPostsUsers($args);

        if (dcCore::app()->ctx->users->isEmpty()) {
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

        if (preg_match('#^(.+)/(atom|rss2)(/comments)?$#', $args, $m)) {
            $author   = $m[1];
            $type     = $m[2];
            $comments = !empty($m[3]);
        } else {
            self::p404();
        }

        dcCore::app()->ctx->users = authormodeUtils::getPostsUsers($author);

        if (dcCore::app()->ctx->users->isEmpty()) {
            self::p404();
        }

        if ($type == 'atom') {
            $mime = 'application/atom+xml';
        }

        $tpl = $type;
        if ($comments) {
            $tpl .= '-comments';
            dcCore::app()->ctx->nb_comment_per_page = dcCore::app()->blog->settings->system->nb_comment_per_feed;
        } else {
            dcCore::app()->ctx->nb_entry_per_page = dcCore::app()->blog->settings->system->nb_post_per_feed;
            dcCore::app()->ctx->short_feed_items  = dcCore::app()->blog->settings->system->short_feed_items;
        }
        $tpl .= '.xml';

        self::serveDocument($tpl, $mime);
        exit;
    }
}

class authormodeUtils
{
    public static function getPostsUsers($params = null)
    {
        if ($params !== null && is_string($params)) {
            $params = ['author' => $params];
        }

        $strReq = 'SELECT P.user_id, user_name, user_firstname, ' .
        'user_displayname, user_desc, COUNT(P.post_id) as nb_post ' .
        'FROM ' . dcCore::app()->prefix . dcAuth::USER_TABLE_NAME . ' U ' .
        'LEFT JOIN ' . dcCore::app()->prefix . 'post P ON P.user_id = U.user_id ' .
        "WHERE blog_id = '" . dcCore::app()->con->escape(dcCore::app()->blog->id) . "' " .
        'AND P.post_status = ' . dcBlog::POST_PUBLISHED . ' ';

        if (!empty($params['author'])) {
            $strReq .= " AND P.user_id = '" . dcCore::app()->con->escape($params['author']) . "' ";
        }

        if (!empty($params['post_type'])) {
            $strReq .= " AND P.post_type = '" . dcCore::app()->con->escape($params['post_type']) . "' ";
        } elseif (dcCore::app()->blog->settings->authormode->authormode_default_posts_only) {
            $strReq .= " AND P.post_type = 'post' ";
        }

        $strReq .= 'GROUP BY P.user_id, user_name, user_firstname, user_displayname, user_desc ';

        if (!empty($params['order'])) {
            $strReq .= 'ORDER BY ' . dcCore::app()->con->escape($params['order']) . ' ';
        } elseif (dcCore::app()->blog->settings->authormode->authormode_default_alpha_order) {
            $strReq .= 'ORDER BY user_displayname, user_firstname, user_name ';
        }

        try {
            $rs = new dcRecord(dcCore::app()->con->select($strReq));
            $rs->extend('rsAuthor');

            return $rs;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

class extAuthorMode
{
    public static function publicBreadcrumb($context)
    {
        if ($context == 'author') {
            return __('Author\'s page');
        }
    }
}

dcCore::app()->addBehavior('publicBreadcrumb', [extAuthorMode::class, 'publicBreadcrumb']);

class extAuthorsMode
{
    public static function publicBreadcrumb($context)
    {
        if ($context == 'authors') {
            return __('List of authors');
        }
    }
}

dcCore::app()->addBehavior('publicBreadcrumb', [extAuthorsMode::class, 'publicBreadcrumb']);

class publicAuthorMode
{
    public static function publicHeadContent()
    {
        echo
        dcUtils::cssModuleLoad('authorMode/css/authorMode.css');
    }
}

dcCore::app()->addBehavior('publicHeadContent', [publicAuthorMode::class, 'publicHeadContent']);
