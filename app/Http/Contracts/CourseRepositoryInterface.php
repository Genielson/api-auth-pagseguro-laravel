<?php

namespace App\Http\Contracts;

interface CourseRepositoryInterface
{

    public function getCourseOfUser();
    public function updateCourseOfUser();
    public function createNewCourse();

}
