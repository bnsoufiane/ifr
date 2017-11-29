<?php

class DefaultSchoolSeeder extends Seeder {

    public function run() {
        $this->createSchool();
        $this->createSecondSchool();
    }

    private function createSchool() {
        $school = new School();
        $school->name = 'Pinetrees Middle School';
        $school->save();

        $class = new SchoolClass(array(
            'name' => 'Mr White\'s Class',
            'minimum_score' => '65',
            'created_by' => $this->getTeacher()->id
        ));

        $school->classes()->save($class);

        $class->students()->sync(array($this->getStudent()->id));

        $school->series()->sync(array(1, 2, 3));

        $school->users()->saveMany(array(
            $this->getSchoolAdmin(),
            $this->getTeacher(),
            $this->getStudent()
        ));
    }

    private function createSecondSchool() {
        $school = new School();
        $school->name = 'Hogwarts';
        $school->save();

        $class = new SchoolClass(array(
            'name' => 'Charms Study Class',
            'minimum_score' => '65',
            'created_by' => $this->getTeacher(2)->id
        ));

        $school->classes()->save($class);

        $class->students()->sync(array(
            $this->getStudent(2)->id,
            $this->getStudent(3)->id
        ));

        $school->series()->sync(array(1, 2, 3));

        $school->users()->saveMany(array(
            $this->getSchoolAdmin(2),
            $this->getTeacher(2),
            $this->getTeacher(3),
            $this->getStudent(2),
            $this->getStudent(3)
        ));
    }

    private function getTeacher($number = false) {
        return User::where('username', '=', 'teacher' . ($number ? $number : ''))->first();
    }

    private function getStudent($number = false) {
        return User::where('username', '=', 'student' . ($number ? $number : ''))->first();
    }

    private function getSchoolAdmin($number = false) {
        return User::where('username', '=', 'school_admin' . ($number ? $number : ''))->first();
    }

}
