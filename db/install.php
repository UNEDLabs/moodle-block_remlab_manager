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
 * Remlab manager block installation.
 *
 * @package    block_remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_block_remlab_manager_install() {
    global $DB;

    // Get system context.
    $context = context_system::instance();

    // Check if the sarlab designer user role exists.
    if ($DB->record_exists('role', array('shortname' => 'sarlabdesigner'))) {
        $sarlabdesignerid = $DB->get_field('role', 'id', array('shortname' => 'sarlabdesigner'));
        // If so, assign capabilities.
        assign_capability('block/remlab_manager:myaddinstance', CAP_ALLOW, $sarlabdesignerid, $context->id, true);
        assign_capability('block/remlab_manager:addinstance', CAP_ALLOW, $sarlabdesignerid, $context->id, true);
        assign_capability('block/remlab_manager:view', CAP_ALLOW, $sarlabdesignerid, $context->id, true);
    }

    // Check if the sarlab manager role exists.
    if ($DB->record_exists('role', array('shortname' => 'sarlabmanager'))) {
        $sarlabmanagerid = $DB->get_field('role', 'id', array('shortname' => 'sarlabmanager'));
        // If so, assign capabilities.
        assign_capability('block/remlab_manager:myaddinstance', CAP_ALLOW, $sarlabmanagerid, $context->id, true);
        assign_capability('block/remlab_manager:addinstance', CAP_ALLOW, $sarlabmanagerid, $context->id, true);
        assign_capability('block/remlab_manager:view', CAP_ALLOW, $sarlabmanagerid, $context->id, true);
    }

    // Clear any capability caches
    $context->mark_dirty();
}