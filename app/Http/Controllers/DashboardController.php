<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Town;
use App\Departments;
use DB;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard.app');
    }

    public function getAdmins(){
        $admin = DB::table('role_user')
                                    ->join('users', 'role_user.user_id', '=', 'users.id')
                                    ->join('roles', 'role_user.role_id', '=', 'roles.id')
                                    ->where('roles.name', '=', 'Administrator')
                                    ->select('users.name', 'users.id', 'users.email', 'users.user_lastname', 'users.user_state')
                                    ->get();

        return view('dashboard.views.administrators')->with('admin', $admin);
    }

    public function find($id){
        $user = User::find($id);
        $town = $user->town->dept_id;

        $dept = Departments::all();

        return response()->json([
            'user' => $user,
            'dept' => $dept
        ]);
    }

    public function updateAdmin(Request $request, $id){

        if($request->ajax()){
            $user = User::find($id);
            $user->user_type        = $request->type;
            $user->user_identity    = $request->identity;
            $user->name             = $request->name;
            $user->user_lastname    = $request->lastname;
            $user->email            = $request->email;

            if($request->password != ""){
                $user->password = bcrypt($request->password);
            }

            $user->user_genre       = $request->genre;
            $user->user_birthday    = $request->birthday;
            $user->user_age         = $request->age;
            $user->user_address     = $request->address;
            $user->user_phone       = $request->phone;
            $user->user_blood       = $request->blood;
            $user->user_profession  = $request->profession;

            if($request->active){
                $user->user_state = 'enabled';
            }

            if($request->disabled){
                $user->user_state = 'disabled';
            }

            $user->save();

            return response()->json([
                'msn' => 'Datos actualizados exitosamente'
            ]);
        }



    }
}
