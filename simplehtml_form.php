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
 * Page for configuring the data needed by a remote lab application to access the lab hardware
 *
 * @package    block_remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once("{$CFG->libdir}/formslib.php");
require_once("../../mod/ejsapp/locallib.php");

/**
 * Class simplehtml_form
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class simplehtml_form extends moodleform {

    /**
     * Defines the form
     */
    public function definition() {
        global $DB;

        $mform =& $this->_form;
        $mform->addElement('header', 'rem_lab', get_string('rem_lab_conf', 'ejsapp'));

        // First, hidden elements.
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

        // Get data if editing an already existing experience.
        $editing = $this->_customdata[0];
        $practiceintro = $this->_customdata[1];
        $remlab = false;
        if ($editing) {
            $remlab = $DB->get_record('block_remlab_manager_conf', array('practiceintro' => $practiceintro));
        }

        // Start adding all the visible elements.
        $mform->addElement('selectyesno', 'usingsarlab',
            get_string('sarlab', 'block_remlab_manager'));
        $mform->addHelpButton('usingsarlab', 'sarlab', 'block_remlab_manager');
        $mform->setDefault('usingsarlab', 0);
        if ($remlab) {
            $mform->setDefault('usingsarlab', $remlab->usingsarlab);
        }

        $ips = explode(";", get_config('block_remlab_manager', 'sarlab_IP'));
        $configured = 0;
        if ($ips[0] != '' && $ips[0] != '127.0.0.1' && $ips[0] != 'localhost') {
            $configured = 1;
        }
        $mform->setDefault('sarlab_configured', $configured);
        $mform->disabledIf('usingsarlab', 'sarlab_configured', 'eq', 0);
        $options = array();
        for ($i = 0; $i < count($ips); $i++) {
            $tempoptions = $ips[$i];
            $initpos = strpos($tempoptions, "'");
            $endpos = strrpos($tempoptions, "'");
            if (($initpos === false) || ($initpos === $endpos)) {
                array_push($options, 'Sarlab server ' . ($i + 1));
            } else {
                array_push($options, substr($tempoptions, $initpos + 1, $endpos - $initpos - 1));
            }
        }
        $mform->addElement('select', 'sarlabinstance',
            get_string('sarlab_instance', 'block_remlab_manager'), $options);
        $mform->addHelpButton('sarlabinstance', 'sarlab_instance', 'block_remlab_manager');
        $mform->disabledIf('sarlabinstance', 'usingsarlab', 'eq', 0);
        if ($remlab) {
            $mform->setDefault('sarlabinstance', $remlab->sarlabinstance);
        }

        // Check whether the selected practice uses sarlab or not in order to show or hide the collab access feature.
        $mform->addElement('selectyesno', 'sarlabcollab',
            get_string('sarlab_collab', 'block_remlab_manager'));
        $mform->addHelpButton('sarlabcollab', 'sarlab_collab', 'block_remlab_manager');
        $mform->disabledIf('sarlabcollab', 'usingsarlab', 'eq', 0);
        if ($remlab) {
            $mform->setDefault('sarlabcollab', $remlab->sarlabcollab);
        }

        $mform->addElement('text', 'practiceintro',
            get_string('practiceintro', 'block_remlab_manager'), array('size' => '20'));
        $mform->setType('practiceintro', PARAM_TEXT);
        $mform->addHelpButton('practiceintro', 'practiceintro', 'block_remlab_manager');
        $mform->addRule('practiceintro',
            get_string('practiceintro_required', 'block_remlab_manager'), 'required');
        if ($remlab) {
            $mform->setDefault('practiceintro', $remlab->practiceintro);
        }

        $mform->addElement('text', 'ip',
            get_string('ip_lab', 'block_remlab_manager'), array('size' => '12'));
        $mform->setType('ip', PARAM_TEXT);
        $mform->addRule('ip',
            get_string('maximumchars', '', 15), 'maxlength', 15, 'client');
        $mform->addHelpButton('ip', 'ip_lab', 'block_remlab_manager');
        $mform->disabledIf('ip', 'usingsarlab', 'eq', 1);
        if ($remlab) {
            $mform->setDefault('ip', $remlab->ip);
        }

        $mform->addElement('text', 'port',
            get_string('port', 'block_remlab_manager'), array('size' => '2'));
        $mform->setType('port', PARAM_INT);
        $mform->addRule('port',
            get_string('maximumchars', '', 6), 'maxlength', 6, 'client');
        $mform->addHelpButton('port', 'port', 'block_remlab_manager');
        $mform->disabledIf('port', 'usingsarlab', 'eq', 1);
        if ($remlab) {
            $mform->setDefault('port', $remlab->port);
        } else {
            $mform->setDefault('port', '443');
        }

        $mform->addElement('selectyesno', 'active',
            get_string('active', 'block_remlab_manager'));
        $mform->addHelpButton('active', 'active', 'block_remlab_manager');
        $mform->disabledIf('active', 'is_rem_lab', 'eq', 0);
        if ($remlab) {
            $mform->setDefault('active', $remlab->active);
        } else {
            $mform->setDefault('active', '1');
        }

        $mform->addElement('selectyesno', 'free_access',
            get_string('free_access', 'block_remlab_manager'));
        $mform->addHelpButton('free_access', 'free_access', 'block_remlab_manager');
        if ($remlab) {
            $mform->setDefault('free_access', $remlab->free_access);
        } else {
            $mform->setDefault('free_access', '0');
        }

        $mform->addElement('select', 'slotsduration',
            get_string('slotsduration', 'block_remlab_manager'), array('60', '30', '15', '5', '2'));
        $mform->addHelpButton('slotsduration', 'slotsduration', 'block_remlab_manager');
        if ($remlab) {
            $mform->setDefault('slotsduration', $remlab->slotsduration);
        } else {
            $mform->setDefault('slotsduration', '60');
        }

        $mform->addElement('text', 'totalslots',
            get_string('totalslots', 'block_remlab_manager'), array('size' => '2'));
        $mform->setType('totalslots', PARAM_INT);
        $mform->addRule('totalslots',
            get_string('maximumchars', '', 5), 'maxlength', 5, 'client');
        $mform->addHelpButton('totalslots', 'totalslots', 'block_remlab_manager');
        $mform->disabledIf('totalslots', 'free_access', 'eq', 1);
        if ($remlab) {
            $mform->setDefault('totalslots', $remlab->totalslots);
        } else {
            $mform->setDefault('totalslots', 18);
        }

        $mform->addElement('text', 'weeklyslots',
            get_string('weeklyslots', 'block_remlab_manager'), array('size' => '2'));
        $mform->setType('weeklyslots', PARAM_INT);
        $mform->addRule('weeklyslots',
            get_string('maximumchars', '', 3), 'maxlength', 3, 'client');
        $mform->addHelpButton('weeklyslots', 'weeklyslots', 'block_remlab_manager');
        $mform->disabledIf('weeklyslots', 'free_access', 'eq', 1);
        if ($remlab) {
            $mform->setDefault('weeklyslots', $remlab->weeklyslots);
        } else {
            $mform->setDefault('weeklyslots', 9);
        }

        $mform->addElement('text', 'dailyslots',
            get_string('dailyslots', 'block_remlab_manager'), array('size' => '2'));
        $mform->setType('dailyslots', PARAM_INT);
        $mform->addRule('dailyslots',
            get_string('maximumchars', '', 2), 'maxlength', 2, 'client');
        $mform->addHelpButton('dailyslots', 'dailyslots', 'block_remlab_manager');
        $mform->disabledIf('dailyslots', 'is_rem_lab', 'eq', 0);
        if ($remlab) {
            $mform->setDefault('dailyslots', $remlab->dailyslots);
        } else {
            $mform->setDefault('dailyslots', 3);
        }

        $mform->addElement('text', 'reboottime',
            get_string('reboottime', 'block_remlab_manager'), array('size' => '2'));
        $mform->setType('reboottime', PARAM_INT);
        $mform->addRule('reboottime',
            get_string('maximumchars', '', 2), 'maxlength', 2, 'client');
        $mform->addHelpButton('reboottime', 'reboottime', 'block_remlab_manager');
        if ($remlab) {
            $mform->setDefault('reboottime', $remlab->reboottime);
        } else {
            $mform->setDefault('reboottime', 2);
        }

        if ($configured) {
            // Sarlab experience configuration.
            $mform->addElement('header', 'sarlab',
                get_string('sarlab_exp_conf', 'block_remlab_manager'));
            $experienceinfo = null;

            // If editing an existing SARLAB experience, get information of that experience from the Sarlab Server.
            if ($editing) {
                $experienceinfo = new stdClass;
                $experienceinfo->ip_client = '127.0.0.1';
                $experienceinfo->port_client = '8081';
                $experienceinfo->ip_server = '';
                $experienceinfo->port_server = '';
                $experienceinfo->power_boards_list = array('APC 1', 'APC 2');
                $experienceinfo->power_outputs = array('0', '1');
                //$experienceinfo = get_sarlab_experience_info();
            }

            $varsarray = array();
            $varsarray[] = $mform->createElement('text', 'ip_client',
                get_string('ip_client', 'block_remlab_manager'), array('size' => '15'));
            $varsarray[] = $mform->createElement('text', 'port_client',
                get_string('port_client', 'block_remlab_manager'), array('size' => '3'));
            $varsarray[] = $mform->createElement('text', 'ip_server',
                get_string('ip_server', 'block_remlab_manager'), array('size' => '15'));
            $varsarray[] = $mform->createElement('text', 'port_server',
                get_string('port_server', 'block_remlab_manager'), array('size' => '3'));
            $repeateloptions = array();
            $repeateloptions['ip_client']['disabledif'] = array('usingsarlab', 'eq', 0);
            $repeateloptions['ip_client']['type'] = PARAM_TEXT;
            $repeateloptions['ip_client']['helpbutton'] = array('ip_client', 'block_remlab_manager');
            $repeateloptions['ip_client']['rule'] =
                array(get_string('maximumchars', '', 4), 'maxlength', 15, 'client');
            if ($experienceinfo) {
                $repeateloptions['ip_client']['default'] = $experienceinfo->ip_client;
            } else {
                $repeateloptions['ip_client']['default'] = '127.0.0.1';
            }
            $repeateloptions['port_client']['disabledif'] = array('usingsarlab', 'eq', 0);
            $repeateloptions['port_client']['type'] = PARAM_INT;
            $repeateloptions['port_client']['helpbutton'] = array('port_client', 'block_remlab_manager');
            $repeateloptions['port_client']['rule'] = 'numeric'; // array('port_client', get_string('maximumchars', '', 4), 'maxlength', 4, 'client');
            if ($experienceinfo) {
                $repeateloptions['port_client']['default'] = $experienceinfo->port_client;
            } else {
                $repeateloptions['port_client']['default'] = '8081';
            }
            $repeateloptions['ip_server']['disabledif'] = array('usingsarlab', 'eq', 0);
            $repeateloptions['ip_server']['type'] = PARAM_TEXT;
            $repeateloptions['ip_server']['helpbutton'] = array('ip_server', 'block_remlab_manager');
            $repeateloptions['ip_server']['rule'] = array(
                get_string('maximumchars', '', 4), 'maxlength', 15, 'client');
            if ($experienceinfo) {
                $repeateloptions['ip_server']['default'] = $experienceinfo->ip_server;
            }
            $repeateloptions['port_server']['disabledif'] = array('usingsarlab', 'eq', 0);
            $repeateloptions['port_server']['type'] = PARAM_INT;
            $repeateloptions['port_server']['helpbutton'] = array('port_server', 'block_remlab_manager');
            $repeateloptions['port_server']['rule'] = 'numeric'; // array('port_server', get_string('maximumchars', '', 4), 'maxlength', 4, 'client');
            if ($experienceinfo) {
                $repeateloptions['port_server']['default'] = $experienceinfo->port_server;
            }
            $this->repeat_elements($varsarray, 2, $repeateloptions, 'option_repeats',
                'option_add_fields', 2, null, true);

            if ($experienceinfo) {
                $powerboard = $experienceinfo->power_boards_list;
            } else {
                $powerboard = array('APC 1', 'APC 2');
            }
            $select = $mform->addElement('select', 'lab_power_board',
                get_string('lab_power_board', 'block_remlab_manager'), $powerboard);
            $mform->addHelpButton('lab_power_board', 'lab_power_board', 'block_remlab_manager');
            $mform->disabledIf('lab_power_board', 'usingsarlab', 'eq', 0);
            if ($experienceinfo) {
                $select->setSelected($experienceinfo->power_boards_list[0]);
            } else {
                $select->setSelected('APC 1');
            }

            $poweroutputs = array('1', '2', '3', '4', '5', '6', '7', '8');
            $select = $mform->addElement('select', 'lab_power_outputs',
                get_string('lab_power_outputs', 'block_remlab_manager'), $poweroutputs);
            $select->setMultiple(true);
            $mform->addHelpButton('lab_power_outputs', 'lab_power_outputs', 'block_remlab_manager');
            $mform->disabledIf('lab_power_outputs', 'usingsarlab', 'eq', 0);
            if ($experienceinfo) {
                $select->setSelected($experienceinfo->power_outputs);
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
    public function validation($data, $files) {
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
    }

}