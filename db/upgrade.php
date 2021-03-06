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
 * Remlab manager block upgrade file.
 *
 * @package    block_remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade function for the remlab manager block
 *
 * @param string $oldversion
 * @return true
 * @throws
 */
function xmldb_block_remlab_manager_upgrade($oldversion) {
    global $DB;

    if ($oldversion <= '2016091601') {
        // Rename sarlab_keys database table to ejsapp_sarlab_keys.
        $dbman = $DB->get_manager();
        $table = new xmldb_table('remlab_manager_conf');
        if ($dbman->table_exists($table)) {
            $dbman->rename_table($table, 'block_remlab_manager_conf');
        }
        $table = new xmldb_table('remlab_manager_expsyst2pract');
        if ($dbman->table_exists($table)) {
            $dbman->rename_table($table, 'block_remlab_manager_exp2prc');
        }
        $table = new xmldb_table('remlab_manager_sarlab_keys');
        if ($dbman->table_exists($table)) {
            $dbman->rename_table($table, 'block_remlab_manager_sb_keys');
        }
    }

    if ($oldversion <= '2017033000') {
        // Rename sarlab_keys database table to ejsapp_sarlab_keys.
        $dbman = $DB->get_manager();
        $table = new xmldb_table('block_remlab_manager_sb_keys');
        $field = new xmldb_field('expirationtime', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, '0', 'creationtime');
        $dbman->add_field($table, $field);
    }

    if ($oldversion <= '2017091921') {
        // Get system context.
        $context = context_system::instance();

        // Create the experience designer role.
        if (!$DB->record_exists('role', array('shortname' => 'enlargedesigner'))) {
            $enlargedesignerid = create_role(get_string('enlargedesigner', 'block_remlab_manager'),
                'enlargedesigner', get_string('enlargedesigner_desc', 'block_remlab_manager'));
            set_role_contextlevels($enlargedesignerid, array(CONTEXT_SYSTEM));
        } else {
            $enlargedesignerid = $DB->get_field('role', 'id', array('shortname' => 'enlargedesigner'));
        }
        // Assign capabilities.
        assign_capability('block/remlab_manager:myaddinstance', CAP_ALLOW, $enlargedesignerid, $context->id, true);
        assign_capability('block/remlab_manager:addinstance', CAP_ALLOW, $enlargedesignerid, $context->id, true);
        assign_capability('block/remlab_manager:view', CAP_ALLOW, $enlargedesignerid, $context->id, true);

        // Create the experience manager role.
        if (!$DB->record_exists('role', array('shortname' => 'enlargemanager'))) {
            $enlargemanagerid = create_role(get_string('enlargemanager', 'block_remlab_manager'),
                'enlargemanager', get_string('enlargemanager_desc', 'block_remlab_manager'));
            set_role_contextlevels($enlargemanagerid, array(CONTEXT_SYSTEM));
        } else {
            $enlargemanagerid = $DB->get_field('role', 'id', array('shortname' => 'enlargemanager'));
        }
        // Assign capabilities.
        assign_capability('block/remlab_manager:myaddinstance', CAP_ALLOW, $enlargemanagerid, $context->id, true);
        assign_capability('block/remlab_manager:addinstance', CAP_ALLOW, $enlargemanagerid, $context->id, true);
        assign_capability('block/remlab_manager:view', CAP_ALLOW, $enlargemanagerid, $context->id, true);

        // Clear any capability caches
        $context->mark_dirty();
    }

    if ($oldversion < '2017092700') {
        // Delete the fields related to ENLARGE experiences.
        $dbman = $DB->get_manager();
        $table = new xmldb_table('block_remlab_manager_conf');
        $field = new xmldb_field('usingsarlab', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL,
            null, '0', 'practiceintro');
        $dbman->drop_field($table, $field);
        $field = new xmldb_field('sarlabinstance', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL,
            null, '0', 'usingsarlab');
        $dbman->drop_field($table, $field);
        $field = new xmldb_field('sarlabcollab', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL,
            null, '0', 'sarlabinstance');
        $dbman->drop_field($table, $field);
    }

    if ($oldversion < '2018070800') {
        // Delete the fields related to ENLARGE experiences.
        $dbman = $DB->get_manager();
        $table = new xmldb_table('block_remlab_manager_conf');
        $field = new xmldb_field('usestate', XMLDB_TYPE_TEXT, '20', null, null,
            null, 'available', 'free_access');
        $dbman->add_field($table, $field);
    }

    if ($oldversion < '2019110400') {
        // Rename sarlab to enlarge
        $dbman = $DB->get_manager();
        $table = new xmldb_table('block_remlab_manager_sb_keys');
        if ($dbman->table_exists($table)) {
            $field = new xmldb_field('labmanager', XMLDB_TYPE_INTEGER, '1', null,
                XMLDB_NOTNULL, null, '0', 'sarlabpass', 'creationtime');
            $dbman->rename_field($table, $field, 'enlargepass');
            $dbman->rename_table($table, 'block_remlab_manager_eg_keys');
        }
    }
    
    // Make sure capabilities are assigned to the enlargedesigner and enlargemanager roles
    $context = context_system::instance();
    
    if ($DB->record_exists('role', array('shortname' => 'enlargedesigner'))) {
        $enlargedesignerid = $DB->get_field('role', 'id', array('shortname' => 'enlargedesigner'));
        assign_capability('block/remlab_manager:myaddinstance', CAP_ALLOW, $enlargedesignerid, $context->id, true);
        assign_capability('block/remlab_manager:addinstance', CAP_ALLOW, $enlargedesignerid, $context->id, true);
        assign_capability('block/remlab_manager:view', CAP_ALLOW, $enlargedesignerid, $context->id, true);
    }

    if ($DB->record_exists('role', array('shortname' => 'enlargemanager'))) {
        $enlargemanagerid = $DB->get_field('role', 'id', array('shortname' => 'enlargemanager'));
        assign_capability('block/remlab_manager:myaddinstance', CAP_ALLOW, $enlargemanagerid, $context->id, true);
        assign_capability('block/remlab_manager:addinstance', CAP_ALLOW, $enlargemanagerid, $context->id, true);
        assign_capability('block/remlab_manager:view', CAP_ALLOW, $enlargemanagerid, $context->id, true);
    }

    return true;
}
