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
 * Block to manage the configuration of remote labs
 *
 * @package    block_remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Class that defines the RemlabManager block
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_remlab_manager extends block_list {

    /**
     * Init function for the Remlab Manager block
     */
    public function init() {
        $this->title = get_string('block_title', 'block_remlab_manager');
    }

    /**
     * Specialization function for the Remlab Manager block
     */
    public function specialization() {
        global $COURSE;

        if (!empty($this->config) && !empty($this->config->sarlab_lti_url)) {
            $this->sarlabltiurl = $this->config->sarlab_lti_url;
        }

        $this->urleditlocal = new moodle_url('/blocks/remlab_manager/view.php',
            array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'edit' => 1, 'sesskey' => sesskey()));
        $this->urldeletelocal = new moodle_url('/blocks/remlab_manager/view.php',
            array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'delete' => 1));
        $this->urlnewlocal = new moodle_url('/blocks/remlab_manager/view.php',
            array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'sesskey' => sesskey()));
    }

    /**
     * Get content function for the Remlab Manager block
     */
    public function get_content() {

        global $CFG, $SESSION, $USER;
        require_once($CFG->dirroot . '/mod/ejsapp/locallib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        if (!has_capability('block/remlab_manager:view', $this->page->context)) {
            return null;
        }

        $context = context_system::instance();
        $experiences = get_showable_experiences($USER->username);
        $SESSION->block_remlab_manager_list_experiences = $experiences;

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        if (!empty($experiences)) {
            $experienceid = optional_param('experience', -1, PARAM_INT);
            $this->content->items[0] = html_writer::start_tag('form', array('method' => 'post')) .
                html_writer::select($experiences, 'experience', $experienceid, true,
                    array('class' => 'remlab_select'));
                $this->content->items[1] = html_writer::empty_tag('input',
                    array('class' => 'remlab_button', 'type' => 'submit', 'formaction' => $this->urleditlocal,
                    'value' => get_string('configure_existing_local_experience', 'block_remlab_manager')));
                $this->content->items[1] .= html_writer::empty_tag('input',
                    array('class' => 'remlab_button', 'type' => 'submit', 'formaction' => $this->urldeletelocal,
                    'value' => get_string('delete_existing_local_experience', 'block_remlab_manager')));
            $this->content->items[1] .= html_writer::end_tag('form');
            $this->content->items[2] = html_writer::label(get_string('or', 'block_remlab_manager'), null);
        } else {
            $this->content->items[0] = '';
            $this->content->items[1] = '';
            $this->content->items[2] = '';
        }
        $this->content->items[3] = html_writer::link($this->urlnewlocal,
            get_string('configure_new_local_experience', 'block_remlab_manager'));
        if  (get_capability_info('ltisource/sarlab:editexp')) {
            if (has_capability('ltisource/sarlab:editexp', $context, $USER->id, false) &&
                !empty($this->sarlabltiurl)) {
                $this->content->items[4] = html_writer::link($this->sarlabltiurl,
                    get_string('go_to_sarlab', 'block_remlab_manager'));
            }
        }
        return $this->content;
    }

    /**
     * Applicable formats for the Remlab Manager block
     */
    public function applicable_formats() {
        return array('all' => true, 'my' => true, 'tag' => false);
    }

    /**
     * Add custom html attributes to aid with theming and styling
     *
     * @return array
     */
    public function html_attributes() {
        $attributes = parent::html_attributes();
        $attributes['class'] .= ' block_'. $this->name(); // Append our class to class attribute.
        return $attributes;
    }

    /**
     * Enable global configuration
     */
    public function has_config() {
        return true;
    }

}