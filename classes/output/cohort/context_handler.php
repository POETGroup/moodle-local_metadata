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
 * @package local_metadata
 * @author Mike Churchward <mike.churchward@poetgroup.org>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2016 POET
 */

/**
 * Cohort metadata context handler class..
 *
 * @package local_metadata
 * @copyright  2016 POET
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_metadata\output\cohort;

defined('MOODLE_INTERNAL') || die;

class context_handler extends \local_metadata\output\context_handler {

    /**
     * Return the instance of the context. Must be handled by the implementing class.
     * @return object The Moodle data record for the instance.
     */
    public function get_instance() {
        global $DB;
        if (empty($this->instance)) {
            if (!empty($this->instanceid)) {
                $this->instance = $DB->get_record('cohort', ['id' => $this->instanceid], '*', MUST_EXIST);
            } else {
                $this->instance = false;
            }
        }
        return $this->instance;
    }

    /**
     * Return the instance of the context. Must be handled by the implementing class.
     * @return object The Moodle context.
     */
    public function get_context() {
        if (empty($this->context)) {
            if (!empty($this->instance)) {
                $this->context = \context::instance_by_id($this->instance->contextid, MUST_EXIST);
            } else {
                $this->context = false;
            }
        }
        return $this->context;
    }

    /**
     * Return the instance of the context. Defaults to the home page.
     * @return object The Moodle redirect URL.
     */
    public function get_redirect() {
        return new \moodle_url('/cohort/edit.php', ['id' => $this->instanceid]);
    }

    /**
     * Check any necessary access restrictions and error appropriately. Must be implemented.
     * e.g. "require_login()". "require_capability()".
     * @return boolean False if access should not be granted.
     */
    public function require_access() {
        require_login();
        require_capability('moodle/cohort:manage', $this->context);
        return true;
    }

    /**
     * Implement if specific context settings can be added to a context settings page (e.g. Users / Accounts).
     */
    public function add_settings_to_context_page($navmenu) {
        // Add the settings page to the cohorts settings menu, if enabled.
        $navmenu->add('accounts',
            new \admin_externalpage('cohort_metadata', get_string('cohortmetadata', 'local_metadata'),
                new \moodle_url('/local/metadata/index.php', ['contextlevel' => CONTEXT_COHORT]), ['moodle/site:config']),
            'cohorts');
        return true;
    }
}