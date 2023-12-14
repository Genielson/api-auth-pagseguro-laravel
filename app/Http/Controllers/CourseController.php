<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

/** @package App\Http\Controllers */
class CourseController extends Controller
{

    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){}
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

        $courses = Course::all();
        return response()->json($courses);
        if(count($courses) > 0){
            return response()->json([$courses,200]);
        }else{
            return response()->json(['mensagem'=>"Não encontramos nenhum curso"], 404);
        }
    }


      /**
     * @param Request $request
     * @return array
     * @throws ValidationException
     */

    public function show(Request $request){

        if(isset($request->id) && $request->id != NULL){
            $course = Course::findOrFail($request->id);
            if(count($course) > 0){
                return response()->json([$course,200]);
            }else{
                return response()->json(['mensagem'=>"Não encontramos nenhum curso"], 404);
            }
        }else{
            return response()->json(['mensagem'=>"Por favor, envie o parametro para busca"],
            404);
        }

    }



    /**
     * @param Request $request
     * @return App\Traits\Iluminate\Http\Response|void
     * @throws ValidationException
     */

     public function update(Request $request){

        if($this->isUpdateValid($request)){
            $user = Auth::user();
            $course = Course::select("*")->where("id",$request->id)->get();
            if($course->user_id == $user->id){
                $course->delete();
                return response()->json(["mensagem" => "Curso deletado com sucesso"], 200);
            }else{
                return response()->json(["mensagem" =>
                "Não é possível deletar cursos de outros usuários"], 403);
            }
        }else{
            return response()->json(["mensagem" => "Algum parametro não foi enviado
            corretamente"],404);
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
            if(Course::create($request)){
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

        if(isset($request->id)){
            $user = Auth::user();
            $course = Course::select("*")->where("id",$request->id)->get();
            if($course->user_id == $user->id){
                $course->delete();
                return response()->json(["mensagem" => "Curso deletado com sucesso"], 200);
            }else{
                return response()->json(["mensagem" =>
                "Não é possível deletar cursos de outros usuários"], 403);
            }
        }else{
            return response()->json(["mensagem" => "Algum parametro não foi enviado
            corretamente"],404);
        }


     }

}
