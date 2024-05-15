@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg mb-3">
        <div class="card-body">
            <form> 
                <fieldset>
                    <legend>My Fitness Goal</legend>
                    <div class="mb-3">
                        <label for="disabledTextInput" class="form-label">Name</label>
                        <h4>{{ Auth::user()->name }}</h4>
                    </div>
                    <div class="mb-3">
                        <label for="disabledTextInput" class="form-label">Email</label>
                        <h4>{{ Auth::user()->email }}</h4>
                    </div>
                    <div class="mb-3">
                        <label for="disabledSelect" class="form-label">Fitness Level</label>
                        <h4>{{ config('const.fitness_level.' .Auth::user()->fitness_level) }}</h4>
                    </div>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exercises as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->exercise_name }}</td>
                                <td>{{ config('const.fitness_level.'.($item->fitness_level)) }}</td>
                                <td>{{ date('M, d, Y H:i:s', strtotime($item->created_at)) }}</td>
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
