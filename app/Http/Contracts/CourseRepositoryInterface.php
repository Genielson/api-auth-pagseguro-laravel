<?php

namespace App\Http\Contracts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

interface CourseRepositoryInterface
{

    public function getAllCourses();
    public function getCourseOfUser(Request $request);
    public function updateCourseOfUser(Request $request, Auth $user);
    public function createNewCourse(Request $request);

}
