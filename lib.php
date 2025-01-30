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
 * @package local_s3coursedelete
 * @copyright 2023 Brain Station 23 LTD.
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

function encryptData($data, $password) {
    $key = hash('sha256', $password, true); // Ensure a 32-byte key
    $iv = openssl_random_pseudo_bytes(16); // Generate a random IV
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . $encrypted); // Store IV + encrypted data
}

function decryptData($encryptedData, $password) {
    $key = hash('sha256', $password, true); // Ensure a 32-byte key
    $data = base64_decode($encryptedData);
    $iv = substr($data, 0, 16); // Extract IV
    $encrypted = substr($data, 16); // Extract encrypted data
    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
}
