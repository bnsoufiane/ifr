<?php

// A base interface for an activity template.

interface ActivityTemplate {
    /**
     * Returns metadata for the activity template.
     *
     * "name" - user-friendly name of the activity template.
     * "template" - student-facing template.
     * "admin_template" - admin-facing template.
     *
     * @return array
     */
    static function getMetaData();

    /**
     * De-serializes the activity template data from a raw data array (usually deserialized
     * from JSON), and saves it to a database.
     */
    function saveFromArray($data);
    
    function delete_activity();
}
