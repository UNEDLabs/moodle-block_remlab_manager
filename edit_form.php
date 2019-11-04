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
 * Minimalistic edit form
 *
 * @package    block
 * @subpackage remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_remlab_manager_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $USER;
        if  (get_capability_info('ltisource/enlarge:createexp')) {
            if (has_capability('ltisource/enlarge:createexp', context_system::instance(), $USER->id, false)) {
                // Section header title according to language file.
                $mform->addElement('header', 'enlarge_header', get_string('enlarge_header', 'block_remlab_manager'));

                // Indicate the LTI activity that connects to ENLARGE.
                $mform->addElement('text', 'config_enlarge_lti_url', get_string('enlarge_lti_url', 'block_remlab_manager'));
                $mform->setType('config_enlarge_lti_url', PARAM_TEXT);
            }
        }
    }

}