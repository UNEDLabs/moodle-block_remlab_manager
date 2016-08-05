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
 * Page for configuring the data needed by a remote lab application to acces the lab hardware
 *
 * @package    block
 * @subpackage remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('simplehtml_form.php');

global $DB, $PAGE, $OUTPUT, $CFG, $SESSION;

// Check for all required and optional variables.
$blockid = required_param('blockid', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$practiceintro_index = optional_param('experience', 0, PARAM_INT);
$editing_experience = optional_param('edit', 0, PARAM_INT);
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

if ($delete != 0 && !empty($SESSION->block_remlab_manager_list_experiences)) { // confirm deletion of experience
    if ($delete == 1) { // show confirm/cancel buttons
        echo $OUTPUT->header();
        $url_confirm = new moodle_url('/blocks/remlab_manager/view.php', array('blockid' => $blockid, 'courseid' => $courseid, 'experience' => $practiceintro_index, 'delete' => 2));
        $url_cancel = new moodle_url('/blocks/remlab_manager/view.php', array('blockid' => $blockid, 'courseid' => $courseid, 'delete' => 3));
        echo $OUTPUT->box(get_string('confirm_deletion', 'block_remlab_manager'));
        echo $OUTPUT->box(html_writer::tag('a', get_string('confirm_delete_button', 'block_remlab_manager'), array('class'=>'btn', 'href'=>$url_confirm)) .
            ' ' . html_writer::tag('a', get_string('cancel_delete_button', 'block_remlab_manager'), array('class'=>'btn', 'href'=>$url_cancel)));
        echo $OUTPUT->footer();
    } else { // perform action and redirect to course page
        if ($delete == 2) { // delete
            $list_experiences = $SESSION->block_remlab_manager_list_experiences;
            $practiceintro = $list_experiences[$practiceintro_index];
            $DB->delete_records('block_remlab_manager_conf', array('practiceintro' => $practiceintro));
            // TODO: If needed, send info to SARLAB for deleting the experience
        }
        $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
        redirect($courseurl);
    }
} else { // add or edit an experience
    $toform['blockid'] = $blockid;
    $toform['courseid'] = $courseid;
    $toform['editingexperience'] = $editing_experience;
    $toform['practiceintro'] = '';
    $toform['originalpracticeintro'] = '';
    $practiceintro = '';
    if ($editing_experience == 1 && !empty($SESSION->block_remlab_manager_list_experiences)) { // editing an already existing experience
        $list_experiences = $SESSION->block_remlab_manager_list_experiences;
        $toform['practiceintro'] = $list_experiences[$practiceintro_index];
        $toform['originalpracticeintro'] = $list_experiences[$practiceintro_index];
        $practiceintro = $list_experiences[$practiceintro_index];
        // If the experience doesn't exist (it is defined in Sarlab but not in Moodle yet), create it:
        if (!$DB->record_exists('block_remlab_manager_conf', array('practiceintro' => '3Tanques'))) {
            $practice_record = new stdClass;
            $practice_record->practiceintro = $practiceintro;
            $practice_record->usingsarlab = 1;
            $list_sarlab_IPs = explode(";", $CFG->sarlab_IP);
            $sarlab_IP = $list_sarlab_IPs[0];
            $last_quote_mark = strrpos($sarlab_IP, "'");
            if ($last_quote_mark != 0) $last_quote_mark++;
            $ip = substr($sarlab_IP, $last_quote_mark);
            $practice_record->ip = $ip;
            $list_sarlab_ports = explode(";", $CFG->sarlab_port);
            $practice_record->port = $list_sarlab_ports[0];
            $practice_record->totalslots = 18;
            $practice_record->weeklyslots = 9;
            $practice_record->dailyslots = 3;
            $practice_record->active = 1;
            $practice_record->free_access = 0;
            $practice_record->slotsduration = 0;
            $practice_record->reboottime = 2;
            $DB->insert_record('block_remlab_manager_conf', $practice_record);
        }
    }

    $simplehtml = new simplehtml_form(null, array($editing_experience, $practiceintro));
    $simplehtml->set_data($toform);

    if ($simplehtml->is_cancelled()) {
        // Cancelled form redirects to the course main page.
        $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
        redirect($courseurl);
    } else if ($fromform = $simplehtml->get_data()) {
        // Store the submitted data
        if ($fromform->editingexperience) {
            $rem_lab_data = $DB->get_record('block_remlab_manager_conf', array('practiceintro' => $fromform->originalpracticeintro));
            $fromform->id = $rem_lab_data->id;
            if (!$DB->update_record('block_remlab_manager_conf', $fromform)) {
                print_error('inserterror', 'block_remlab_manager');
            }
        } else {
            if (!$DB->insert_record('block_remlab_manager_conf', $fromform)) {
                print_error('inserterror', 'block_remlab_manager');
            }
        }
        // TODO: If needed, send info to SARLAB for updating/creating the experience configuration
        $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
        redirect($courseurl);
    } else {
        // Form didn't validate or this is the first display
        echo $OUTPUT->header();
        $simplehtml->display();
        echo $OUTPUT->footer();
    }
}