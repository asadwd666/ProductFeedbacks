<!-- resources/views/admin/users/index.blade.php -->

@extends('../layouts.layout')
<style>
    .custom-control-input {
        opacity: 1 !important;
        position: none !important;
        z-index: 1 !important;
        position: relative !important;
    }
</style>
@section('content')
    <div class="row">

        <div class="col-md-10">
            <h1>Admin Dashboard - User Management</h1>

        </div>
        <table class="table entriestable table-striped table-users">
            <thead class="head-sm thead-color-1">
                <tr class="text-left">

                    <th>Name</th>
                    <th>Email</th>
                    <th>Posting</th>
                    <th>Action</th>
                    <th>Comments</th>


                </tr>
            </thead>
            <tbody>

                @foreach ($users as $user)
                    <tr>

                        <td>
                            {{ $user->name }}

                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                        <td>
                            <input type="checkbox" data-id={{ $user->id }} class="custom-control-input posting"
                                name="" id="" @if ($user->hasDirectPermission('posting')) checked @endif>


                        </td>
                        <td>
                            <a href="" class="delete_user" data-id="{{ $user->id }}">delete user</a>
                        </td>
                        <td>
                            <input type="checkbox" data-id={{ $user->id }} class="custom-control-input commenting"
                                name="" id="" @if ($user->hasDirectPermission('commenting')) checked @endif>
                        </td>
                    </tr>
                @endforeach

            </tbody>

        </table>

    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $('.posting').on('change', function() {
            let enablePosting = $(this).prop('checked') ? 1 : 0;
            let user_id = $(this).data('id');
            $.ajax({
                method: "POST",
                url: "{{ route('admin.user-posting') }}",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'enablePosting': enablePosting,
                    'user_id': user_id

                },
                success: function(data) {

                    toastr.success(
                        'Success!',
                        'Posting status Updated succesfully', {
                            positionClass: 'toast-bottom-right'
                        }
                    );

                },

            });
        })
        $('.delete_user').on('click', function() {
            let user_id = $(this).data('id');
            $.ajax({
                method: "POST",
                url: "{{ route('admin.user-delete') }}",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'user_id': user_id
                },
                success: function(data) {

                    toastr.success(
                        'Success!',
                        'User Deleted succesfully', {
                            positionClass: 'toast-bottom-right'
                        }
                    );
                    location.reload();

                },

            });
        })
        $('.commenting').on('change', function() {
            let enableCommenting = $(this).prop('checked') ? 1 : 0;
            let user_id = $(this).data('id');
            $.ajax({
                method: "POST",
                url: "{{ route('admin.user-commenting') }}",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'enableCommenting': enableCommenting,
                    'user_id': user_id

                },
                success: function(data) {

                    toastr.success(
                        'Success!',
                        'Commenting status Updated succesfully', {
                            positionClass: 'toast-bottom-right'
                        }
                    );

                },

            });
        })
    </script>
@endsection
