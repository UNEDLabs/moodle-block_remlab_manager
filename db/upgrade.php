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
 * Remlab manager block caps.
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

    return true;
}