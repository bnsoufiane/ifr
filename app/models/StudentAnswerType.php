<?php

interface StudentAnswerType {
	/**
	 * @returns array
	 */
	public function getMetaData();

	/**
	 * @param array $data
	 * @param ActivityTemplate $activityTemplate
	 */
	public function saveFromArray($data, $activityTemplate);
}
