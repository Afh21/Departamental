@extends('dashboard.app')

@section('content')

        <div class="row">
            @foreach($admin as $admin)
                    <div class="col l4 s12 m7">
                        <div class="card">
                            <div class="image" style="text-align: center">
                                <img src="{{asset('images/back.jpg')}}" class="circle" style="height: 100px; width: 100px; margin-top: 15px">
                            </div>
                            <div class="card-content center">
                                @role('administrator') <i class="fa fa-diamond"></i> @endrole{{$admin->name}}
                                <span> <b>{{$admin->user_lastname}}</b></span> <br>
                                <span style="font-size: 12px"> {{$admin->email}} </span>
                                <span class="right"> @if($admin->user_state == 'enabled') <i class="fa fa-circle tooltiped circle" style="color: #4caf50;box-shadow: 2px 1px 6px 4px #00b0ff " data-position="right" data-delay="50" data-tooltip="Activo"></i> @else <i class="fa fa-circle tooltiped" style="color: #424242 " data-position="right" data-delay="50" data-tooltip="Inactivo"></i> @endif</span>
                            </div>
                            <div class="card-action center">
                                <ul style="padding: 0px 20px">
                                    <li data-id="{{$admin->id}}" style="display: inline-block; padding-left: 10px">
                                        <button class="btn-floating waves-circle white tooltiped edit modal-trigger" data-target="modalEdit" data-id="{{$admin->id}}" data-position="bottom" data-delay="50" data-tooltip="Editar">
                                            <i class="fa fa-edit" style="color:black"></i>
                                        </button>
                                    </li>

                                    <li data-id="{{$admin->id}}" style="display: inline-block; padding-left: 10px">
                                        <button class="btn-floating waves-circle white tooltiped profile" data-position="bottom" data-delay="50" data-tooltip="Ver Perfil">
                                            <i class="fa fa-eye" style="color:grey"></i>
                                        </button>
                                    </li>

                                    <li data-id="{{$admin->id}}" style="display: inline-block; padding-left: 10px">
                                        <button class="btn-floating waves-circle white tooltiped delete" data-position="bottom" data-delay="50" data-tooltip="Eliminar">
                                            <i class="fa fa-trash-o" style="color:red"></i>
                                        </button>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
            @endforeach
        </div>

        <div id="create">
            @include('dashboard.views.administrators.formCreate')
        </div>

        <div id="edit">
            @include('dashboard.views.administrators.formEdit')
        </div>

@endsection

@section('js')
    <script>
        $(document).ready(function(){

            $('.tooltiped').tooltip();
            $('.modal-trigger').leanModal({dismissible: false});
            $('select').material_select();


            $('.modal-trigger.create').click(function(){
                $(this).parent('a').removeClass('collapsible-header');
                $('#modalCreate').openModal();

                $('button.crear').click(function (){
                    var routeSave = 'http://localhost:8000/admins/save';
                    var data    = $('#formCreate').serialize();
                    var token   = $('#tokenC').attr('value');

                    alert(data);

                    $.ajax({
                        url: routeSave,
                        type: 'POST',
                        headers: 	{ 'X-CSRF-TOKEN': token },
                        dataType: 	'json',
                        data:        data,
                        success: function(res) {
                            Materialize.toast(res.msn, 5000);
                            window.location.href = 'http://localhost:8000/admins';
                        },
                        fail: function (){
                            alert('No se completo el registro');
                        }
                    });
                });
            });

            $('button.modal-close').click(function (){
                $('a.menu').addClass('collapsible-header');
                window.location.href = 'http://localhost:8000/admins';
            });

            $('ul#type input').each(function (){
                var id = $(this).attr('value');
                $(this).click(function (){
                  if(id == 3) {
                      $('select#user_type').append($("<option value='TI'> Tarjeta de Identidad </option> <option value='RC'> Registro Civil </option>"));
                      $('select#user_type').material_select();
                      $('div.groups').show();
                      $('div.groupsTeacher').hide();
                  }
                  else if(id == 2){
                      $('div.groupsTeacher').show();
                      $('div.groups').hide();
                  }
                  else {
                      $('div.groups').hide();
                      $('div.groupsTeacher').hide();
                  }
                })
            })

            $('.modal-trigger.edit').click(function(){
                var id = $(this).parent('li').attr('data-id');
                var r  = " http://localhost:8000/admins/find/"+id+ " ";

                $.get(r, function(){
                }).done(function (res){
                    $('#modalEdit').openModal();
                    $('#identity').val(res.user.user_identity);
                    $('#name').val(res.user.name);
                    $('#lastname').val(res.user.user_lastname);
                    $('#email').val(res.user.email);

                    $('#genre option').each(function(){
                        if($(this).val() == res.user.user_genre ) {
                            $(this).attr('selected', true);
                            if (res.user.user_genre == 'M') {
                                $(this).text('Hombre');
                            } else {
                                $(this).text('Hombre');
                            }
                        }
                    });

                    $('#birthday').val(res.user.user_birthday);
                    $('#age').val(res.user.user_age);
                    $('#address').val(res.user.user_address);
                    $('#phone').val(res.user.user_phone);
                    $('#blood').val(res.user.user_blood);

                    if(res.user.user_state == 'enabled'){
                        $('#active').attr('checked', true);
                    }else{
                        $('#disabled').attr('checked', true);
                    }

                    $('#profession').val(res.user.user_profession);
                    $('button.update').attr('data-id', res.user.id);

                }).fail(function(){
                    alert('No se envio nada');
                });
            });
            $('button.update').click(function(){
                var id      = $(this).attr('data-id');
                var route   = "http://localhost:8000/admins/find/"+id+"/update";
                var form    = $('#formEdit').serialize();
                var token   = $('#token').attr('value');

                $.ajax({
                    url: route,
                    headers: 	{ 'X-CSRF-TOKEN': token },
                    type: 		'PUT',
                    dataType: 	'json',
                    data:        form,
                    success: function(res){
                       if(res.msn != null){
                           alert('Datos actualizados correctamente');
                           window.location.href = 'http://localhost:8000/admins';
                       }
                    }
                })

            });

            $('div.groups').hide();
            $('div.groupsTeacher').hide();
            $.get('http://localhost:8000/extras', function (){
            }).done(function (res){
                $(res.msn).each(function (key){
                    $('select#groups').append("<option value="+res.msn[key].id+"> "+res.msn[key].group_name+"</option>");
                    $('select#groups').material_select();

                    $('select#groupsMultiple').append("<option value="+res.msn[key].id+"> "+res.msn[key].group_name+"</option>");
                    $('select#groupsMultiple').material_select();
                });
                $(res.math).each(function (key){
                    $('select#mathMultiple').append("<option value="+res.math[key].id+"> "+res.math[key].math_code+" - "+res.math[key].math_name+"</option>");
                    $('select#mathMultiple').material_select();
                });
            }).fail(function (){
                alert('Fallo la consulta de grupos');
            })
        });
    </script>
@endsection