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
 * English strings
 *
 * @package    block
 * @subpackage remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
//block_remlab_manager.php:
$string['pluginname'] = 'Remlab manager';
$string['block_title'] = 'Remote Laboratories Manager';
$string['configure_existing_experience'] = 'Configure the selected experience';
$string['delete_existing_experience'] = 'Delete the selected experience';
$string['or'] = 'or';
$string['configure_new_experience'] = 'Configure new experience';

//settings.php:
$string['default_communication_set'] = "Communication settings. (Only important if you are also using Sarlab)";
$string['sarlab_IP'] = "Name and IP address of the Sarlab server(s)";
$string['sarlab_IP_description'] = "If you are using Sarlab (a system that manages connections to remote laboratories resources), you need to provide the IP address of the server that runs the Sarlab system you want to use. Otherwise, this value will not be used, so you can leave the default value. If you have more than one Sarlab server (for example, one at 127.0.0.1 and a second one at 127.0.0.2), insert the IP addresses separated by semicolons: 127.0.0.1;127.0.0.2. Additionally, you can provide a name in order to identify each Sarlab server: 'Sarlab Madrid'127.0.0.1;'Sarlab Huelva'127.0.0.2";
$string['sarlab_port'] = "Sarlab communication port(s)";
$string['sarlab_port_description'] = "If you are using Sarlab (a system that manages connections to remote laboratories resources), you need to provide a valid port for establishing the communications with the Sarlab server. Otherwise, this value will not be used, so you can leave the default value. If you have more than one Sarlab server (for example, one using port 443 and a second one also using port 443), insert the values separated by semicolons: 443;443";
$string['sarlab_enc_key'] = "Encryption key to communicate with Sarlab";
$string['sarlab_enc_key_description'] = "If you are using Sarlab (a system that manages connections to remote laboratories resources), you need to provide the 16 characters long encryption key for encrypting/decrypting the communications with the Sarlab server (this key should be the same as configured in the Sarlab server). Otherwise, this value will not be used, so you can leave the default value.";

//edit_form.php:
$string['sarlab_header'] = 'Configure Sarlab for this block instance';
$string['use_sarlab'] = 'Use Sarlab in collaborative sessions?';

//Capabilities
$string['remlab_manager:addinstance'] = 'Add a new Remlab Manager block';
$string['remlab_manager:myaddinstance'] = 'Add a new Remlab Manager block to My home';

//view.php
$string['inserterror'] = 'Error while trying to save the configuration for the remote lab experience';
$string['confirm_deletion'] = 'Are you sure you want to delete the selected experience?';
$string['confirm_delete_button'] = 'Yes';
$string['cancel_delete_button'] = 'No';

//simplehtml_form.php
$string['configure_lab'] = 'Configure remote lab';

$string['sarlab'] = "Using Sarlab?";
$string['sarlab_help'] = "Only select yes if you are using Sarlab; a system that manages connections to remote laboratories resources";

$string['sarlab_instance'] = "Sarlab server for this lab";
$string['sarlab_instance_help'] = "The order corresponds to the one used for the values in the sarlab_IP and sarlab_port variables configured at the ejsapp settings page";

$string['sarlab_collab'] = "Use collaborative access provided by Sarlab?";
$string['sarlab_collab_help'] = "Whether you want Sarlab to provide collaborative access to this remote laboratory or not.";

$string['practiceintro'] = 'Practice identifier';
$string['practiceintro_help'] = 'The identifier of the practice you want to label this configuration with.';
$string['practiceintro_required'] = 'WARNING: You need to specify one practice.';

$string['ip_lab'] = 'IP address';
$string['ip_lab_help'] = "Experimental system IP address. If you are using Sarlab, you don't have to worry about this parameter.";
$string['ip_lab_required'] = 'WARNING: You need to provide a valid IP address.';

$string['port'] = 'Port';
$string['port_help'] = "The port used to establish the communication. If you are using Sarlab, you don't have to worry about this parameter.";
$string['port_required'] = 'WARNING: You need to provide a valid port.';

$string['active'] = 'Available';
$string['active_help'] = 'Whether this remote lab is operative at the moment or not.';

$string['free_access'] = 'Free access';
$string['free_access_help'] = 'Enable free access mode (no need to make a booking) for this remote lab.';

$string['slotsduration'] = 'Slots duration (minutes)';
$string['slotsduration_help'] = 'Duration of the time slots (in minutes) in which users will be able to work with this lab.';

$string['totalslots'] = 'Total slots of work';
$string['totalslots_help'] = 'Total amount of maximum slots each student will be allowed to work with this lab.';
$string['weeklyslots'] = 'Weekly slots of work';
$string['weeklyslots_help'] = 'Weekly amount of maximum slots each student will be allowed to work with this lab.';
$string['dailyslots'] = 'Daily slots of work';
$string['dailyslots_help'] = 'Daily amount of maximum slots each student will be allowed to work with this lab. Also, if the lab is in free access mode, it determines the maximum number of consecutive time slots in which the lab can be used.';

$string['reboottime'] = 'Idle time (minutes)';
$string['reboottime_help'] = 'Minimum elapsed time (in minutes) between someone stops using a remote lab and somebody else can start using it. Useful for giving time to the remote lab to reboot and/or turn back to its initial state.';

$string['sarlab_exp_conf'] = 'Configuration of the Sarlab experience';

$string['ip_server'] = 'Lab server IP address';
$string['ip_server_help'] = 'Set the lab\'s IP address to connect to.';

$string['port_server'] = 'Lab server port';
$string['port_server_help'] = 'Set the port in the lab server side.';

$string['ip_client'] = 'Client IP address';
$string['ip_client_help'] = 'Set the IP address from which the client will connect.';

$string['port_client'] = 'Client port';
$string['port_client_help'] = 'Set the port in the client side.';

$string['lab_power_board'] = 'Power distribution unit';
$string['lab_power_board_help'] = 'The device that powers the equipment used by this lab.';

$string['lab_power_outputs'] = 'Powering outputs';
$string['lab_power_outputs_help'] = 'The power distribution unit\'s outputs that need activation.';