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

namespace local_s3coursedelete;

use dml_exception;
use stdClass;

class manager {

    /** Insert the data into our database table.
     * @param string $message_text
     * @param string $message_type
     * @return bool true if successful
     */
    public function create_message(array $newsimage, array $content): bool
    {
        global $DB, $COURSE;
        $record_to_insert = new stdClass();
        $record_to_insert->course = $COURSE->id;
        $record_to_insert->newsimage = $newsimage;
        $record_to_insert->content = $content;
        $record_to_insert->timecreated = time();

        try {
            return $DB->insert_record('s3coursedelete_newsdetails', $record_to_insert, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /** Gets all messages that have not been read by this user
     * @param int $userid the user that we are getting messages for
     * @return array of messages
     */
    public function get_messages(int $userid): array
    {
        global $DB;
        $sql = "SELECT lm.id, lm.messagetext, lm.messagetype 
            FROM {local_s3coursedelete} lm 
            LEFT OUTER JOIN {local_s3coursedelete_read} lmr ON lm.id = lmr.newsid AND lmr.userid = :userid 
            WHERE lmr.userid IS NULL";
        $params = [
            'userid' => $userid,
        ];
        try {
            return $DB->get_records_sql($sql, $params);
        } catch (dml_exception $e) {
            // Log error here.
            return [];
        }
    }

    /** Gets all messages
     * @return array of messages
     */
    public function get_all_messages(): array {
        global $DB;
        return $DB->get_records('local_s3coursedelete');
    }

    /** Mark that a message was read by this user.
     * @param int $message_id the message to mark as read
     * @param int $userid the user that we are marking message read
     * @return bool true if successful
     */
    public function mark_message_read(int $message_id, int $userid): bool
    {
        global $DB;
        $read_record = new stdClass();
        $read_record->newsid = $message_id;
        $read_record->userid = $userid;
        $read_record->timeread = time();
        try {
            return $DB->insert_record('local_s3coursedelete_read', $read_record, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    /** Get a single message from its id.
     * @param int $newsid the message we're trying to get.
     * @return object|false message data or false if not found.
     */
    public function get_message(int $newsid)
    {
        global $DB;
        return $DB->get_record('s3coursedelete_newsdetails', ['id' => $newsid]);
    }

    /** Update details for a single message.
     * @param int $newsid the message we're trying to get.
     * @param string $message_text the new text for the message.
     * @param string $message_type the new type for the message.
     * @return bool message data or false if not found.
     */
    public function update_message(int $newsid, int $course, string $newsimage, string $content)
    {
        global $DB;
        $object = new stdClass();
        $object->id = $newsid;
        $object->course = $course;
        $object->newsimage = $newsimage;
        $object->content = $content;
        $object->timecreated = time();

        return $DB->update_record('s3coursedelete_newsdetails', $object, false, ['id' => 2]);
    }

    /** Update the type for an array of messages.
     * @return bool message data or false if not found.
     */
    public function update_messages(array $newsids, $type): bool
    {
        global $DB;
        list($ids, $params) = $DB->get_in_or_equal($newsids);
        return $DB->set_field_select('local_s3coursedelete', 'messagetype', $type, "id $ids", $params);
    }

    /** Delete a message and all the read history.
     * @param $newsid
     * @return bool
     * @throws \dml_transaction_exception
     * @throws dml_exception
     */
    public function delete_message($newsid)
    {
        global $DB;
        $transaction = $DB->start_delegated_transaction();
        $deletedMessage = $DB->delete_records('local_s3coursedelete', ['id' => $newsid]);
        $deletedRead = $DB->delete_records('local_s3coursedelete_read', ['newsid' => $newsid]);
        if ($deletedMessage && $deletedRead) {
            $DB->commit_delegated_transaction($transaction);
        }
        return true;
    }

    /** Delete all messages by id.
     * @param $newsids
     * @return bool
     */
    public function delete_messages($newsids)
    {
        global $DB;
        $transaction = $DB->start_delegated_transaction();
        list($ids, $params) = $DB->get_in_or_equal($newsids);
        $deletedMessages = $DB->delete_records_select('local_s3coursedelete', "id $ids", $params);
        $deletedReads = $DB->delete_records_select('local_s3coursedelete_read', "newsid $ids", $params);
        if ($deletedMessages && $deletedReads) {
            $DB->commit_delegated_transaction($transaction);
        }
        return true;
    }
}
