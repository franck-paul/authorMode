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
if (!isset(dcCore::app()->resources['help']['authorMode'])) {
    dcCore::app()->resources['help']['authorMode'] = __DIR__ . '/help/authorMode.html';
}
