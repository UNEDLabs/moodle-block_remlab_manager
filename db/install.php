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
 * Remlab manager block caps.
 *
 * @package    block
 * @subpackage remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_block_remlab_manager_install() {
    global $DB;
    $dbman = $DB->get_manager();

    $table = new xmldb_table('remlab_manager_conf');
    if (!$dbman->table_exists($table)) {
        $field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, true, null, null);
        $dbman->add_field($table, $field);
        $field = new xmldb_field('practiceintro', XMLDB_TYPE_CHAR, '255', true, XMLDB_NOTNULL, false, null, 'id');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('usingsarlab', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, '0', 'ejsappid');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('sarlabinstance', XMLDB_TYPE_INTEGER, '2', true, XMLDB_NOTNULL, false, '0', 'usingsarlab');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('sarlabcollab', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, '0', 'sarlabinstance');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('ip', XMLDB_TYPE_CHAR, '255', true, XMLDB_NOTNULL, false, null, 'sarlabcollab');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('port', XMLDB_TYPE_INTEGER, '6', true, XMLDB_NOTNULL, false, null, 'ip');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('slotsduration', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, null, 'port');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('totalslots', XMLDB_TYPE_INTEGER, '5', true, XMLDB_NOTNULL, false, null, 'slotsduration');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('weeklyslots', XMLDB_TYPE_INTEGER, '3', true, XMLDB_NOTNULL, false, null, 'totalslots');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('dailyslots', XMLDB_TYPE_INTEGER, '2', true, XMLDB_NOTNULL, false, null, 'weeklyslots');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('reboottime', XMLDB_TYPE_INTEGER, '2', true, XMLDB_NOTNULL, false, null, 'dailyslots');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('active', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, null, 'reboottime');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('free_access', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, null, 'active');
        $dbman->add_field($table, $field);
    }

    $table = new xmldb_table('remlab_manager_expsyst2pract');
    if (!$dbman->table_exists($table)) {
        $field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, true, null, null);
        $dbman->add_field($table, $field);
        $field = new xmldb_field('ejsappid', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, false, null, 'id');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('practiceid', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, false, null, 'ejsappid');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('practiceintro', XMLDB_TYPE_CHAR, '255', true, XMLDB_NOTNULL, false, null, 'practiceintro');
        $dbman->add_field($table, $field);
    }

    $table = new xmldb_table('remlab_manager_sarlab_keys');
    if (!$dbman->table_exists($table)) {
        $field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, true, null, null);
        $dbman->add_field($table, $field);
        $field = new xmldb_field('user', XMLDB_TYPE_CHAR, '100', true, XMLDB_NOTNULL, false, null, 'id');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('sarlabpass', XMLDB_TYPE_CHAR, '40', true, XMLDB_NOTNULL, false, null, 'user');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('labmanager', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, null, 'sarlabpass');
        $dbman->add_field($table, $field);
        $field = new xmldb_field('creationtime', XMLDB_TYPE_INTEGER, '20', true, XMLDB_NOTNULL, false, null, 'labmanager');
        $dbman->add_field($table, $field);
    }
}