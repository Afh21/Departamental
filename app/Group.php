<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    protected $guarded = ['group_assigned', 'user_teacher_director'];

    //Relaciones

    // Muchos a Muchos - Profesores & Grupos
    public function GroupUsers(){
        return $this->belongsToMany('App\User','group_user', 'group_id', 'user_id');
    }

    // Muchos a Muchos - Groups & Materias
    public function GroupMaths(){
        return $this->belongsToMany('App\Math', 'group_math', 'group_id', 'math_id');
    }

    // Uno a muchos (Director de Grupo)
    public function director(){
        return $this->belongsTo('App\User','user_teacher_director');
    }

    // Se activa para agregar estudiantes al Grupo
    public function students(){
        return $this->hasMany('App\User', 'group_id');
    }

    public function notes(){
        return $this->hasMany('App\Note');
    }

}
