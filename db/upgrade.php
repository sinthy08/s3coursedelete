<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * newsfeed external functions and service definitions.
 *
 * @package    local_newsfeed
 * @category   uninstall
 * @copyright  2023 Brain Station 23 Limited
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */

function xmldb_local_newsfeed_upgrade($oldversion)
{

    global $DB;
    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.


    if ($oldversion < 2023011405) {

        $table = new xmldb_table('newsfeed_newsdetailurl');
        $field1 = new xmldb_field('newstitle_bn', XMLDB_TYPE_TEXT, null, null, null, null, null, 'newstitle');
        $field2 = new xmldb_field('newssubtitle_bn', XMLDB_TYPE_TEXT, null, null, null, null, null, 'newssubtitle');
        $field3 = new xmldb_field('newsbody_bn', XMLDB_TYPE_TEXT, null, null, null, null, null, 'newsbody');


        // Conditonally launch add field email.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }

        // Conditonally launch add field tier.
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }
        if (!$dbman->field_exists($table, $field3)) {
            $dbman->add_field($table, $field3);
        }

        upgrade_plugin_savepoint(true, 2023011405, 'local', 'newsfeed');
    }

    return true;
}