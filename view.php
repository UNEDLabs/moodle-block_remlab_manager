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

require_once('../../config.php');
require_once('simplehtml_form.php');

global $DB, $PAGE, $OUTPUT, $CFG, $SESSION;

// Check for all required and optional variables.
$blockid = required_param('blockid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$experienceid = optional_param('experience', 0, PARAM_INT);
$editing = optional_param('edit', 0, PARAM_INT);
$delete = optional_param('delete', 0, PARAM_INT);
$id = optional_param('id', -1, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse');
}

require_login($course);

$PAGE->set_url('/blocks/remlab_manager/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('configure_lab', 'block_remlab_manager'));

$context = context_course::instance($courseid);
if (!has_capability('block/remlab_manager:addinstance', $context)) {
    echo $OUTPUT->header();
    echo $OUTPUT->footer();
}

if ($delete != 0 && !empty($SESSION->block_remlab_manager_list_experiences)) { // Confirm deletion of local experience.
    if ($delete == 1) { // Show confirm/cancel buttons.
        echo $OUTPUT->header();
        $urlconfirm = new moodle_url('/blocks/remlab_manager/view.php',
            array('blockid' => $blockid, 'courseid' => $courseid, 'experience' => $experienceid, 'delete' => 2,
                'sesskey' => sesskey()));
        $urlcancel = new moodle_url('/blocks/remlab_manager/view.php',
            array('blockid' => $blockid, 'courseid' => $courseid, 'delete' => 3));
        echo $OUTPUT->box(get_string('confirm_deletion', 'block_remlab_manager'));
        echo $OUTPUT->box(html_writer::tag('a',
                get_string('confirm_delete_button', 'block_remlab_manager'),
                array('class' => 'btn', 'href' => $urlconfirm)) . ' ' . html_writer::tag('a',
                    get_string('cancel_delete_button', 'block_remlab_manager'),
                array('class' => 'btn', 'href' => $urlcancel)));
        echo $OUTPUT->footer();
    } else { // Perform action and redirect to course page.
        if ($delete == 2) { // Delete local configuration of the experience.
            require_sesskey();
            $experiences = $SESSION->block_remlab_manager_list_experiences;
            $practiceintro = $experiences[$experienceid];
            $DB->delete_records('block_remlab_manager_conf', array('practiceintro' => $practiceintro));
            $records = $DB->get_records('block_remlab_manager_exp2prc', array('practiceintro' => $practiceintro));
            foreach ($records as $record) {
                $DB->delete_records('ejsapp', array('id' => $record->id));
            }
            $DB->delete_records('block_remlab_manager_exp2prc', array('practiceintro' => $practiceintro));
        }
        $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
        redirect($courseurl);
    }
} else { // Add or edit an experience.
    require_sesskey();
    $toform['blockid'] = $blockid;
    $toform['courseid'] = $courseid;
    $toform['editingexperience'] = $editing;
    if ($editing == 1 && !empty($SESSION->block_remlab_manager_list_experiences)) { // Editing an already listed experience.
        $experiences = $SESSION->block_remlab_manager_list_experiences;
        $practiceintro = $experiences[$experienceid];
        $toform['practiceintro'] = $practiceintro;
        $toform['originalpracticeintro'] = $practiceintro;
        // If the experience doesn't exist (it is defined in ENLARGE but not in Moodle yet), create it.
        if (!$DB->record_exists('block_remlab_manager_conf', array('practiceintro' => $practiceintro)) &&
        $practiceintro != '') {
            $default_conf = default_rem_lab_conf($practiceintro, $USER->username);
            $DB->insert_record('block_remlab_manager_conf', $default_conf);
        }
    } else {
        $practiceintro = '';
        $toform['practiceintro'] = '';
        $toform['originalpracticeintro'] = '';
    }

    $simplehtml = new simplehtml_form(null, array($editing, $practiceintro));
    $simplehtml->set_data($toform);

    if ($simplehtml->is_cancelled()) {
        // Cancelled form redirects to the course main page.
        $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
        redirect($courseurl);
    } else if ($fromform = $simplehtml->get_data()) {
        if ($fromform->practiceintro != '') {
            require_sesskey();
            // Store the submitted data.
            if ($fromform->editingexperience) {
                $remlab = $DB->get_record('block_remlab_manager_conf',
                    array('practiceintro' => $fromform->originalpracticeintro));
                $fromform->id = $remlab->id;
                if (!$DB->update_record('block_remlab_manager_conf', $fromform)) {
                    print_error('Could not update record in block_remlab_manager_conf table',
                        'block_remlab_manager');
                }
            } else {
                if (!$DB->insert_record('block_remlab_manager_conf', $fromform)) {
                    print_error('Could not insert record in block_remlab_manager_conf table',
                        'block_remlab_manager');
                }
            }
            $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
            redirect($courseurl);
        } else {
            print_error('The name of the experience was left blank', 'block_remlab_manager');
        }
    } else {
        // Form didn't validate or this is the first display.
        echo $OUTPUT->header();
        $simplehtml->display();
        echo $OUTPUT->footer();
    }
}