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

$capabilities = array(

    'block/remlab_manager:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_PROHIBIT,
            'guest' => CAP_PROHIBIT,
            'student' => CAP_PROHIBIT,
            'teacher' => CAP_PROHIBIT,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_PROHIBIT
        ),
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),

    'block/remlab_manager:addinstance' => array(
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'user' => CAP_PROHIBIT,
            'guest' => CAP_PROHIBIT,
            'student' => CAP_PROHIBIT,
            'teacher' => CAP_PROHIBIT,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_PROHIBIT
        ),
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),

    'block/remlab_manager:view' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'user' => CAP_PROHIBIT,
            'guest' => CAP_PROHIBIT,
            'student' => CAP_PROHIBIT,
            'teacher' => CAP_PROHIBIT,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_PROHIBIT
        )
    )

);