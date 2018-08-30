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
 * Remlab manager block usestate field update task.
 *
 * @package    block_remlab_manager
 * @copyright  2018 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_remlab_manager\task;

/**
 * Task for updating the value of the usestate field in the block_remlab_manager_conf table.
 *
 * @package    block_remlab_manager
 * @copyright  2018 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ping_remote_labs extends \core\task\scheduled_task {
    /**
     * Get a descriptive name for this task.
     *
     * @return string
     * @throws
     */
    public function get_name() {
        // Shown in admin screens
        return get_string('ping_remote_labs', 'block_remlab_manager');
    }

    /**
     * Performs ta ping to the remote lab computers and updates the 'available' field.
     *
     * @return bool|void
     * @throws
     */
    public function execute() {
        global $DB, $CFG;

        require_once($CFG->dirroot . '/filter/multilang/filter.php');

        $remlabsconf = $DB->get_records('block_remlab_manager_conf');
        foreach ($remlabsconf as $remlabconf) {
            $sarlabinstance = is_practice_in_sarlab($remlabconf->practiceintro);
            $devicesinfo = new stdClass();
            $labstate = ping($remlabconf->ip, $remlabconf->port, $sarlabinstance, $remlabconf->practiceintro);
            $remlabs = get_repeated_remlabs($remlabconf->practiceintro);
            foreach ($remlabs as $remlab) {
                $context = context_course::instance($remlab->course);
                $multilang = new filter_multilang($context, array('filter_multilang_force_old' => 0));
                $sendmail = false;
                // Prepare e-mails' content and update lab state when checkable.
                $subject = '';
                $messagebody = '';
                // E-mails are sent only if the remote lab state is not checkable or if it has passed from active to inactive
                if ($labstate == 2) {  // Not checkable.
                    $subject = get_string('mail_subject_lab_not_checkable', 'block_remlab_manager');
                    $messagebody = get_string('mail_content1_lab_not_checkable', 'block_remlab_manager') .
                        $multilang->filter($remlab->name) .
                        get_string('mail_content2_lab_not_checkable', 'block_remlab_manager') . $remlabconf->ip .
                        get_string('mail_content3_lab_not_checkable', 'block_remlab_manager');
                    $sendmail = true;
                } else {               // Active or inactive.
                    if ($remlabconf->active == 1 && $labstate == 0) {  // Lab has passed from active to inactive.
                        $subject = get_string('mail_subject_lab_down', 'block_remlab_manager');
                        $messagebody = get_string('mail_content1_lab_down', 'block_remlab_manager') .
                            $multilang->filter($remlab->name) .
                            get_string('mail_content2_lab_down', 'block_remlab_manager') . $remlabconf->ip .
                            get_string('mail_content3_lab_down', 'block_remlab_manager') .
                            get_string('mail_content4_lab_down', 'block_remlab_manager');
                        /*foreach ($devicesinfo as $deviceinfo) {
                            if (!$deviceinfo->alive) {
                                $messagebody .= $deviceinfo->name . ', ' . $deviceinfo->ip . "\r\n";
                            }
                        }*/
                        $sendmail = true;
                    } else if ($remlabconf->active == 0 && $labstate == 1) { // Lab has passed from inactive to active.
                        $subject = get_string('mail_subject_lab_up', 'block_remlab_manager');
                        $messagebody = get_string('mail_content1_lab_up', 'block_remlab_manager') .
                            $multilang->filter($remlab->name) .
                            get_string('mail_content2_lab_up', 'block_remlab_manager') . $remlabconf->ip .
                            get_string('mail_content3_lab_up', 'block_remlab_manager');
                        $sendmail = true;
                    }
                    $remlabconf->active = $labstate;
                    $DB->update_record('block_remlab_manager_conf', $remlabconf);
                }
                // Send e-mails to teachers if conditions are met.
                $role = $DB->get_record('role', array('shortname' => 'editingteacher'));
                // TODO: Allow configuring which roles receive the e-mails? (managers, non-editing teacher...) Use Moodle capabilities.
                if ($sendmail) {
                    $teachers = get_role_users($role->id, $context);
                    foreach ($teachers as $teacher) {
                        email_to_user($teacher, $teacher, $subject, $messagebody);
                    }
                }
            }
        }
    }
}