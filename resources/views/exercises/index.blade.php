@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg mb-3">
        <div class="card-body">
            <form action="{{ route('exercisesStore') }}" method="POST"> @csrf
                <fieldset>
                    <legend>Add Exercise</legend>
                    <div class="mb-3">
                    <label for="disabledTextInput" class="form-label">Exercise Name</label>
                        <input id="exercise" type="text" name="exercise_name" class="form-control @error('exercise_name') is-invalid @enderror" value="{{ old('exercise_name') }}" required autocomplete="exercise_name" autofocus>
                        @error('exercise_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                    <label for="disabledSelect" class="form-label">Fitness Level</label>
                    <select id="disabledSelect" name="fitness_level" class="form-select">
                        @foreach (config('const.fitness_level') as $item)
                            <option value="{{ ($loop->iteration) -1 }}">{{ config('const.fitness_level.'.($loop->iteration) - 1) }}</option>
                        @endforeach
                    </select>
                    </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="card shadow-lg mb-3">
        <div class="card-header">
            List of Exercises
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Exercise Name</th>
                            <th>Fitness Level</th>
                            <th>Date Added</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exercises as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->exercise_name }}</td>
                                <td>
                                    <select id="fitness_level" name="fitness_level" data-id="{{ $item->id }}" class="form-select">
                                        <option value="{{ ($item->fitness_level) }}">{{ config('const.fitness_level.'.($item->fitness_level)) }}</option>
                                        @foreach (config('const.fitness_level') as $data)
                                            <option value="{{ ($loop->iteration) -1 }}">{{ config('const.fitness_level.'.($loop->iteration) - 1) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>{{ date('M, d, Y H:i:s', strtotime($item->created_at)) }}</td>
                                <td>
                                    <button type="button" id="delete_exercise" data-id="{{ $item->id }}" class="btn btn-outline-danger m-1">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '#delete_exercise', function() {
        $.ajax({
            url: `{{ route('exercisesDestroy') }}`,
            method: 'POST',
            data: {
                id: $(this).data('id')
            },
            beforeSend: function(){
                $('#btnLogin').html("REDIRECTING...").prop("disabled", true);
            },
            success:function(response){
                if (response.status == 200) {
                    location.reload();
                }
            },
            error:function(xhr, status, error){
            alert(xhr.responseJSON.message);
            }
        });
    });

    $(document).on('change', '#fitness_level', function() {
       let id = $(this).data('id');
       let fitness_level = $(this).val();
        $.ajax({
            url: `{{ route('exercisesUpdate') }}`,
            method: 'POST',
            data: {
                id: id,
                fitness_level : fitness_level
            },
            beforeSend: function(){
                $('#btnLogin').html("REDIRECTING...").prop("disabled", true);
            },
            success:function(response){
                if (response.status == 200) {
                    location.reload();
                }
            },
            error:function(xhr, status, error){
                alert(xhr.responseJSON.message);
            }
        });
    });
</script>
@endsection
