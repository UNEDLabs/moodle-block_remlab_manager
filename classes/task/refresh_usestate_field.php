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
class refresh_usestate_field extends \core\task\scheduled_task {
    /**
     * Get a descriptive name for this task.
     *
     * @return string
     * @throws
     */
    public function get_name() {
        // Shown in admin screens
        return get_string('refresh_usestate_field', 'block_remlab_manager');
    }

    /**
     * Performs the update of the usestate field if needed.
     *
     * @return bool|void
     * @throws
     */
    public function execute() {
        global $DB;

        // Obtain the list of remote labs that are in use
        $select = 'usestate ';
        list($sql, $params) = $DB->get_in_or_equal(array('in use', 'rebooting'), SQL_PARAMS_NAMED);
        $select .= $sql;
        $notavailableremlabs = $DB->get_records_select('block_remlab_manager_conf', $select, $params);
        foreach ($notavailableremlabs as $notavailableremlab) {
            // Get all EJSApp activities associated to each of those remote labs
            $repeatedpractices = $DB->get_records('block_remlab_manager_exp2prc',
                array('practiceintro' => $notavailableremlab->practiceintro));
            $ejsappids = array();
            foreach ($repeatedpractices as $repeatedpractice) {
                array_push($ejsappids, $repeatedpractice->ejsappid);
            }
            $repeatedlabs = $DB->get_records_list('ejsapp', 'id', $ejsappids);
            // Check whether last users activity in the EJSApp activities associated to that remote lab was before the
            // checkactivity configuration from the EJSApp plugin plus the time that lab requires to reboot. If so,
            // change 'usestate' field to 'available'.
            $ids = array();
            $names = array();
            foreach ($repeatedlabs as $repeatedlab) {
                $ids[] = $repeatedlab->id;
                $names[] = $repeatedlab->name;
            }
            $checkactivity = get_config('mod_ejsapp', 'check_activity');
            $safetime = $checkactivity + 60 * $notavailableremlab->reboottime;
            $dbman = $DB->get_manager();
            $standardlog = $dbman->table_exists('logstore_standard_log');
            $currenttime = time();
            if ($standardlog && !empty($ids)) {
                $select = 'component = :component AND action = :action AND timecreated > :timecreated AND objectid ';
                list($sql, $params) = $DB->get_in_or_equal($ids, SQL_PARAMS_NAMED);
                $select .= $sql;
                $queryparams = ['component' => 'mod_ejsapp', 'action' => 'working', 'timecreated' => $currenttime - $safetime];
                $queryparams += $params;
                $inuse = $DB->get_field_select('logstore_standard_log', 'MAX(timecreated)', $select, $queryparams);
            } else if (!empty($names)) {
                $select = 'action = :action AND time > :time AND info ';
                list($sql, $params) = $DB->get_in_or_equal($names, SQL_PARAMS_NAMED);
                $select .= $sql;
                $queryparams = ['action' => 'working', 'time' => $currenttime - $safetime];
                $queryparams += $params;
                $inuse = $DB->get_field_select('log', 'MAX(time)', $select, $queryparams);
            } else {
                $inuse = false;
            }
            if ($inuse == false) { // If so, change 'usestate' field to 'available' to mark the lab as not in use.
                if ($standardlog) {
                    $queryparams = ['component' => 'mod_ejsapp', 'action' => 'working', 'timecreated' => $currenttime - $checkactivity];
                    $queryparams += $params;
                    $available = $DB->get_field_select('logstore_standard_log', 'MAX(timecreated)', $select, $queryparams);
                } else {
                    $queryparams = ['action' => 'working', 'time' => $currenttime - $checkactivity];
                    $queryparams += $params;
                    $available = $DB->get_field_select('log', 'MAX(time)', $select, $queryparams);
                }
                if ($available == false) { // If so, change 'usestate' field to 'rebooting' to mark the lab as not in use.
                    $notavailableremlab->usestate = 'rebooting';
                    $DB->update_record('block_remlab_manager_conf', $notavailableremlab);
                }
                $notavailableremlab->usestate = 'available';
                $DB->update_record('block_remlab_manager_conf', $notavailableremlab);
            }
        }
    }
}