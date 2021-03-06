## 1. Content

This plugin lets users to manage the remote labs that can be later accessed using the Java or Javascript applications
created with Easy Java/Javascript Simulations (EjsS) and which are added to a Moodle course by means of the ejsapp module.

The Java applets or the Javascript applications should have been created with version 5.1 (build 20150613 or later) of
EjsS to work properly.

This plugin supports (but does not require) the use of ENLARGE for managing the connection to the remote labs. For more
information about ENLARGE visit https://www.nebsyst.com and https://irs.nebsyst.com. This plugin will not receive further
updates. For more updated versions, contact the authors and/or Nebulous Systems (contact@nebsyst.com).

## 2. License

Remote Lab Manager is free software: you can redistribute it and/or modify it under the terms of the GNU General
Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any
later version.

Remote Lab Manager is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
details.

The GNU General Public License is available on <http://www.gnu.org/licenses/>

## 3. Installation

If you downloaded this plugin from github, you will need to change the folder's name to remlab_manager. If you
downloaded it from Moodle.org, then you are fine.

This is a block plugin for Moodle so you should place the remlab_manager folder in your /blocks folder, inside
your Moodle installation directory.

When installing remlab manager for the first time, you will need to set a few variables:

   myFrontier_IP:    This variable defines the IP(s) address(es) of the ENLARGE system(s) used for managing the access to
                     the remote laboratories. If left empty, the plugin understands that ENLARGE is not used.

## 4. Dependencies

This block needs the ejsapp module to be of any use. It works with version 2.2 (or later) of EJSApp. You can find and
download it at https://moodle.org/plugins/view.php?plugin=mod_ejsapp, in the plugins section in the Moodle.org webpage
or at https://github.com/UNEDLabs.

## 5. Authors

Remlab Manager has been developed by:
  - Luis de la Torre (ldelatorre@dia.uned.es)

at the Computer Science and Automatic Control Department, Spanish Open University (UNED), Madrid, Spain.
