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
// The GNU General Public License is available on <http://www.gnu.org/licenses/>
//
// Remlab Manager has been developed by:
//  - Luis de la Torre (1): ldelatorre@dia.uned.es
//
//  (1): Computer Science and Automatic Control, Spanish Open University (UNED),
//       Madrid, Spain

/**
 * File for configuring the block instances (selecting the Sarlab server in charge of managing the remote labs)
 *
 * @package    block
 * @subpackage remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//sarlab_IP,            If one or more SARLAB systems are used for accessing the remote laboratories, the list of their IPs directions must be written here.
//sarlab_port,          If one or more SARLAB systems are used for accessing the remote laboratories, the list of the ports used to connect with them must be written here.
//sarlab_enc_key        If one or more SARLAB systems are used for accessing the remote laboratories, the list of their encoding keys must be written here.

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_heading(
        'remlab_manager/communicationsettings',
        get_string('default_communication_set', 'block_remlab_manager'),
        ''
    ));

    $settings->add(new admin_setting_configtext(
        'sarlab_IP',
        get_string('sarlab_IP', 'block_remlab_manager'),
        get_string('sarlab_IP_description', 'block_remlab_manager'),
        '127.0.0.1',
        PARAM_TEXT,
        '13'
    ));

    $settings->add(new admin_setting_configtext(
        'sarlab_port',
        get_string('sarlab_port', 'block_remlab_manager'),
        get_string('sarlab_port_description', 'block_remlab_manager'),
        443,
        PARAM_TEXT,
        '4'
    ));

    $settings->add(new admin_setting_configtext(
        'sarlab_enc_key',
        get_string('sarlab_enc_key', 'block_remlab_manager'),
        get_string('sarlab_enc_key_description', 'block_remlab_manager'),
        '1234567890123456',
        PARAM_TEXT,
        '30'
    ));
}