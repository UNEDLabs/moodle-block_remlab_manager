<?php
// This file is part of the Moodle block "Remlab Manager"
//
// Remlab Manager is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Remlab Manager is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
//
// Remlab Manager has been developed by:
// - Luis de la Torre: ldelatorre@dia.uned.es
//
// at the Computer Science and Automatic Control, Spanish Open University
// (UNED), Madrid, Spain.

/**
 * File for configuring the block instances (selecting the ENLARGE server in charge of managing the remote labs)
 *
 * @package    block_remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_heading(
        'block_remlab_manager/communicationsettings',
        get_string('default_communication_set', 'block_remlab_manager'),
        ''
    ));

    $settings->add(new admin_setting_configtext(
        'block_remlab_manager/myFrontier_IP',
        get_string('myFrontier_IP', 'block_remlab_manager'),
        get_string('myFrontier_IP_description', 'block_remlab_manager'),
        '127.0.0.1',
        PARAM_TEXT,
        '13'
    ));
}