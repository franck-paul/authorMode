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

        App::frontend()->template()->addValue('AuthorCommonName', FrontendTemplate::AuthorCommonName(...));
        App::frontend()->template()->addValue('AuthorDisplayName', FrontendTemplate::AuthorDisplayName(...));
        App::frontend()->template()->addValue('AuthorEmail', FrontendTemplate::AuthorEmail(...));
        App::frontend()->template()->addValue('AuthorID', FrontendTemplate::AuthorID(...));
        App::frontend()->template()->addValue('AuthorLink', FrontendTemplate::AuthorLink(...));
        App::frontend()->template()->addValue('AuthorName', FrontendTemplate::AuthorName(...));
        App::frontend()->template()->addValue('AuthorFirstName', FrontendTemplate::AuthorFirstName(...));
        App::frontend()->template()->addValue('AuthorURL', FrontendTemplate::AuthorURL(...));
        App::frontend()->template()->addValue('AuthorDesc', FrontendTemplate::AuthorDesc(...));
        App::frontend()->template()->addValue('AuthorPostsURL', FrontendTemplate::AuthorPostsURL(...));
        App::frontend()->template()->addValue('AuthorNbPosts', FrontendTemplate::AuthorNbPosts(...));
        App::frontend()->template()->addValue('AuthorFeedURL', FrontendTemplate::AuthorFeedURL(...));

        App::frontend()->template()->addBlock('Authors', FrontendTemplate::Authors(...));
        App::frontend()->template()->addBlock('AuthorsHeader', FrontendTemplate::AuthorsHeader(...));
        App::frontend()->template()->addBlock('AuthorsFooter', FrontendTemplate::AuthorsFooter(...));

        App::behavior()->addBehaviors([
            'templateBeforeBlockV2'  => FrontendBehaviors::templateBeforeBlock(...),
            'publicBeforeDocumentV2' => FrontendBehaviors::addTplPath(...),
            'publicBreadcrumb'       => FrontendBehaviors::publicBreadcrumb(...),
            'publicHeadContent'      => FrontendBehaviors::publicHeadContent(...),

            'initWidgets' => Widgets::initWidgets(...),
        ]);

        return true;
    }
}
