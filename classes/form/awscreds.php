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

namespace local_s3coursedelete\form;
use moodleform;
global $CFG;
require_once($CFG->libdir.'/formslib.php');

class awscreds extends moodleform {
    public function definition() {
        global $CFG, $DB;

        $mform = $this->_form; // Don't forget the underscore!

        $data = $DB->get_record('local_s3coursedelete', array('id' => 1));

        if($data != null) {
            $mform->addElement('header', 'header', get_string('addheader', 'local_s3coursedelete'));
            $mform->addElement('text', 'accesskey', get_string('accesskey', 'local_s3coursedelete'), null
            )->setValue($data->accesskey);
            $mform->addRule('accesskey', get_string('required'), 'required', null, 'client');
            $mform->setType('accesskey', PARAM_RAW);

            $mform->addElement('text', 'secretaccesskey', get_string('secretaccesskey', 'local_s3coursedelete'), null
            )->setValue($data->secret_accesskey);
            $mform->addRule('secretaccesskey', get_string('required'), 'required', null, 'client');
            $mform->setType('secretaccesskey', PARAM_RAW);

            $mform->addElement('text', 'region', get_string('region', 'local_s3coursedelete'), null
            )->setValue($data->region);
            $mform->addRule('region', get_string('required'), 'required', null, 'client');
            $mform->setType('region', PARAM_RAW);

            $mform->addElement('text', 'version', get_string('version', 'local_s3coursedelete'), null
            )->setValue($data->version);
            $mform->addRule('version', get_string('required'), 'required', null, 'client');
            $mform->setType('version', PARAM_RAW);

            $mform->addElement('text', 'bucketname', get_string('bucketname', 'local_s3coursedelete'), null
            )->setValue($data->bucketname);
            $mform->addRule('bucketname', get_string('required'), 'required', null, 'client');
            $mform->setType('bucketname', PARAM_RAW);

        } else {

            $mform->addElement('text', 'accesskey', get_string('accesskey', 'local_s3coursedelete')); // Add elements to your form
            $mform->addRule('accesskey', get_string('required'), 'required', null, 'client');
            $mform->setType('accesskey', PARAM_RAW);

            $mform->addElement('text', 'secretaccesskey', get_string('secretaccesskey', 'local_s3coursedelete')); // Add elements to your form
            $mform->addRule('secretaccesskey', get_string('required'), 'required', null, 'client');
            $mform->setType('secretaccesskey', PARAM_RAW);

            $mform->addElement('text', 'region', get_string('region', 'local_s3coursedelete')); // Add elements to your form
            $mform->addRule('region', get_string('required'), 'required', null, 'client');
            $mform->setType('region', PARAM_RAW);

            $mform->addElement('text', 'version', get_string('version', 'local_s3coursedelete')); // Add elements to your form
            $mform->addRule('version', get_string('required'), 'required', null, 'client');
            $mform->setType('version', PARAM_RAW);

            $mform->addElement('text', 'bucketname', get_string('bucketname', 'local_s3coursedelete')); // Add elements to your form
            $mform->addRule('bucketname', get_string('required'), 'required', null, 'client');
            $mform->setType('bucketname', PARAM_RAW);

        }
        $this->add_action_buttons();
    }

//    Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
