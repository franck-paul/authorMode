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
use dcNamespace;
use Dotclear\App;
use Dotclear\Core\Backend\Notices;
use Dotclear\Core\Backend\Page;
use Dotclear\Core\Process;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Text as Txt;
use Exception;

class Manage extends Process
{
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
            try {
                $active = (empty($_POST['active'])) ? false : true;
                if (trim((string) $_POST['url_author']) == '') {
                    $url_author = 'author';
                } else {
                    $url_author = Txt::str2URL(trim((string) $_POST['url_author']));
                }
                if (trim((string) $_POST['url_authors']) == '') {
                    $url_authors = 'authors';
                } else {
                    $url_authors = Txt::str2URL(trim((string) $_POST['url_authors']));
                }
                $posts_only  = (empty($_POST['posts_only'])) ? false : true;
                $alpha_order = (empty($_POST['alpha_order'])) ? false : true;

                $settings = My::settings();

                $settings->put('authormode_active', $active, dcNamespace::NS_BOOL);
                $settings->put('authormode_url_author', $url_author, dcNamespace::NS_STRING);
                $settings->put('authormode_url_authors', $url_authors, dcNamespace::NS_STRING);
                $settings->put('authormode_default_posts_only', $posts_only, dcNamespace::NS_BOOL);
                $settings->put('authormode_default_alpha_order', $alpha_order, dcNamespace::NS_BOOL);

                App::blog()->triggerBlog();

                Notices::addSuccessNotice(__('Configuration successfully updated.'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id());
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
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

        $settings = My::settings();

        $active      = $settings->authormode_active;
        $url_author  = $settings->authormode_url_author;
        $url_authors = $settings->authormode_url_authors;
        $posts_only  = $settings->authormode_default_posts_only;
        $alpha_order = $settings->authormode_default_alpha_order;

        Page::openModule(My::name());

        echo Page::breadcrumb(
            [
                Html::escapeHTML(App::blog()->name()) => '',
                __('Author Mode')                     => '',
            ]
        );
        echo Notices::getNotices();

        // Form
        echo
        (new Form('authormode_options'))
            ->action(dcCore::app()->admin->getPageURL())
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

        Page::helpBlock('authorMode');

        Page::closeModule();
    }
}
