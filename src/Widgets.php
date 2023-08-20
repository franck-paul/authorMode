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

use Dotclear\Plugin\widgets\WidgetsStack;

class Widgets
{
    public static function initWidgets(WidgetsStack $w)
    {
        $w
            ->create('authors', __('AuthorMode: authors'), FrontendWidgets::authors(...), null, __('List of authors'))
            ->addTitle(__('Authors'))
            ->setting('postcount', __('With entries counts'), 0, 'check')
            ->setting('allauthors', __('With link to all authors'), 1, 'check')
            ->addHomeOnly()
            ->addContentOnly()
            ->addClass()
            ->addOffline();
    }
}
