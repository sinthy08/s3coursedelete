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
 * @category   external
 * @copyright  2023 Brain Station 23 Limited
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */

defined('MOODLE_INTERNAL') || die;

$functions = array(

    'local_newsfeed_view_full_newsfeed' => array(
        'classname'     => 'local_newsfeed_external',
        'methodname'    => 'view_full_newsfeed',
        'classpath'     => 'local/newsfeed/classes/external.php',
        'description'   => 'Simulate the view.php web interface page: trigger events, completion, etc...',
        'type'          => 'read',
        'ajax'          => true,
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),

    'local_newsfeed_view_newsfeed_by_newsid' => array(
        'classname'     => 'local_newsfeed_external',
        'methodname'    => 'newsfeed_by_newsid',
        'classpath' => 'local/newsfeed/classes/external.php',
        'description'   => 'Simulate the view.php web interface page: trigger events, completion, etc...',
        'type' => 'read',
        'ajax' => true,
        'services'      => array(MOODLE_OFFICIAL_MOBILE_SERVICE)
    ),
);
