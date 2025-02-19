<table class="table">
    <thead>
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Name</th>
            <th class="text-center">Username</th>
            <th class="text-center">Email</th>
            <th class="text-center">Date of Birth</th>
            <th class="text-center">Gender</th>
            <th class="text-center">Phone Number</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody class="{{ $tab }}UserTableBody">
        @forelse($users as $user)
            <tr>
                <td class="text-center">{{ $user->userID }}</td>
                <td class="text-center">{{ $user->name }}</td>
                <td class="text-center">{{ $user->username }}</td>
                <td class="text-center">{{ $user->email }}</td>
                <td class="text-center">{{ $user->date_of_birth }}</td>
                <td class="text-center">{{ $user->gender }}</td>
                <td class="text-center">{{ $user->phone_number }}</td>
                <td class="text-center">
                    <a href="{{ route('user.details', ['userId' => $user->userID]) }}" class="btn btn-info">
                        <i class="fas fa-info"></i>
                    </a>
                    <a href="{{ route('user.edit', ['userId' => $user->userID]) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="post" action="{{ route('user.delete', ['userId' => $user->userID]) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this staff member?')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No users found</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    $(document).ready(function() {
        function activateTab(tabId) {
            $('.nav-link').removeClass('active');
            $('#' + tabId + '-tab').addClass('active');
            $('.tab-pane').removeClass('show active');
            $('#' + tabId).addClass('show active');
        }

        function handleSearch(input, tableBodyClass) {
            var searchText = input.val().toLowerCase();
            var visibleRows = $(tableBodyClass + ' tr').filter(function() {
                return $(this).text().toLowerCase().indexOf(searchText) > -1;
            });
            $(tableBodyClass + ' tr').hide();
            visibleRows.show();
            toggleNoUsersMessage(tableBodyClass, visibleRows);
        }

        function toggleNoUsersMessage(tableBodyClass, visibleRows) {
            if (visibleRows.length === 0) {
                if ($(tableBodyClass + ' .no-users-message').length === 0) {
                    $(tableBodyClass).append('<tr class="no-users-message"><td colspan="8" class="text-center">No users found</td></tr>');
                }
            } else {
                $(tableBodyClass + ' .no-users-message').remove();
            }
        }

        $('#adminUserSearch').on('input', function() {
            handleSearch($(this), '#admin .adminUserTableBody');
        });

        $('#studentUserSearch').on('input', function() {
            handleSearch($(this), '#student .studentUserTableBody');
        });

        $('#staffUserSearch').on('input', function() {
            handleSearch($(this), '#staff .staffUserTableBody');
        });

        activateTab($('.nav-link.active').attr('href').substring(1));
    });
</script>
