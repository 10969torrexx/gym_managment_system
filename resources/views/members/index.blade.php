@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg mb-3">
        <div class="card-header">
            <legend>List of Members</legend>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Fitness Level</th>
                            <th>Date Joined</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    <select id="fitness_level" name="fitness_level" data-id="{{ $item->id }}" class="form-select">
                                        <option value="{{ ($item->fitness_level) }}">{{ config('const.fitness_level.'.($item->fitness_level)) }}</option>
                                        @foreach (config('const.fitness_level') as $data)
                                            <option value="{{ ($loop->iteration) -1 }}">{{ config('const.fitness_level.'.($loop->iteration) - 1) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>{{ date('M, d, Y', strtotime($item->created_at)) }}</td>
                                <td>
                                    <button type="button" id="delete_exercise" data-id="{{ $item->id }}" class="btn btn-outline-danger m-1">Danger</button>
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
        alert($(this).data('id'));
        $.ajax({
            url: `{{ route('membersDestroy') }}`,
            method: 'POST',
            data: {
                id: $(this).data('id')
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
            url: `{{ route('membersUpdate') }}`,
            method: 'POST',
            data: {
                id: id,
                fitness_level : fitness_level
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
