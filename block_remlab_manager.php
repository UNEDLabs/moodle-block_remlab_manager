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
 * Block to manage the configuration of remote labs
 *
 * @package    block
 * @subpackage remlab_manager
 * @copyright  2015 Luis de la Torre
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Class that defines the RemlabManager block
 */
class block_remlab_manager extends block_list {

    /**
     * Init function for the RemlabManager block
     */
    function init() {
      $this->title = get_string('block_title', 'block_remlab_manager');
    }

    public function specialization() {
        if (empty($this->config->use_sarlab)) $this->use_sarlab = '0';
        else $this->use_sarlab = $this->config->use_sarlab;

        if (empty($this->config->sarlab_instance)) $this->sarlab_instance = '0';
        else $this->sarlab_instance = $this->config->sarlab_instance;
    }

    /**
     * Get content function for the RemlabManager block
     */
    function get_content() {

        global $CFG, $COURSE, $SESSION, $OUTPUT;
        require_once($CFG->dirroot . '/mod/ejsapp/locallib.php');

        if($this->content !== NULL) {
            return $this->content;
        }

        if (!has_capability('moodle/course:manageactivities', $this->page->context)) {
            return null;
        }

        $list_showable_experiences = get_showable_experiences();
        $SESSION->block_remlab_manager_list_experiences = $list_showable_experiences;

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $practiceintro_index = optional_param('experience', -1, PARAM_INT);
        $url_edit = new moodle_url('/blocks/remlab_manager/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'edit' => 1, 'sesskey' => sesskey()));
        $url_delete = new moodle_url('/blocks/remlab_manager/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'delete' => 1));
        $this->content->items[0] = html_writer::start_tag('form', array('method'=>'post'));
        $this->content->items[1] = html_writer::select($list_showable_experiences, 'experience', $practiceintro_index, true);
        if (empty($list_showable_experiences)) {
            $this->content->items[2] = html_writer::empty_tag('input', array('type'=>'submit', 'formaction'=>$url_edit, 'value'=>get_string('configure_existing_experience', 'block_remlab_manager'), 'disabled'));
            $this->content->items[3] = html_writer::empty_tag('input', array('type'=>'submit', 'formaction'=>$url_delete, 'value'=>get_string('delete_existing_experience', 'block_remlab_manager'), 'disabled'));
        } else {
            $this->content->items[2] = html_writer::empty_tag('input', array('type'=>'submit', 'formaction'=>$url_edit, 'value'=>get_string('configure_existing_experience', 'block_remlab_manager')));
            $this->content->items[3] = html_writer::empty_tag('input', array('type'=>'submit', 'formaction'=>$url_delete, 'value'=>get_string('delete_existing_experience', 'block_remlab_manager')));
        }
        $this->content->items[4] = html_writer::end_tag('form');
        $this->content->items[5] = html_writer::label(get_string('or', 'block_remlab_manager'), null);
        $url_new = new moodle_url('/blocks/remlab_manager/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'sesskey' => sesskey()));
        $this->content->items[6] = html_writer::link($url_new, get_string('configure_new_experience', 'block_remlab_manager'));
        return $this->content;
    }

    /**
     * Applicable formats for the RemlabManager block
     */
    function applicable_formats() {
      return array('all' => true, 'my' => true, 'tag' => false);
    }

    /**
     * Add custom html attributes to aid with theming and styling
     *
     * @return array
     */
    function html_attributes() {
        $attributes = parent::html_attributes();
        $attributes['class'] .= ' block_'. $this->name(); // Append our class to class attribute
        return $attributes;
    }

    /**
     * Enable global configuration
     */
    function has_config() {return true;}

}