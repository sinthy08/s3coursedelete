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

$newsid = optional_param('newsid', 0, PARAM_INT);
$delete = optional_param('del', 0, PARAM_INT);

$PAGE->set_url(new moodle_url('/local/s3coursedelete/awscreds.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Newsfeed Edit');


//$mform = new awscreds($CFG->wwwroot . '/local/s3coursedelete/awscreds.php');
$mform = new awscreds($CFG->wwwroot . '/local/s3coursedelete/awscreds.php');
//
//if ($mform->is_cancelled()) {
//    // Go back to awscredsmanage.php page
//    redirect($CFG->wwwroot . '/local/s3coursedelete/awscredsmanage.php', get_string('cancelled_form', 'local_s3coursedelete'));
//
//} else if ($fromform = $mform->get_data()) {

    $fromform = $mform->get_data();
    var_dump($fromform); die;
    $manager = new manager();

//    if ($newsid != NULL) {
//        // We are updating an existing message.
//        global $DB;
//        $object = new stdClass();
//        $object->id = $newsid;
//        $object->newsimage = $fromform->newsimage;
//
//
//        $DB->update_record('s3coursedelete_awscreds', $object);
//        update_record_s3coursedelete_newsdetailurl($fromform, $newsid);
//        redirect($CFG->wwwroot . '/local/s3coursedelete/awscredsmanage.php', get_string('updated_form', 'local_s3coursedelete'));
//    }
//
//    // Insert.
//    $record_to_insert = new stdClass();
//    $record_to_insert->newsimage = $fromform->newsimage;
//    $record_to_insert->timecreated = time();
//
//    try {
//        $data = $DB->insert_record('s3coursedelete_awscreds', $record_to_insert, false);
//
//        insert_record_s3coursedelete_newsdetailurl($fromform);
//
//    } catch (dml_exception $e) {
//        return false;
//    }
//
//    // Go back to awscredsmanage.php page
//    redirect($CFG->wwwroot . '/local/s3coursedelete/awscredsmanage.php', get_string('created_form', 'local_s3coursedelete'));
//}
//
//if ($newsid) {
//    // Add extra data to the form.
//    global $DB;
//    $manager = new manager();
//    $message = $manager->get_message($newsid);
//    if (!$message) {
//        throw new invalid_parameter_exception('Message not found');
//    }
//    $mform->set_data($message);
//}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
