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
use Dotclear\Core\Frontend\Utility;

class FrontendBehaviors
{
    public static function block(): string
    {
        $args = func_get_args();
        array_shift($args);

        if ($args[0] == 'Comments') {
            return '<?php if (App::frontend()->context()->exists("users")) { ' .
                "@\$params['sql'] .= \"AND P.user_id = '\".App::frontend()->context()->users->user_id.\"' \";" .
                "} ?>\n";
        }

        return '';
    }

    public static function addTplPath(): string
    {
        $tplset = App::themes()->moduleInfo(App::blog()->settings()->system->theme, 'tplset');
        if (!empty($tplset) && is_dir(implode(DIRECTORY_SEPARATOR, [My::path(), Utility::TPL_ROOT, $tplset]))) {
            App::frontend()->template()->appendPath(implode(DIRECTORY_SEPARATOR, [My::path(), Utility::TPL_ROOT, $tplset]));
        } else {
            App::frontend()->template()->appendPath(implode(DIRECTORY_SEPARATOR, [My::path(), Utility::TPL_ROOT, App::config()->defaultTplset()]));
        }

        return '';
    }

    public static function publicBreadcrumb(string $context): string
    {
        if ($context == 'author') {
            return __('Author\'s page');
        } elseif ($context == 'authors') {
            return __('List of authors');
        }

        return '';
    }

    public static function publicHeadContent(): string
    {
        echo
        My::cssLoad('authorMode.css');

        return '';
    }
}
