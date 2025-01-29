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
        $newsid = optional_param('newsid', 0, PARAM_INT);

        $mform = $this->_form; // Don't forget the underscore!

        $data = $DB->get_record('s3coursedelete_newsdetailurl', array('id' => $newsid));

        if($data != null) {
            $mform->addElement('header', 'header', get_string('addheader', 'local_s3coursedelete'));

            $mform->addElement('text', 'newstitle', get_string('newstitle', 'local_s3coursedelete'), null
            )->setValue($data->newstitle);
            $mform->addRule('newstitle', get_string('required'), 'required', null, 'client');
            $mform->setType('newstitle', PARAM_RAW);

            $mform->addElement('text', 'newstitle_bn', get_string('newstitle', 'local_s3coursedelete')." (Bengali)")->setValue($data->newstitle_bn); // Field for bengali
            $mform->setType('newstitle_bn', PARAM_RAW);


            $mform->addElement('text', 'newssubtitle', get_string('newssubtitle', 'local_s3coursedelete'), null
            )->setValue($data->newssubtitle);
            $mform->addRule('newssubtitle', get_string('required'), 'required', null, 'client');
            $mform->setType('newssubtitle', PARAM_RAW);

            $mform->addElement('text', 'newssubtitle_bn', get_string('newssubtitle', 'local_s3coursedelete')." (Bengali)")->setValue($data->newssubtitle_bn); // Field for bengali
            $mform->setType('newssubtitle_bn', PARAM_RAW);

            $mform->addElement('filemanager', 'newsimage', get_string('newsimage', 'local_s3coursedelete'), null
            )->setValue($data->newstitle);
            $mform->addRule('newsimage', get_string('required'), 'required', null, 'client');

            $mform->addElement
            (
                'editor',
                'newsbody',
                get_string('newsbody', 'local_s3coursedelete'),
                null
            )->setValue(array('text' => $data->newsbody));

            $mform->addElement
            (
                'editor',
                'newsbody_bn',
                get_string('newsbody', 'local_s3coursedelete')." (Bengali)",
                null
            )->setValue(array('text' => $data->newsbody_bn));


            $mform->addElement('date_selector', 'dateofpublish', get_string('dateofpublish', 'local_s3coursedelete'), null
            )->setValue($data->dateofpublish);
            $mform->addRule('dateofpublish', get_string('required'), 'required', null, 'client');
            $mform->setDefault('dateofpublish', $data->dateofpublish);

            $options = array(
                'Draft' => get_string('draft', 'local_s3coursedelete'),
                'Published' => get_string('publish', 'local_s3coursedelete'),
            );
            $mform->addElement('select', 'status', get_string('status', 'local_s3coursedelete'), $options, null)->setValue($data->status);

        } else {

            $mform->addElement('text', 'newstitle', get_string('newstitle', 'local_s3coursedelete')); // Add elements to your form
            $mform->addRule('newstitle', get_string('required'), 'required', null, 'client');
            $mform->setType('newstitle', PARAM_RAW);

            $mform->addElement('text', 'newstitle_bn', get_string('newstitle', 'local_s3coursedelete')." (Bengali)"); // Field for bengali
            $mform->setType('newstitle_bn', PARAM_RAW);


            $mform->addElement('text', 'newssubtitle', get_string('newssubtitle', 'local_s3coursedelete')); // Add elements to your form
            $mform->addRule('newssubtitle', get_string('required'), 'required', null, 'client');
            $mform->setType('newssubtitle', PARAM_RAW);

            $mform->addElement('text', 'newssubtitle_bn', get_string('newssubtitle', 'local_s3coursedelete')." (Bengali)"); // Field for bengali
            $mform->setType('newssubtitle_bn', PARAM_RAW);


            $mform->addElement('filemanager', 'newsimage', get_string('newsimage', 'local_s3coursedelete')); // Add elements to your form
            $mform->addRule('newsimage', get_string('required'), 'required', null, 'client');

            $mform->addElement('editor', 'newsbody', get_string('newsbody', 'local_s3coursedelete')); // Add elements to your form
            $mform->addRule('newsbody', get_string('required'), 'required', null, 'client');

            $mform->addElement('editor', 'newsbody_bn', get_string('newsbody', 'local_s3coursedelete')." (Bengali)"); // Field for bengali
            $mform->setType('newsbody_bn', PARAM_RAW);

            $mform->addElement('date_selector', 'dateofpublish', get_string('dateofpublish', 'local_s3coursedelete')); // Add elements to your form
            $mform->addRule('dateofpublish', get_string('required'), 'required', null, 'client');

            $options = array(
                'Draft' => get_string('draft', 'local_s3coursedelete'),
                'Published' => get_string('publish', 'local_s3coursedelete'),
            );
            $mform->addElement('select', 'status', get_string('status', 'local_s3coursedelete'), $options);
        }
        $this->add_action_buttons();
    }

//    Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
