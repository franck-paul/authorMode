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
use Dotclear\Helper\Process\TraitProcess;

class Backend
{
    use TraitProcess;

    public static function init(): bool
    {
        // dead but useful code, in order to have translations
        __('authorMode');
        __('Author Mode');

        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        My::addBackendMenuItem(App::backend()->menus()::MENU_BLOG);

        App::behavior()->addBehaviors([
            'adminUserHeaders'             => BackendBehaviors::adminAuthorHeaders(...),
            'adminPreferencesHeaders'      => BackendBehaviors::adminAuthorHeaders(...),
            'adminUserForm'                => BackendBehaviors::adminUserForm(...),        // user.php
            'adminPreferencesFormV2'       => BackendBehaviors::adminPreferencesForm(...), //preferences.php
            'adminBeforeUserCreate'        => BackendBehaviors::adminBeforeUserUpdate(...),
            'adminBeforeUserUpdate'        => BackendBehaviors::adminBeforeUserUpdate(...),
            'adminBeforeUserOptionsUpdate' => BackendBehaviors::adminBeforeUserUpdate(...), //preferences.php
            'adminDashboardFavoritesV2'    => BackendBehaviors::authorModeDashboardFavorites(...),
        ]);

        if (My::checkContext(My::WIDGETS)) {
            App::behavior()->addBehaviors([
                'initWidgets' => Widgets::initWidgets(...),
            ]);
        }

        return true;
    }
}
