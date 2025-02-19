@extends('layouts.admin')
@section('content')
<div class="row justify-content-center">
    <div>
        <div class="card" style="min-height: 70vh;">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>Manage Announcements</span>
                @include('partials.tabs') 
            </div>
            <div class="tab-content mt-2" id="adminTabsContent">
                <div class="tab-pane fade show active" id="manageAnnouncements" role="tabpanel" aria-labelledby="manageAnnouncements-tab">
                    <div id="manageAnnouncementsContent" class="container mt-3">
                    <h2 class="text-center text-primary"><strong>Announcements List</h2></strong><hr>
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif  
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">User ID</th>
                                            <th class="col-md-3 text-center">Title</th>
                                            <th class="col-md-4 text-center">Content</th>
                                            <th class="text-center">Visibility</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($announcements->isEmpty())
                                            <tr>
                                                <td colspan="6" class="text-center">No announcements found.</td>
                                            </tr>
                                        @else
                                            @foreach ($announcements as $announcement)
                                                <tr>
                                                    <td class="text-center">{{ $announcement->announcement_id }}</td>
                                                    <td class="text-center">{{ $announcement->user_id }}</td>
                                                    <td class="text-center">{{ $announcement->title }}</td>
                                                    <td>{!! nl2br(e($announcement->content)) !!}</td>
                                                    <td class="text-center">
                                                        <span id="visibilityText"> 
                                                            @if($announcement->visible)
                                                                <span class="text-success">Visible</span>
                                                            @else
                                                                <span class="text-danger">Hidden</span>
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('admin.viewAnnouncementDetails', $announcement->announcement_id) }}" class="btn btn-info"><i class="fas fa-info"></i></a>
                                                        <button id="editAnnouncementButton" class="btn btn-primary"><i class="fas fa-edit"></i></button>
                                                        <form method="post" action="{{ route('admin.deleteAnnouncement', ['announcementId' => $announcement->announcement_id]) }}" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this announcement?')"><i class="fas fa-trash-alt"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-primary" id="addAnnouncementButton"><i class="fas fa-plus"></i> Add Announcement</button>
                    </div>
                    <div id="addAnnouncementForm" class="container mt-3" style="display: none;">
                        <div class="card">
                        <h2 class="text-center text-primary"><strong>Add New Announcement</strong></h2><hr><br>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.addAnnouncement') }}" onsubmit="return confirmSubmission();">
                                    @csrf
                                    <div class="form-group">
                                        <div class="form-floating mb-3">
                                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autofocus>
                                                <label for="title">Title</label>
                                            @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-floating mb-3">
                                            <textarea id="content" class="form-control @error('content') is-invalid @enderror" name="content" required>{{ old('content') }}</textarea>
                                            <label for="content">Content</label>
                                            @error('content')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div><br>
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary">                            
                                            <i class="fas fa-save"></i> Add Announcement
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="confirmCancellation()">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleAddAnnouncementForm() {
        $('#addAnnouncementForm').toggle();
        $('#addAnnouncementButton').toggle();
    }

    $('#addAnnouncementButton').click(toggleAddAnnouncementForm);

    function confirmCancellation() {
        if (confirm('Are you sure you want to cancel adding this announcement?')) {
            toggleAddAnnouncementForm();
        }
    }

    function confirmSubmission() {
        return confirm('Are you sure you want to add this announcement?');
    }

    $('#manageAnnouncementsTab').on('shown.bs.tab', function (e) {
        $.ajax({
            url: "{{ route('admin.viewAnnouncements') }}",
            method: 'GET',
            success: function(response) {
                $('#manageAnnouncementsContent').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading Manage Announcements content:', error);
            }
        });
    });

    function toggleEditMode() {
        var editableTitle = document.getElementById('editableTitle');
        var editableContent = document.getElementById('editableContent');
        var announcementTitle = document.getElementById('announcementTitle');
        var announcementContent = document.getElementById('announcementContent');
        var announcementId = document.getElementById('announcementId').innerText; 

        if (editableTitle.style.display === 'none') {
            // Switch to edit mode
            editableTitle.style.display = 'block';
            editableContent.style.display = 'block';
            announcementTitle.style.display = 'none';
            announcementContent.style.display = 'none';
            document.getElementById('editAnnouncementButton').innerText = 'Save'; 

            document.getElementById('goBackButton').style.display = 'none';
            document.getElementById('cancelButton').style.display = 'inline';
        } else {
            if (confirm('Are you sure you want to save changes?')) {
                announcementTitle.innerText = editableTitle.value;
                announcementContent.innerText = editableContent.value;
                editableTitle.style.display = 'none';
                editableContent.style.display = 'none';
                announcementTitle.style.display = 'block';
                announcementContent.style.display = 'block';
                document.getElementById('editAnnouncementButton').innerText = 'Edit'; 
                document.getElementById('goBackButton').style.display = 'inline';
                document.getElementById('cancelButton').style.display = 'none';
                var title = editableTitle.value;
                var content = editableContent.value;

                $.ajax({
                    url: "{{ route('admin.updateAnnouncement', ['announcementId' => ':announcementId']) }}".replace(':announcementId', announcementId),
                    method: 'PUT',
                    data: {
                        title: title,
                        content: content,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // No need for success message here
                        // Refresh the entire page
                        window.location.reload(true);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating announcement:', error);
                    }
                });

                alert('Announcement updated successfully!');
            }
        }
    }
    document.getElementById('editAnnouncementButton').addEventListener('click', toggleEditMode);

    function cancelEdit() {
        if (confirm('Are you sure you want to cancel editing?')) {
            var editableTitle = document.getElementById('editableTitle');
            var editableContent = document.getElementById('editableContent');
            var announcementTitle = document.getElementById('announcementTitle');
            var announcementContent = document.getElementById('announcementContent');

            editableTitle.style.display = 'none';
            editableContent.style.display = 'none';
            announcementTitle.style.display = 'block';
            announcementContent.style.display = 'block';
            document.getElementById('editAnnouncementButton').innerText = 'Edit'; 

            document.getElementById('goBackButton').style.display = 'inline';
            document.getElementById('cancelButton').style.display = 'none';
        }
    }

    document.getElementById('cancelButton').addEventListener('click', cancelEdit);
</script>
@endsection
