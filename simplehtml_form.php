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
 * Page for configuring the data needed by a non-ENLARGE remote lab application
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

        // Get data if editing an already existing experience.
        $editing = $this->_customdata[0];
        $practiceintro = $this->_customdata[1];
        $remlab = false;

        $mform->addElement('hidden', 'editing', null);
        $mform->setType('editing', PARAM_INT);
        $mform->setDefault('editing', 0);

        if ($editing) {
            $mform->setDefault('editing', 1);
            $remlab = $DB->get_record('block_remlab_manager_conf', array('practiceintro' => $practiceintro));
        }

        $mform->addElement('text', 'practiceintro',
            get_string('practiceintro', 'block_remlab_manager'), array('size' => '25'));
        $mform->setType('practiceintro', PARAM_TEXT);
        $mform->addHelpButton('practiceintro', 'practiceintro', 'block_remlab_manager');
        $mform->addRule('practiceintro',
            get_string('practiceintro_required', 'block_remlab_manager'), 'required');

        $mform->addElement('hidden', 'initialpracticeintro', null);
        $mform->setType('initialpracticeintro', PARAM_TEXT);

        $mform->addElement('text', 'ip',
            get_string('ip_lab', 'block_remlab_manager'), array('size' => '15'));
        $mform->setType('ip', PARAM_TEXT);
        $mform->addRule('ip',
            get_string('maximumchars', ''), 'maxlength', 15, 'client');
        $mform->addHelpButton('ip', 'ip_lab', 'block_remlab_manager');
        $mform->addRule('ip',
            get_string('ip_lab_required', 'block_remlab_manager'), 'required');

        $mform->addElement('text', 'port',
            get_string('port', 'block_remlab_manager'), array('size' => '6'));
        $mform->setType('port', PARAM_INT);
        $mform->addRule('port',
            get_string('maximumchars', ''), 'maxlength', 6, 'client');
        $mform->addHelpButton('port', 'port', 'block_remlab_manager');
        $mform->addRule('port',
            get_string('port_required', 'block_remlab_manager'), 'required');

        if ($remlab) {
            $mform->setDefault('practiceintro', $remlab->practiceintro);
            $mform->setDefault('initialpracticeintro', $remlab->practiceintro);
            $mform->setDefault('ip', $remlab->ip);
            $mform->setDefault('port', $remlab->port);
            $enlargeinstance = is_practice_in_enlarge($remlab->practiceintro);
            if ($enlargeinstance !== false) {
                $mform->freeze(array('practiceintro', 'ip', 'port'));
            }
        } else {
            $mform->setDefault('initialpracticeintro', '');
            $mform->setDefault('ip', '127.0.0.1');
            $mform->setDefault('port', 443);
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

        $this->add_action_buttons();
    }

    /**
     * Performs minimal validation on the settings form
     * @param array $data
     * @param array $files
     * @return array $errors
     * @throws
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $editing = $data['editing'];
        $showableexperiences = get_showable_experiences();
        if ($editing == 0) { // New experience
            // Make sure the practice name or identifier does not exist locally nor remotely (in ENLARGE)
            if (in_array($data['practiceintro'], $showableexperiences)) {
                $errors['practiceintro'] = get_string('existing_practice_id', 'block_remlab_manager');
            }
        } else { // Editing an existing experience
            if (($data['practiceintro'] != $data['initialpracticeintro']) &&
                in_array($data['practiceintro'], $showableexperiences)) {
                $errors['practiceintro'] = get_string('existing_practice_id', 'block_remlab_manager');
            }
        }
        return $errors;
    }

}