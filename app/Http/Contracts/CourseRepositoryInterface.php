<?php

namespace App\Http\Contracts;

use Illuminate\Http\Request;

interface CourseRepositoryInterface
{

    public function getCourseOfUser(Request $request);
    public function updateCourseOfUser(Request $request);
    public function createNewCourse(Request $request);

}
