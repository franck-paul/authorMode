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

class Frontend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = My::checkContext(My::FRONTEND);

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        dcCore::app()->tpl->addValue('AuthorCommonName', [FrontendTemplate::class, 'AuthorCommonName']);
        dcCore::app()->tpl->addValue('AuthorDisplayName', [FrontendTemplate::class, 'AuthorDisplayName']);
        dcCore::app()->tpl->addValue('AuthorEmail', [FrontendTemplate::class, 'AuthorEmail']);
        dcCore::app()->tpl->addValue('AuthorID', [FrontendTemplate::class, 'AuthorID']);
        dcCore::app()->tpl->addValue('AuthorLink', [FrontendTemplate::class, 'AuthorLink']);
        dcCore::app()->tpl->addValue('AuthorName', [FrontendTemplate::class, 'AuthorName']);
        dcCore::app()->tpl->addValue('AuthorFirstName', [FrontendTemplate::class, 'AuthorFirstName']);
        dcCore::app()->tpl->addValue('AuthorURL', [FrontendTemplate::class, 'AuthorURL']);
        dcCore::app()->tpl->addValue('AuthorDesc', [FrontendTemplate::class, 'AuthorDesc']);
        dcCore::app()->tpl->addValue('AuthorPostsURL', [FrontendTemplate::class, 'AuthorPostsURL']);
        dcCore::app()->tpl->addValue('AuthorNbPosts', [FrontendTemplate::class, 'AuthorNbPosts']);
        dcCore::app()->tpl->addValue('AuthorFeedURL', [FrontendTemplate::class, 'AuthorFeedURL']);

        dcCore::app()->tpl->addBlock('Authors', [FrontendTemplate::class, 'Authors']);
        dcCore::app()->tpl->addBlock('AuthorsHeader', [FrontendTemplate::class, 'AuthorsHeader']);
        dcCore::app()->tpl->addBlock('AuthorsFooter', [FrontendTemplate::class, 'AuthorsFooter']);

        dcCore::app()->addBehaviors([
            'templateBeforeBlockV2'  => [FrontendBehaviors::class, 'block'],
            'publicBeforeDocumentV2' => [FrontendBehaviors::class, 'addTplPath'],
            'publicBreadcrumb'       => [FrontendBehaviors::class, 'publicBreadcrumb'],
            'publicHeadContent'      => [FrontendBehaviors::class, 'publicHeadContent'],

            'initWidgets' => [Widgets::class, 'initWidgets'],
        ]);

        return true;
    }
}
