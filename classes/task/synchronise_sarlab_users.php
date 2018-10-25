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
 * Remlab manager block Sarlab users synchronization task.
 *
 * @package    block_remlab_manager
 * @copyright  2018 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_remlab_manager\task;

/**
 * Task for synchronising Sarlab users between Moodle and Sarlab.
 *
 * @package    block_remlab_manager
 * @copyright  2018 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class synchronise_sarlab_users extends \core\task\scheduled_task {
    /**
     * Get a descriptive name for this task.
     *
     * @return string
     * @throws
     */
    public function get_name() {
        // Shown in admin screens
        return get_string('synchronise_sarlab_users', 'block_remlab_manager');
    }

    /**
     * Performs the synchronisation of sarlab users.
     *
     * @return bool|void
     * @throws
     */
    public function execute() {
        global $DB, $CFG;

        // Obtain the list of users in Moodle with Sarlab designer role
        $sarlabdesignerroleid = $DB->get_field('role', 'id', array('shortname' => 'sarlabdesigner'));
        $records = $DB->get_records('role_assignments', array('roleid' => $sarlabdesignerroleid));
        $sarlabdesignerusersid = array();
        foreach ($records as $record) {
            array_push($sarlabdesignerusersid, $record->userid);
        }

        // Obtain the list of users in Moodle with Sarlab manager role
        $sarlabmanagerroleid = $DB->get_field('role', 'id', array('shortname' => 'sarlabmanager'));
        $records = $DB->get_records('role_assignments', array('roleid' => $sarlabmanagerroleid));
        $sarlabmanagerusersid = array();
        foreach ($records as $record) {
            array_push($sarlabmanagerusersid, $record->userid);
        }

        // Ask Sarlab IRS for $sarlabirsdesignerusersid and $sarlabirsmanagerusersid
        $sarlabirstoken = 'demo-101-irs-token';

        $curl = curl_init('http://sarlabirs.dia.uned.es/designers');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERPWD, $sarlabirstoken . ':');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/xml;q=0.9, */*;q=0.8'));
        $curl_response = curl_exec($curl);
        curl_close($curl);
        if ($curl_response !== false) {
            $xml_response = simplexml_load_string($curl_response);
            if ($xml_response) {
                $sarlabirsdesignerusersid = [];
                $sarlabirsmanagerusersid = [];
                $record = new \stdClass();
                $record->contextid    = 1;
                $record->component    = '';
                $record->itemid       = 0;
                $record->timemodified = time();
                $record->modifierid   = 0;
                $record->sortorder    = 0;
                foreach ($xml_response->item as $item) {
                    $siteURL = (string) $item->siteURL;
                    $user_id = (int) $item->user_id;
                    if ((strpos($CFG->wwwroot, $siteURL) !== false)  && $DB->record_exists('user', ['id' => $user_id])) {
                        // Check whether the user is designer or manager and update role assignments
                        $level = (int) $item->level;
                        if ($level == 1) {
                            $sarlabirsdesignerusersid[] = $user_id;
                            if (!$DB->record_exists('role_assignments', ['roleid' => $sarlabdesignerroleid,
                                'userid' => $user_id])) {
                                $record->roleid = $sarlabdesignerroleid;
                                $record->userid = $user_id;
                                $DB->insert_record('role_assignments', $record);
                            }
                        } else if ($level == 0) {
                            $sarlabirsmanagerusersid[] = $user_id;
                            if (!$DB->record_exists('role_assignments', ['roleid' => $sarlabmanagerroleid,
                                'userid' => $user_id])) {
                                $record->roleid = $sarlabmanagerroleid;
                                $record->userid = $user_id;
                                $DB->insert_record('role_assignments', $record);
                            }
                        }
                    }
                }
                foreach ($sarlabdesignerusersid as $sarlabdesigneruserid) {
                    if (!in_array($sarlabdesigneruserid, $sarlabirsdesignerusersid)) {
                        $DB->delete_records('role_assignments', ['roleid' => $sarlabdesignerroleid,
                            'userid' => $sarlabdesigneruserid]);
                    }
                }
                foreach ($sarlabmanagerusersid as $sarlabmanageruserid) {
                    if (!in_array($sarlabmanageruserid, $sarlabirsmanagerusersid)) {
                        $DB->delete_records('role_assignments', ['roleid' => $sarlabmanagerroleid,
                            'userid' => $sarlabmanageruserid]);
                    }
                }
            }
        }
    }
}