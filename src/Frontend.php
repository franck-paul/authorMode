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
use Dotclear\Core\Process;

class Frontend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::FRONTEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        $settings = My::settings();
        if (!(bool) $settings->authormode_active) {
            return false;
        }

        dcCore::app()->tpl->addValue('AuthorCommonName', FrontendTemplate::AuthorCommonName(...));
        dcCore::app()->tpl->addValue('AuthorDisplayName', FrontendTemplate::AuthorDisplayName(...));
        dcCore::app()->tpl->addValue('AuthorEmail', FrontendTemplate::AuthorEmail(...));
        dcCore::app()->tpl->addValue('AuthorID', FrontendTemplate::AuthorID(...));
        dcCore::app()->tpl->addValue('AuthorLink', FrontendTemplate::AuthorLink(...));
        dcCore::app()->tpl->addValue('AuthorName', FrontendTemplate::AuthorName(...));
        dcCore::app()->tpl->addValue('AuthorFirstName', FrontendTemplate::AuthorFirstName(...));
        dcCore::app()->tpl->addValue('AuthorURL', FrontendTemplate::AuthorURL(...));
        dcCore::app()->tpl->addValue('AuthorDesc', FrontendTemplate::AuthorDesc(...));
        dcCore::app()->tpl->addValue('AuthorPostsURL', FrontendTemplate::AuthorPostsURL(...));
        dcCore::app()->tpl->addValue('AuthorNbPosts', FrontendTemplate::AuthorNbPosts(...));
        dcCore::app()->tpl->addValue('AuthorFeedURL', FrontendTemplate::AuthorFeedURL(...));

        dcCore::app()->tpl->addBlock('Authors', FrontendTemplate::Authors(...));
        dcCore::app()->tpl->addBlock('AuthorsHeader', FrontendTemplate::AuthorsHeader(...));
        dcCore::app()->tpl->addBlock('AuthorsFooter', FrontendTemplate::AuthorsFooter(...));

        dcCore::app()->addBehaviors([
            'templateBeforeBlockV2'  => FrontendBehaviors::block(...),
            'publicBeforeDocumentV2' => FrontendBehaviors::addTplPath(...),
            'publicBreadcrumb'       => FrontendBehaviors::publicBreadcrumb(...),
            'publicHeadContent'      => FrontendBehaviors::publicHeadContent(...),

            'initWidgets' => Widgets::initWidgets(...),
        ]);

        return true;
    }
}
