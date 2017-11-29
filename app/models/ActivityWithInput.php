<?php

interface ActivityWithInput {
	/**
	 * Returns a corresponding "student answer" model class for this activity template.
	 * @returns string
	 */
	public function getStudentAnswer();
}
