<?php

namespace App\Repositories;

use App\Http\Contracts\CourseRepositoryInterface;
use App\Models\Course;

class CourseRepository implements  CourseRepositoryInterface
{



    public function __construct(){

    }

    public function getCourseOfUser(\Illuminate\Http\Request $request)
    {
        try {
            if (isset($request->id) && $request->id != NULL) {
                $course = Course::findOrFail($request->id);
                if (count($course) > 0) {
                    return response()->json([$course, 200]);
                } else {
                    return response()->json(['mensagem' => "Não encontramos nenhum curso"], 404);
                }
            } else {
                return response()->json(['mensagem' => "Por favor, envie o parametro para busca"],
                    404);
            }
        }catch(\Exception $e){
            return response()->json(["mensagem" => " Houve um erro"], 500);
        }
    }

    public function updateCourseOfUser(\Illuminate\Http\Request $request)
    {
        // TODO: Implement updateCourseOfUser() method.
    }

    public function createNewCourse(\Illuminate\Http\Request $request)
    {
        // TODO: Implement createNewCourse() method.
    }
}
