<?php
// This file is part of Moodle Course Rollover Plugin
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
 * @package     local_s3coursedelete
 * @author      Brain Station 23 Ltd.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_s3coursedelete\form\awscreds;
use local_s3coursedelete\manager;

global $DB, $COURSE, $CFG, $PAGE, $OUTPUT;
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/s3coursedelete/lib.php');

require_login();
$context = context_system::instance();

//$newsid = optional_param('newsid', 0, PARAM_INT);
//$delete = optional_param('del', 0, PARAM_INT);

$PAGE->set_url(new moodle_url('/local/s3coursedelete/awscreds.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Newsfeed Edit');


$mform = new awscreds($CFG->wwwroot . '/local/s3coursedelete/awscreds.php');

if ($mform->is_cancelled()) {
    // Go back to newsdetailsmanage.php page
    redirect($CFG->wwwroot . '/admin/search.php#linkmodules', get_string('cancelled_form', 'local_s3coursedelete'));

} else if ($fromform = $mform->get_data()) {

    $fromform = $mform->get_data();

    $data = $DB->get_record('local_s3coursedelete', ['id' => 1]);

    if (!$data) {
        $record_to_insert = new stdClass();
        $record_to_insert->accesskey = $fromform->accesskey;
        $record_to_insert->secret_accesskey = $fromform->secretaccesskey;
        $record_to_insert->region = $fromform->region;
        $record_to_insert->version = $fromform->version;
        $record_to_insert->bucketname = $fromform->bucketname;
        $record_to_insert->timecreated = time();

        $DB->insert_record('local_s3coursedelete', $record_to_insert);
    } else {
        $record_to_insert = new stdClass();
        $record_to_insert->id = 1;
        $record_to_insert->accesskey = $fromform->accesskey;
        $record_to_insert->secret_accesskey = $fromform->secretaccesskey;
        $record_to_insert->region = $fromform->region;
        $record_to_insert->version = $fromform->version;
        $record_to_insert->bucketname = $fromform->bucketname;
        $record_to_insert->timecreated = time();

        $DB->update_record('local_s3coursedelete', $record_to_insert);
    }
    redirect($CFG->wwwroot . '/admin/search.php#linkmodules', get_string('saved', 'local_s3coursedelete'));

}
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
