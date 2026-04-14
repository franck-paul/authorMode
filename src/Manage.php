<?php

/**
 * @brief authorMode, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul contact@open-time.net
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\authorMode;

use Dotclear\App;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Process\TraitProcess;
use Dotclear\Helper\Text as Txt;
use Exception;

class Manage
{
    use TraitProcess;

    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        return self::status(My::checkContext(My::MANAGE));
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        if (!empty($_POST['saveconfig'])) {
            // Post data helpers
            $_Bool = fn (string $name): bool => !empty($_POST[$name]);
            $_Str  = fn (string $name, string $default = ''): string => isset($_POST[$name]) && is_string($val = $_POST[$name]) ? $val : $default;

            try {
                $active      = $_Bool('active');
                $url_author  = $_Str('url_author');
                $url_authors = $_Str('url_authors');
                $posts_only  = $_Bool('posts_only');
                $alpha_order = $_Bool('alpha_order');

                $url_author  = trim($url_author)  === '' ? 'author' : Txt::str2URL(trim($url_author));
                $url_authors = trim($url_authors) === '' ? 'authors' : Txt::str2URL(trim($url_authors));

                $settings = My::settings();

                $settings->put('authormode_active', $active, App::blogWorkspace()::NS_BOOL);
                $settings->put('authormode_url_author', $url_author, App::blogWorkspace()::NS_STRING);
                $settings->put('authormode_url_authors', $url_authors, App::blogWorkspace()::NS_STRING);
                $settings->put('authormode_default_posts_only', $posts_only, App::blogWorkspace()::NS_BOOL);
                $settings->put('authormode_default_alpha_order', $alpha_order, App::blogWorkspace()::NS_BOOL);

                App::blog()->triggerBlog();

                App::backend()->notices()->addSuccessNotice(__('Configuration successfully updated.'));
                My::redirect();
            } catch (Exception $e) {
                App::error()->add($e->getMessage());
            }
        }

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        // Variable data helpers
        $_Bool = fn (mixed $var): bool => (bool) $var;
        $_Str  = fn (mixed $var, string $default = ''): string => $var !== null && is_string($val = $var) ? $val : $default;

        $settings = My::settings();

        $active      = $_Bool($settings->authormode_active);
        $url_author  = $_Str($settings->authormode_url_author);
        $url_authors = $_Str($settings->authormode_url_authors);
        $posts_only  = $_Bool($settings->authormode_default_posts_only);
        $alpha_order = $_Bool($settings->authormode_default_alpha_order);

        App::backend()->page()->openModule(My::name());

        echo App::backend()->page()->breadcrumb(
            [
                Html::escapeHTML(App::blog()->name()) => '',
                __('Author Mode')                     => '',
            ]
        );
        echo App::backend()->notices()->getNotices();

        // Form
        echo
        (new Form('authormode_options'))
            ->action(App::backend()->getPageURL())
            ->method('post')
            ->fields([
                (new Para())->items([
                    (new Checkbox('active', $active))
                        ->value(1)
                        ->label((new Label(__('Enable authorMode'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Text('h3', __('Advanced options'))),
                (new Text('h4', __('URLs prefixes'))),
                (new Para())->items([
                    (new Input('url_author'))
                        ->size(60)
                        ->maxlength(256)
                        ->value(Html::escapeHTML($url_author))
                        ->label((new Label(__('URL author:'), Label::INSIDE_TEXT_BEFORE))),
                ]),
                (new Para())->items([
                    (new Input('url_authors'))
                        ->size(60)
                        ->maxlength(256)
                        ->value(Html::escapeHTML($url_authors))
                        ->label((new Label(__('URL authors:'), Label::INSIDE_TEXT_BEFORE))),
                ]),
                (new Text('h4', __('List options'))),
                (new Para())->items([
                    (new Checkbox('posts_only', $posts_only))
                        ->value(1)
                        ->label((new Label(__('List only authors of standard posts'), Label::INSIDE_TEXT_AFTER))),
                ]),
                (new Para())->items([
                    (new Checkbox('alpha_order', $alpha_order))
                        ->value(1)
                        ->label((new Label(__('Sort list (alphabetical order)'), Label::INSIDE_TEXT_AFTER))),
                ]),

                // Submit
                (new Para())->items([
                    (new Submit(['saveconfig']))
                        ->value(__('Save configuration')),
                    ... My::hiddenFields(),
                ]),
            ])
        ->render();

        App::backend()->page()->helpBlock('authorMode');

        App::backend()->page()->closeModule();
    }
}
