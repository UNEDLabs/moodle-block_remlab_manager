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
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_remlab_manager\task;

/**
 * Task for synchronising Sarlab users between Moodle and Sarlab.
 *
 * @package    block_remlab_manager
 * @copyright  2015 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class synchronise_sarlab_users extends \core\task\scheduled_task {
    /**
     * Get a descriptive name for this task.
     *
     * @return string
     */
    public function get_name() {
        // Shown in admin screens
        return get_string('synchronise_sarlab_users', 'block_remlab_manager');
    }

    /**
     * Performs the synchronisation of sarlab users.
     *
     * @return bool|void
     */
    public function execute() {
        global $DB;


        // Obtain the list of users in Moodle with Sarlab designer role
        $sarlabdesignerroleid = $DB->get_field('role', 'id', array('shortname' => 'sarlabdesigner'));
        $sarlabdesignerusers = $DB->get_records('role', array('roleid' => $sarlabdesignerroleid));
        $sarlabdesignerusersid = array();
        foreach ($sarlabdesignerusers as $sarlabdesigneruser) {
            array_push($sarlabdesignerusersid, $sarlabdesigneruser->id);
        }

        // Obtain the list of users in Moodle with Sarlab manager role
        $sarlabmanagerroleid = $DB->get_field('role', 'id', array('shortname' => 'sarlabmanager'));
        $sarlabmanagerusers = $DB->get_records('role', array('roleid' => $sarlabmanagerroleid));
        $sarlabmanagerusersid = array();
        foreach ($sarlabmanagerusers as $sarlabmanageruser) {
            array_push($sarlabmanagerusersid, $sarlabmanageruser->id);
        }

        // Compare info between authorized users in Sarlab and authorized users in Moodle
        // Communicate with Sarlab to send $sarlabdesignerusersid and $sarlabmanagerusersid

    }
}