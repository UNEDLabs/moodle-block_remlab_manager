// This file is part of the Moodle module "EJSApp"
//
// EJSApp is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either moodle_version 3 of the License, or
// (at your option) any later moodle_version.
//
// EJSApp is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// The GNU General Public License is available on <http://www.gnu.org/licenses/>
//
// EJSApp has been developed by:
// - Luis de la Torre: ldelatorre@dia.uned.es
// - Ruben Heradio: rheradio@issi.uned.es
//
// at the Computer Science and Automatic Control, Spanish Open University
// (UNED), Madrid, Spain

/**
 * Call required actions when a user leaves a remote lab activity.
 *
 * @package    mod
 * @subpackage ejsapp
 * @copyright  2016 Luis de la Torre and Ruben Heradio
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function($) {
    var t = {
        disable: function() {
            // Disable non-editable elements when editing an ENLARGE experience
            $("#id_practiceintro").prop("disabled", true);
            $("#id_ip").prop("disabled", true);
            $("#id_port").prop("disabled", true);
            // Enable them again before unloading the webpage so the form validates correctly
            $("#id_submitbutton").click(function(){
                $("#id_practiceintro").prop("disabled", false);
                $("#id_ip").prop("disabled", false);
                $("#id_port").prop("disabled", false);
            });
        }
    };
    return t;
});