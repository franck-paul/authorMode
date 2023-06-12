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

use dcAdmin;
use dcCore;
use dcNsProcess;

class Backend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = My::checkContext(My::BACKEND);

        // dead but useful code, in order to have translations
        __('authorMode') . __('Author Mode');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(
            __('Authors'),
            My::makeUrl(),
            My::icons(),
            preg_match(My::urlScheme(), $_SERVER['REQUEST_URI']),
            My::checkContext(My::MENU)
        );

        dcCore::app()->addBehaviors([
            'adminUserHeaders'             => [BackendBehaviors::class, 'adminAuthorHeaders'],
            'adminPreferencesHeaders'      => [BackendBehaviors::class, 'adminAuthorHeaders'],
            'adminUserForm'                => [BackendBehaviors::class, 'adminUserForm'],        // user.php
            'adminPreferencesFormV2'       => [BackendBehaviors::class, 'adminPreferencesForm'], //preferences.php
            'adminBeforeUserCreate'        => [BackendBehaviors::class, 'adminBeforeUserUpdate'],
            'adminBeforeUserUpdate'        => [BackendBehaviors::class, 'adminBeforeUserUpdate'],
            'adminBeforeUserOptionsUpdate' => [BackendBehaviors::class, 'adminBeforeUserUpdate'], //preferences.php
            'adminDashboardFavoritesV2'    => [BackendBehaviors::class, 'authorModeDashboardFavorites'],
        ]);

        if (My::checkContext(My::WIDGETS)) {
            dcCore::app()->addBehaviors([
                'initWidgets' => [Widgets::class, 'initWidgets'],
            ]);
        }

        return true;
    }
}
