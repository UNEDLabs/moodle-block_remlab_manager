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
 * Page for configuring the data needed by a remote lab application to access the lab hardware
 *
 * @package    block
 * @subpackage remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once("../../mod/ejsapp/locallib.php");
class simplehtml_form extends moodleform {
    function definition()
    {
        global $CFG, $DB;
        $mform =& $this->_form;
        $mform->addElement('header', 'rem_lab', get_string('rem_lab_conf', 'ejsapp'));
        // First, hidden elements
        $mform->addElement('hidden', 'blockid');
        $mform->setType('blockid', PARAM_INT);
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden', 'editingexperience');
        $mform->setType('editingexperience', PARAM_INT);
        $mform->addElement('hidden', 'originalpracticeintro');
        $mform->setType('originalpracticeintro', PARAM_TEXT);
        $mform->addElement('hidden', 'sarlab_configured', null);
        $mform->setType('sarlab_configured', PARAM_INT);
        // Get data if editing an already existing experience
        $editing_experience = $this->_customdata[0];
        $practiceintro = $this->_customdata[1];
        $rem_lab_data = false;
        if ($editing_experience) {
            $rem_lab_data = $DB->get_record('remlab_manager_conf', array('practiceintro' => $practiceintro));
        }
        // Start adding all the visible elements
        $mform->addElement('selectyesno', 'usingsarlab', get_string('sarlab', 'block_remlab_manager'));
        $mform->addHelpButton('usingsarlab', 'sarlab', 'block_remlab_manager');
        $mform->setDefault('usingsarlab', 0);
        if ($rem_lab_data) {
            $mform->setDefault('usingsarlab', $rem_lab_data->usingsarlab);
        }
        $list_sarlab_IPs = explode(";", $CFG->sarlab_IP);
        $sarlab_configured = 0;
        if ($list_sarlab_IPs[0] != '' && $list_sarlab_IPs[0] != '127.0.0.1' && $list_sarlab_IPs[0] != 'localhost') $sarlab_configured = 1;
        $mform->setDefault('sarlab_configured', $sarlab_configured);
        $mform->disabledIf('usingsarlab', 'sarlab_configured', 'eq', 0);
        $sarlab_instance_options = array();
        for ($i = 0; $i < count($list_sarlab_IPs); $i++) {
            $sarlab_instance_options_temp = $list_sarlab_IPs[$i];
            $init_pos = strpos($sarlab_instance_options_temp, "'");
            $end_pos = strrpos($sarlab_instance_options_temp, "'");
            if (($init_pos === false) || ($init_pos === $end_pos)) {
                array_push($sarlab_instance_options, 'Sarlab server ' . ($i + 1));
            } else {
                array_push($sarlab_instance_options, substr($sarlab_instance_options_temp, $init_pos + 1, $end_pos - $init_pos - 1));
            }
        }
        $mform->addElement('select', 'sarlabinstance', get_string('sarlab_instance', 'block_remlab_manager'), $sarlab_instance_options);
        $mform->addHelpButton('sarlabinstance', 'sarlab_instance', 'block_remlab_manager');
        $mform->disabledIf('sarlabinstance', 'usingsarlab', 'eq', 0);
        if ($rem_lab_data) {
            $mform->setDefault('sarlabinstance', $rem_lab_data->sarlabinstance);
        }
        //Check whether the selected practice uses sarlab or not in order to show or hide the collab access feature
        $mform->addElement('selectyesno', 'sarlabcollab', get_string('sarlab_collab', 'block_remlab_manager'));
        $mform->addHelpButton('sarlabcollab', 'sarlab_collab', 'block_remlab_manager');
        $mform->disabledIf('sarlabcollab', 'usingsarlab', 'eq', 0);
        if ($rem_lab_data) {
            $mform->setDefault('sarlabcollab', $rem_lab_data->sarlabcollab);
        }
        $mform->addElement('text', 'practiceintro', get_string('practiceintro', 'block_remlab_manager'), array('size' => '20'));
        $mform->setType('practiceintro', PARAM_TEXT);
        $mform->addHelpButton('practiceintro', 'practiceintro', 'block_remlab_manager');
        $mform->addRule('practiceintro', get_string('practiceintro_required', 'block_remlab_manager'), 'required');
        if ($rem_lab_data) {
            $mform->setDefault('practiceintro', $rem_lab_data->practiceintro);
        }
        $mform->addElement('text', 'ip', get_string('ip_lab', 'block_remlab_manager'), array('size' => '12'));
        $mform->setType('ip', PARAM_TEXT);
        $mform->addRule('ip', get_string('maximumchars', '', 15), 'maxlength', 15, 'client');
        $mform->addHelpButton('ip', 'ip_lab', 'block_remlab_manager');
        $mform->disabledIf('ip', 'usingsarlab', 'eq', 1);
        if ($rem_lab_data) {
            $mform->setDefault('ip', $rem_lab_data->ip);
        }
        $mform->addElement('text', 'port', get_string('port', 'block_remlab_manager'), array('size' => '2'));
        $mform->setType('port', PARAM_INT);
        $mform->addRule('port', get_string('maximumchars', '', 6), 'maxlength', 6, 'client');
        $mform->addHelpButton('port', 'port', 'block_remlab_manager');
        $mform->disabledIf('port', 'usingsarlab', 'eq', 1);
        if ($rem_lab_data) {
            $mform->setDefault('port', $rem_lab_data->port);
        } else {
            $mform->setDefault('port', '443');
        }
        $mform->addElement('selectyesno', 'active', get_string('active', 'block_remlab_manager'));
        $mform->addHelpButton('active', 'active', 'block_remlab_manager');
        $mform->disabledIf('active', 'is_rem_lab', 'eq', 0);
        if ($rem_lab_data) {
            $mform->setDefault('active', $rem_lab_data->active);
        } else {
            $mform->setDefault('active', '1');
        }
        $mform->addElement('selectyesno', 'free_access', get_string('free_access', 'block_remlab_manager'));
        $mform->addHelpButton('free_access', 'free_access', 'block_remlab_manager');
        if ($rem_lab_data) {
            $mform->setDefault('free_access', $rem_lab_data->free_access);
        } else {
            $mform->setDefault('free_access', '0');
        }
        $mform->addElement('select', 'slotsduration', get_string('slotsduration', 'block_remlab_manager'), array('60', '30', '15', '5', '2'));
        $mform->addHelpButton('slotsduration', 'slotsduration', 'block_remlab_manager');
        if ($rem_lab_data) {
            $mform->setDefault('slotsduration', $rem_lab_data->slotsduration);
        } else {
            $mform->setDefault('slotsduration', '60');
        }
        $mform->addElement('text', 'totalslots', get_string('totalslots', 'block_remlab_manager'), array('size' => '2'));
        $mform->setType('totalslots', PARAM_INT);
        $mform->addRule('totalslots', get_string('maximumchars', '', 5), 'maxlength', 5, 'client');
        $mform->addHelpButton('totalslots', 'totalslots', 'block_remlab_manager');
        $mform->disabledIf('totalslots', 'free_access', 'eq', 1);
        if ($rem_lab_data) {
            $mform->setDefault('totalslots', $rem_lab_data->totalslots);
        } else {
            $mform->setDefault('totalslots', 18);
        }
        $mform->addElement('text', 'weeklyslots', get_string('weeklyslots', 'block_remlab_manager'), array('size' => '2'));
        $mform->setType('weeklyslots', PARAM_INT);
        $mform->addRule('weeklyslots', get_string('maximumchars', '', 3), 'maxlength', 3, 'client');
        $mform->addHelpButton('weeklyslots', 'weeklyslots', 'block_remlab_manager');
        $mform->disabledIf('weeklyslots', 'free_access', 'eq', 1);
        if ($rem_lab_data) {
            $mform->setDefault('weeklyslots', $rem_lab_data->weeklyslots);
        } else {
            $mform->setDefault('weeklyslots', 9);
        }
        $mform->addElement('text', 'dailyslots', get_string('dailyslots', 'block_remlab_manager'), array('size' => '2'));
        $mform->setType('dailyslots', PARAM_INT);
        $mform->addRule('dailyslots', get_string('maximumchars', '', 2), 'maxlength', 2, 'client');
        $mform->addHelpButton('dailyslots', 'dailyslots', 'block_remlab_manager');
        $mform->disabledIf('dailyslots', 'is_rem_lab', 'eq', 0);
        if ($rem_lab_data) {
            $mform->setDefault('dailyslots', $rem_lab_data->dailyslots);
        } else {
            $mform->setDefault('dailyslots', 3);
        }
        $mform->addElement('text', 'reboottime', get_string('reboottime', 'block_remlab_manager'), array('size' => '2'));
        $mform->setType('reboottime', PARAM_INT);
        $mform->addRule('reboottime', get_string('maximumchars', '', 2), 'maxlength', 2, 'client');
        $mform->addHelpButton('reboottime', 'reboottime', 'block_remlab_manager');
        if ($rem_lab_data) {
            $mform->setDefault('reboottime', $rem_lab_data->reboottime);
        } else {
            $mform->setDefault('reboottime', 2);
        }
        if ($sarlab_configured) {
            // Sarlab experience configuration
            $mform->addElement('header', 'sarlab', get_string('sarlab_exp_conf', 'block_remlab_manager'));
            //TODO: if editing an existing SARLAB experience, get information of that experience from the Sarlab Server
            $experience_sarlab_info = null;
            if ($editing_experience) {
            }
            $varsarray = array();
            $varsarray[] = $mform->createElement('text', 'ip_client', get_string('ip_client', 'block_remlab_manager'), array('size' => '15'));
            $varsarray[] = $mform->createElement('text', 'port_client', get_string('port_client', 'block_remlab_manager'), array('size' => '3'));
            $varsarray[] = $mform->createElement('text', 'ip_server', get_string('ip_server', 'block_remlab_manager'), array('size' => '15'));
            $varsarray[] = $mform->createElement('text', 'port_server', get_string('port_server', 'block_remlab_manager'), array('size' => '3'));
            $repeateloptions = array();
            $repeateloptions['ip_client']['disabledif'] = array('usingsarlab', 'eq', 0);
            $repeateloptions['ip_client']['type'] = PARAM_TEXT;
            $repeateloptions['ip_client']['helpbutton'] = array('ip_client', 'block_remlab_manager');
            $repeateloptions['ip_client']['rule'] = array(get_string('maximumchars', '', 4), 'maxlength', 15, 'client');
            if ($experience_sarlab_info) {
                $repeateloptions['ip_client']['default'] = $experience_sarlab_info->reboottime;
            } else {
                $repeateloptions['ip_client']['default'] = '127.0.0.1';
            }
            $repeateloptions['port_client']['disabledif'] = array('usingsarlab', 'eq', 0);
            $repeateloptions['port_client']['type'] = PARAM_INT;
            $repeateloptions['port_client']['helpbutton'] = array('port_client', 'block_remlab_manager');
            $repeateloptions['port_client']['rule'] = 'numeric'; //array('port_client', get_string('maximumchars', '', 4), 'maxlength', 4, 'client');
            if ($experience_sarlab_info) {
                $repeateloptions['port_client']['default'] = $experience_sarlab_info->reboottime;
            } else {
                $repeateloptions['port_client']['default'] = '8081';
            }
            $repeateloptions['ip_server']['disabledif'] = array('usingsarlab', 'eq', 0);
            $repeateloptions['ip_server']['type'] = PARAM_TEXT;
            $repeateloptions['ip_server']['helpbutton'] = array('ip_server', 'block_remlab_manager');
            $repeateloptions['ip_server']['rule'] = array(get_string('maximumchars', '', 4), 'maxlength', 15, 'client');
            if ($experience_sarlab_info) {
                $repeateloptions['ip_server']['default'] = $experience_sarlab_info->reboottime;
            }
            $repeateloptions['port_server']['disabledif'] = array('usingsarlab', 'eq', 0);
            $repeateloptions['port_server']['type'] = PARAM_INT;
            $repeateloptions['port_server']['helpbutton'] = array('port_server', 'block_remlab_manager');
            $repeateloptions['port_server']['rule'] = 'numeric'; //array('port_server', get_string('maximumchars', '', 4), 'maxlength', 4, 'client');
            if ($experience_sarlab_info) {
                $repeateloptions['port_server']['default'] = $experience_sarlab_info->reboottime;
            }
            $this->repeat_elements($varsarray, 2, $repeateloptions, 'option_repeats', 'option_add_fields', 2, null, true);
            if ($experience_sarlab_info) {
                $lab_power_board = $experience_sarlab_info->power_boards_list;
            } else {
                $lab_power_board = array('APC 1', 'APC 2');
            }
            $select = $mform->addElement('select', 'lab_power_board', get_string('lab_power_board', 'block_remlab_manager'), $lab_power_board);
            $mform->addHelpButton('lab_power_board', 'lab_power_board', 'block_remlab_manager');
            $mform->disabledIf('lab_power_board', 'usingsarlab', 'eq', 0);
            if ($experience_sarlab_info) {
                $select->setSelected($experience_sarlab_info->power_board);
            } else {
                $select->setSelected('APC 1');
            }
            $lab_power_outputs = array('1', '2', '3', '4', '5', '6', '7', '8');
            $select = $mform->addElement('select', 'lab_power_outputs', get_string('lab_power_outputs', 'block_remlab_manager'), $lab_power_outputs);
            $select->setMultiple(true);
            $mform->addHelpButton('lab_power_outputs', 'lab_power_outputs', 'block_remlab_manager');
            $mform->disabledIf('lab_power_outputs', 'usingsarlab', 'eq', 0);
            if ($experience_sarlab_info) {
                $select->setSelected($experience_sarlab_info->power_outputs);
            } else {
                $select->setSelected(array('0', '1'));
            }
        }
        $this->add_action_buttons();
    }
    /**
     * Performs minimal validation on the settings form
     * @param array $data
     * @param array $files
     * @return array $errors
     */
    function validation($data, $files)
    {
        $errors = parent::validation($data, $files);
        if ($data['usingsarlab'] == 0) {
            if (empty($data['ip'])) {
                $errors['ip'] = get_string('ip_lab_required', 'block_remlab_manager');
            }
            if (empty($data['port'])) {
                $errors['port'] = get_string('port_required', 'block_remlab_manager');
            }
        }
        return $errors;
    } // validation
}