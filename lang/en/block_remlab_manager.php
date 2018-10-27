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
 * English strings
 *
 * @package    block_remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Strings in block_remlab_manager.php.
$string['pluginname'] = 'Remlab manager';
$string['block_title'] = 'Remote Laboratories Manager';
$string['configure_existing_local_experience'] = 'Configure local experience';
$string['delete_existing_local_experience'] = 'Delete local experience';
$string['or'] = 'or';
$string['configure_new_local_experience'] = 'Create new local experience';
$string['go_to_sarlab'] = 'Go to Sarlab';

// Strings in settings.php.
$string['default_communication_set'] = "Communication settings. (Only important if you are also using Sarlab)";
$string['sarlab_IP'] = "Name and IP address of the Sarlab server(s)";
$string['sarlab_IP_description'] = "If you are using Sarlab (a system that manages connections to remote laboratories resources), you need to provide the IP address of the server that runs the Sarlab system you want to use. Otherwise, this value will not be used, so you can leave the default value. If you have more than one Sarlab server (for example, one at 127.0.0.1 and a second one at 127.0.0.2), insert the IP addresses separated by semicolons: 127.0.0.1;127.0.0.2. Additionally, you can provide a name in order to identify each Sarlab server: 'Sarlab Madrid'127.0.0.1;'Sarlab Huelva'127.0.0.2";
$string['sarlab_port'] = "Sarlab communication port(s)";
$string['sarlab_port_description'] = "If you are using Sarlab (a system that manages connections to remote laboratories resources), you need to provide a valid port for establishing the communications with the Sarlab server. Otherwise, this value will not be used, so you can leave the default value. If you have more than one Sarlab server (for example, one using port 443 and a second one also using port 443), insert the values separated by semicolons: 443;443";

// Strings in edit_form.php.
$string['sarlab_header'] = 'Configure Sarlab for this block instance';
$string['sarlab_lti_url'] = 'URL to the Sarlab LTI activity ';

// Strings for capabilities.
$string['remlab_manager:addinstance'] = 'Add a new Remlab Manager block';
$string['remlab_manager:myaddinstance'] = 'Add a new Remlab Manager block to My home';
$string['remlab_manager:view'] = 'View the Remlab Manager block';

// Strings for new roles.
$string['sarlabdesigner'] = 'Sarlab designer';
$string['sarlabdesigner_desc'] = 'Sarlab designers can create, delete, edit and use Sarlab experiences';
$string['sarlabmanager'] = 'Sarlab manager';
$string['sarlabmanager_desc'] = 'Sarlab managers can edit and use existing Sarlab experiences';

// Strings in view.php.
$string['configure_lab'] = 'Configure remote lab';
$string['inserterror'] = 'Error while trying to save the configuration for the remote lab experience';
$string['confirm_deletion'] = 'Are you sure you want to delete the selected experience?';
$string['confirm_delete_button'] = 'Yes';
$string['cancel_delete_button'] = 'No';

// Strings in tasks.
$string['delete_sarlab_keys'] = 'Delete Sarlab keys';

$string['ping_remote_labs'] = 'Check if remote lab equipments are alive';

$string['mail_subject_lab_not_checkable'] = 'Not Checkable Lab State Alert';
$string['mail_content1_lab_not_checkable'] = 'The state of one of your remote labs (';
$string['mail_content2_lab_not_checkable'] = ' - IP: ';
$string['mail_content3_lab_not_checkable'] = ') could not be checked.';

$string['mail_subject_lab_down'] = 'Lab Down Alert';
$string['mail_content1_lab_down'] = 'One of your previously operative remote labs (';
$string['mail_content2_lab_down'] = ' - IP: ';
$string['mail_content3_lab_down'] = ") has ceased to be accessible. \r\n";
$string['mail_content4_lab_down'] = "A list of the inaccessible or inoperative devices is given below: \r\n";

$string['mail_subject_lab_up'] = 'Lab Up Notice';
$string['mail_content1_lab_up'] = 'One of your previously not accessible remote labs (';
$string['mail_content2_lab_up'] = ' - IP: ';
$string['mail_content3_lab_up'] = ') is operative once again.';

$string['synchronise_sarlab_users'] = 'Synchronise Sarlab users';

$string['refresh_usestate_field'] = 'Refresh the use state of remote labs';

// Strings in simplehtml_form.php.
$string['practiceintro'] = 'Practice identifier';
$string['practiceintro_help'] = 'The identifier of the practice you want to label this configuration with.';
$string['practiceintro_required'] = 'WARNING: You need to specify one practice.';
$string['existing_practice_id'] = 'WARNING: This practice identifier already exists. Choose a different name.';

$string['ip_lab'] = 'IP address';
$string['ip_lab_help'] = "Experimental system IP address.";
$string['ip_lab_required'] = 'WARNING: You need to provide a valid IP address.';

$string['port'] = 'Port';
$string['port_help'] = "The port used to establish the communication.";
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

//Privacy
$string['privacy:metadata'] = 'The Remlab Manager block only provides configuration options for remote lab experiences.';