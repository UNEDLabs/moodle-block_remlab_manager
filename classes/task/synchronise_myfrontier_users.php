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
 * Remlab manager block ENLARGE IRS users synchronization task.
 *
 * @package    block_remlab_manager
 * @copyright  2018 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_remlab_manager\task;

/**
 * Task for synchronising ENLARGE IRS users between Moodle and myFrontier.
 *
 * @package    block_remlab_manager
 * @copyright  2018 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class synchronise_myfrontier_users extends \core\task\scheduled_task {
    /**
     * Get a descriptive name for this task.
     *
     * @return string
     * @throws
     */
    public function get_name() {
        // Shown in admin screens
        return get_string('synchronise_myFrontier_users', 'block_remlab_manager');
    }

    /**
     * Performs the synchronisation of myFrontier users.
     *
     * @return bool|void
     * @throws
     */
    public function execute() {
        global $DB, $CFG;

        // Obtain the list of users in Moodle with ENLARGE designer role
        $enlargedesignerroleid = $DB->get_field('role', 'id', array('shortname' => 'enlargedesigner'));
        $records = $DB->get_records('role_assignments', array('roleid' => $enlargedesignerroleid));
        $enlargedesignerusersid = array();
        foreach ($records as $record) {
            array_push($enlargedesignerusersid, $record->userid);
        }

        // Obtain the list of users in Moodle with ENLARGE manager role
        $enlargemanagerroleid = $DB->get_field('role', 'id', array('shortname' => 'enlargemanager'));
        $records = $DB->get_records('role_assignments', array('roleid' => $enlargemanagerroleid));
        $enlargemanagerusersid = array();
        foreach ($records as $record) {
            array_push($enlargemanagerusersid, $record->userid);
        }

        // Ask ENLARGE IRS for $enlargeirsdesignerusersid and $enlargeirsmanagerusersid
        $enlargeirstoken = 'demo-101-irs-token';

        $curl = curl_init('http://irs.nebsyst.com/designers');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERPWD, $enlargeirstoken . ':');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/xml;q=0.9, */*;q=0.8'));
        $curl_response = curl_exec($curl);
        curl_close($curl);
        if ($curl_response !== false) {
            $xml_response = simplexml_load_string($curl_response);
            if ($xml_response) {
                $site_hostname = parse_url($CFG->wwwroot);
                $site_hostname = $site_hostname['host'];
                $enlargeirsdesignerusersid = [];
                $enlargeirsmanagerusersid = [];
                $record = new \stdClass();
                $record->contextid    = 1;
                $record->component    = '';
                $record->itemid       = 0;
                $record->timemodified = time();
                $record->modifierid   = 0;
                $record->sortorder    = 0;
                // Prepare lti users remote ids, remote hostname url and local ids
                $lti_users_hostname = $DB->get_fieldset_select('enrol_lti_users', 'serviceurl',
                    "id > ?", array(0));
                if (!empty($lti_users_hostname)) {
                    function fcn1(&$item) {
                        $item = parse_url($item);
                        $item = $item['host'];
                    }
                    array_walk($lti_users_hostname, array('self', 'fcn1'));
                }
                $lti_remote_users_id = $DB->get_fieldset_select('enrol_lti_users', 'sourceid',
                    "id > ?", array(0));
                if (!empty($lti_remote_users_id)) {
                    function fcn2(&$item) {
                        $item = json_decode($item, true);
                        $item = $item['data'];
                        $item = $item['userid'];
                    }
                    array_walk($lti_remote_users_id, array('self', 'fcn2'));
                }
                function update_role($record, $user_id, $enlargeroleid) {
                    // Check whether the user is designer or manager and update role assignments
                    global $DB;
                    if (!$DB->record_exists('role_assignments', ['roleid' => $enlargeroleid,
                        'userid' => $user_id])) {
                        $record->roleid = $enlargeroleid;
                        $record->userid = $user_id;
                        $DB->insert_record('role_assignments', $record);
                    }
                    return $user_id;
                }
                foreach ($xml_response->item as $item) {
                    $user_site_hostname = parse_url((string) $item->siteURL);
                    $user_site_hostname = $user_site_hostname['host'];
                    $user_id = (int) $item->user_id;
                    if (strcmp($site_hostname, $user_site_hostname) == 0) {
                        $level = (int)$item->level;
                        if ($DB->record_exists('user', ['id' => $user_id])) { // this moodle's user
                            if ($level == 1) {
                                $enlargeirsmanagerusersid[] = update_role($record, $user_id, $enlargedesignerroleid);
                            } else {
                                $enlargeirsmanagerusersid[] = update_role($record, $user_id, $enlargemanagerroleid);
                            }
                        } else { // LTI user?
                            // Check if the user info received from ENLARGE IRS corresponds to any LTI user
                            $origin_coincidence = array_search($user_site_hostname, $lti_users_hostname);
                            $user_id_coincidence = array_search($user_id, $lti_remote_users_id);
                            if ($origin_coincidence && $user_id_coincidence &&
                                ($origin_coincidence == $user_id_coincidence)) { // If so, continue
                                if ($level == 1) {
                                    $enlargeirsdesignerusersid[] = update_role($record, $user_id, $enlargedesignerroleid);
                                } else {
                                    $enlargeirsmanagerusersid[] = update_role($record, $user_id, $enlargemanagerroleid);
                                }
                            }
                        }
                    }
                }
                foreach ($enlargedesignerusersid as $enlargedesigneruserid) {
                    if (!in_array($enlargedesigneruserid, $enlargeirsdesignerusersid)) {
                        $DB->delete_records('role_assignments', ['roleid' => $enlargedesignerroleid,
                            'userid' => $enlargedesigneruserid]);
                    }
                }
                foreach ($enlargemanagerusersid as $enlargemanageruserid) {
                    if (!in_array($enlargemanageruserid, $enlargeirsmanagerusersid)) {
                        $DB->delete_records('role_assignments', ['roleid' => $enlargemanagerroleid,
                            'userid' => $enlargemanageruserid]);
                    }
                }
            }
        }
    }
}