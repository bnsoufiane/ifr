<?php

namespace Curotec\Presenters;

class SchoolPresenter extends Presenter {
	private $school = null;

	public function __construct($school) {
		$this->school = $school;
	}

	public function id() {
		return $this->school->id;
	}

	public function name() {
		return $this->school->name;
	}

    public function school_district() {
        if($this->school->school_district()->first()!==null){
            return $this->school->school_district()->first()->name;
        }
        return $this->school->school_district;
    }

	public function adminsNames() {
		$admins = array();
		
		$this->school->admins()->get()->each(function ($admin) use (&$admins) {
			$admins[] = link_to_route(
				'admin.users.edit',
				$admin->first_name . ' ' . $admin->last_name,
				$admin->id
			);
		});

		return join(', ', $admins);
	}
}
