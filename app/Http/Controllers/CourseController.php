<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

/** @package App\Http\Controllers */
class CourseController extends Controller
{

    private $repository;
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CourseRepository $courseRepository){
        $this->repository = $courseRepository;
    }
    /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function isRegisterValid(Request $request)
    {

        return  $this->validate(
            $request,
            [
                'name' => 'required',
                'description' => 'required',
                'amount_hour' => 'required',
                'amount_module' => 'required',
                'user_id' => 'required'
            ]
        );
    }

    public function isUpdateValid(Request $request)
    {
        return $this->validate($request, [
            'id' => 'required|exists:courses,id',
            'name' => 'sometimes|required',
            'description' => 'sometimes|required',
            'amount_hour' => 'sometimes|required',
            'amount_module' => 'sometimes|required',
            'user_id' => 'sometimes|required',
        ]);
    }

    public function index(){
        try{
            return $this->repository->getAllCourses();
        }catch(Exception $e){
            return response()->json(['mensagem' => 'Houve um erro'],500);
        }

    }


      /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
       * @throws ValidationException
     */

    public function show(Request $request){
        try{
            return $this->repository->getCourseOfUser($request);
        }catch (Exception){
            return response()->json(['mensagem' => 'Houve um erro'],500);
        }
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */

     public function update(Request $request)
        {
            try {
                if($this->isUpdateValid($request)) {
                    $user = Auth::user();
                   return $this->repository->updateCourseOfUser($request,$user);
                } else {
                    return response()->json(["mensagem" => "Algum parametro não foi enviado corretamente"], 404);
                }
            }catch (Exception){
                return response()->json(["mensagem" => "Houve um erro"], 500);
            }
        }



    public function listUserCourse(){
        $user = Auth::user();
        $courses = Course::where('user_id', $user->id)->get();
        if($courses > 0){
            return response()->json([$courses,200]);
        }else{
            return response()->json(['mensagem'=>"Não encontramos nenhum curso"], 404);
        }
    }

        /**
         * @param Request $request
         * @return App\Traits\Iluminate\Http\Response|void
         * @throws ValidationException
         */
    public function store(Request $request)
    {


        if ($this->isRegisterValid($request)) {

            if(Course::create($request->all())){
                return response()->json(['mensagem'=>' Curso criado com sucesso '], 201);
            }else{
                return response()->json(['mensagem' => 'Erro ao criar o curso'], 500);
            }

        }else{
            return response()->json(['mensagem'=>'Algum parametro não foi enviado corretamente'],404);
        }
    }

    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|void
     * @throws ValidationException
     */



     public function destroy(Request $request){




     }

}
