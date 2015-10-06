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
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, true, null);
        $table->add_field('practiceintro', XMLDB_TYPE_CHAR, '255', true, XMLDB_NOTNULL, false, null, 'id');
        $table->add_field('usingsarlab', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, '0', 'practiceintro');
        $table->add_field('sarlabinstance', XMLDB_TYPE_INTEGER, '2', true, XMLDB_NOTNULL, false, '0', 'usingsarlab');
        $table->add_field('sarlabcollab', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, '0', 'sarlabinstance');
        $table->add_field('ip', XMLDB_TYPE_CHAR, '255', true, XMLDB_NOTNULL, false, null, 'sarlabcollab');
        $table->add_field('port', XMLDB_TYPE_INTEGER, '6', true, XMLDB_NOTNULL, false, null, 'ip');
        $table->add_field('slotsduration', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, null, 'port');
        $table->add_field('totalslots', XMLDB_TYPE_INTEGER, '5', true, XMLDB_NOTNULL, false, null, 'slotsduration');
        $table->add_field('weeklyslots', XMLDB_TYPE_INTEGER, '3', true, XMLDB_NOTNULL, false, null, 'totalslots');
        $table->add_field('dailyslots', XMLDB_TYPE_INTEGER, '2', true, XMLDB_NOTNULL, false, null, 'weeklyslots');
        $table->add_field('reboottime', XMLDB_TYPE_INTEGER, '2', true, XMLDB_NOTNULL, false, null, 'dailyslots');
        $table->add_field('active', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, null, 'reboottime');
        $table->add_field('free_access', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, null, 'active');
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $dbman->create_table($table);
    }

    $table = new xmldb_table('remlab_manager_expsyst2pract');
    if (!$dbman->table_exists($table)) {
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, true, null);
        $table->add_field('ejsappid', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, false, null, 'id');
        $table->add_field('practiceid', XMLDB_TYPE_INTEGER, '10', true, XMLDB_NOTNULL, false, null, 'ejsappid');
        $table->add_field('practiceintro', XMLDB_TYPE_CHAR, '255', true, XMLDB_NOTNULL, false, null, 'practiceid');
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $dbman->create_table($table);
    }

    $table = new xmldb_table('remlab_manager_sarlab_keys');
    if (!$dbman->table_exists($table)) {
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, true, null);
        $table->add_field('user', XMLDB_TYPE_CHAR, '100', true, XMLDB_NOTNULL, false, null, 'id');
        $table->add_field('sarlabpass', XMLDB_TYPE_CHAR, '40', true, XMLDB_NOTNULL, false, null, 'user');
        $table->add_field('labmanager', XMLDB_TYPE_INTEGER, '1', true, XMLDB_NOTNULL, false, null, 'sarlabpass');
        $table->add_field('creationtime', XMLDB_TYPE_INTEGER, '20', true, XMLDB_NOTNULL, false, null, 'labmanager');
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $dbman->create_table($table);
    }
}