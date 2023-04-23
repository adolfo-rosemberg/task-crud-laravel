@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-12">
        <div>
            <h2 class="text-white">CRUD de Tareas</h2>
        </div>
        <div>
            <a href="{{route('tasks.create')}}" class="btn btn-primary">Crear tarea</a>
        </div>
    </div>

    @if(Session::get('success'))
    <div class="col-12">
        <div class="alert alert-success mt-2">
            <strong>{{Session::get('success')}}</strong>
        </div>
    </div>

    @endif

    <div class="col-12 mt-4">
        <table class="table table-bordered text-white">
            <tr class="text-secondary">
                <th>Tarea</th>
                <th>Descripción</th>
                <th>Fecha límite</th>
                <th>Fecha de completado</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
            @foreach ($tasks as $task)
            <tr>
                <td class="fw-bold">{{$task->title}}</td>
                <td>{{$task->description}}</td>
                <td>
                    {{$task->due_date}}
                </td>
                <td>
                    {{$task->finish_date}}
                </td>
                <td>
                    @switch($task->status)
                        @case("Pendiente")
                        <span class="badge bg-secondary fs-6">{{$task->status}}</span>
                            @break
                        @case("En progreso")
                        <span class="badge bg-warning fs-6">{{$task->status}}</span>
                            @break
                        @case("Completado")
                        <span class="badge bg-primary fs-6">{{$task->status}}</span>
                            @break
                        @default
                        <span class="badge bg-danger fs-6">{{$task->status}}</span>
                    @endswitch
                </td>
                <td>
                    <a href="{{route('tasks.edit',$task)}}" class="btn btn-warning">Editar</a>

                    <form action="{{route('tasks.destroy',$task)}}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('¿Está seguro de querer eliminar está tarea?')" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach

           
        </table>
        {{$tasks->links()}}
    </div>
</div>
@endsection