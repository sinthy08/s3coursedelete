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
 *
 * @category   external
 * @copyright  2022 Brain Statin 23 LTD
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */
namespace local_s3coursedelete\event;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot .'/local/s3coursedelete/lib.php');
require_once($CFG->dirroot . '/local/aws/sdk/aws-autoloader.php');

class observers {
       public static function on_course_deleted (\core\event\course_deleted $event) {
        global $CFG, $DB, $USER;
        require_once($CFG->dirroot."/lib/weblib.php");
        require_once($CFG->dirroot."/user/lib.php");
        require_once($CFG->dirroot."/user/editlib.php");
        require_once($CFG->dirroot."/user/profile/lib.php");

        $creds = $DB->get_record('local_s3coursedelete', ['id' => 1]);
        $folderName = $event->other['fullname'];
        $bucketName = $creds->bucketname;

       try {
            $s3Client = new S3Client([
                'version' => $creds->version,
                'region'  => $creds->region,
                'credentials' => [
                    'key'    => $creds->accesskey,
                    'secret' => $creds->secret_accesskey,
                ],
            ]);

            // Ensure the folder name ends with a '/'
            if (!str_ends_with($folderName, '/')) {
                $folderName .= '/';
            }

            try {
                // List all objects under the folder (prefix)
                $objects = $s3Client->listObjectsV2([
                    'Bucket' => $bucketName,
                    'Prefix' => $folderName,
                ]);

                // Check if there are any objects to delete
                if (isset($objects['Contents']) && count($objects['Contents']) > 0) {
                    // Create an array of objects to delete
                    $deleteKeys = array_map(function ($object) {
                        return ['Key' => $object['Key']];
                    }, $objects['Contents']);

                    // Delete the objects
                    $s3Client->deleteObjects([
                        'Bucket' => $bucketName,
                        'Delete' => [
                            'Objects' => $deleteKeys,
                        ],
                    ]);

                    echo "\n". get_string('deletion', 'local_s3coursedelete').  count($deleteKeys) . get_string('object', 'local_s3coursedelete').$folderName ."\n";
                } else {
                    echo get_string('noobject', 'local_s3coursedelete'). $folderName ." \n";
                }
            } catch (AwsException $e) {
                // Output error message if fails
                echo get_string('error', 'local_s3coursedelete') . $e->getMessage() . "\n";
            }
        } catch (AwsException $e) {
           echo get_string('error', 'local_s3coursedelete') . $e->getMessage() . "\n";
       }
    }
}
