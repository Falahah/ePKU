@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div>
        <div class="card" style="min-height: 70vh;">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <span>Announcement's Details</span>
                @include('partials.tabs') <!-- Include the tabs partial -->
            </div>
            <div class="tab-content mt-2" id="adminTabsContent">
                <div class="tab-pane fade show active" id="manageAnnouncements" role="tabpanel" aria-labelledby="manageAnnouncements-tab">
                    {{-- Add the content to display announcements --}}
                    <div id="manageAnnouncementsContent" class="container mt-3">
                    @if (session('success'))
                    <!-- Display success message -->
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif  
                            <dl class="row">
                                <div class="col-sm-12 bg-light p-4 rounded mb-3">
                                <div class="col-md-8 mx-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                        <a href="{{ route('admin.manageAnnouncements') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i>
                                        </a>
                                        <h2 class="text-center text-primary flex-grow-1"><strong>Announcement's Details</strong></h2>
                                        <div class="d-flex">
                                            <button id="editAnnouncementButton" class="btn btn-primary me-2"><i class="fas fa-edit"></i></button>
                                            <form method="post" action="{{ route('admin.deleteAnnouncement', ['announcementId' => $announcement->announcement_id]) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this announcement?')"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                            <a href="#" id="cancelButton" class="btn btn-secondary ms-2" style="display: none;"><i class="fas fa-times"></i></a>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <strong>ID</strong>
                                        </div>
                                        <div class="col-sm-8" id="announcementId">
                                            {{ $announcement->announcement_id }}
                                        </div>
                                    </div>
                                    <hr>                                        
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <strong>Created by</strong>
                                        </div>
                                        <div class="col-sm-8" id="userId">
                                            <dd class="col-sm-9">
                                                @if($announcement->author)
                                                    {{ $announcement->author->name }}
                                                @else
                                                    No User Found
                                                @endif
                                            </dd>
                                        </div>
                                    </div>
                                    <hr>                                        

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <strong>Title</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <dd class="col-sm-9">
                                                <span id="announcementTitle">{{ $announcement->title }}</span>
                                                <input type="text" id="editableTitle" class="form-control" style="display: none;" value="{{ $announcement->title }}">
                                            </dd>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <strong>Content</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <dd class="col-sm-9">
                                                <span id="announcementContent">{{ $announcement->content }}</span>
                                                <textarea id="editableContent" class="form-control" style="display: none;">{{ $announcement->content }}</textarea>
                                            </dd>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <strong>Visibility</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <dd class="col-sm-9" id="visibilitySection">
                                                <span id="visibilityText"> 
                                                    @if($announcement->visible)
                                                        <span class="text-success">Visible</span>
                                                    @else
                                                        <span class="text-danger">Hidden</span>
                                                    @endif
                                                </span>
                                                <select id="editableVisibility" class="form-control" style="display: none;">
                                                    <option value="1" {{ $announcement->visible ? 'selected' : '' }}>Show</option>
                                                    <option value="0" {{ !$announcement->visible ? 'selected' : '' }}>Hide</option>
                                                </select>
                                            </dd>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleEditMode() {
        var editableTitle = document.getElementById('editableTitle');
        var editableContent = document.getElementById('editableContent');
        var editableVisibility = document.getElementById('editableVisibility');
        var announcementTitle = document.getElementById('announcementTitle');
        var announcementContent = document.getElementById('announcementContent');
        var visibilitySection = document.getElementById('visibilitySection');
        var editableVisibility = document.getElementById('editableVisibility');
        var visibilityText = document.getElementById('visibilityText');

        if (editableTitle.style.display === 'none') {
            // Switch to edit mode
            editableTitle.style.display = 'block';
            editableContent.style.display = 'block';
            editableVisibility.style.display = 'block'; // Show dropdown list
            visibilityText.style.display = 'none'; // Hide visibility text
            visibilitySection.style.display = 'block';
            announcementTitle.style.display = 'none';
            announcementContent.style.display = 'none';
            document.getElementById('editAnnouncementButton').innerHTML = '<i class="fas fa-save"></i>'; // Change button text to 'Save' with icon
                    // Hide Go Back button and show Cancel button
        document.getElementById('cancelButton').style.display = 'inline';
    } else {
        // Confirm if user wants to save changes
        if (confirm('Are you sure you want to save changes?')) {
            // Save changes and switch back to view mode
            announcementTitle.innerText = editableTitle.value;
            announcementContent.innerText = editableContent.value;
            editableTitle.style.display = 'none';
            editableContent.style.display = 'none';
            editableVisibility.style.display = 'none'; // Hide dropdown list
            visibilityText.style.display = 'inline'; // Show visibility text
            visibilitySection.style.display = 'inline';
            announcementTitle.style.display = 'block';
            announcementContent.style.display = 'block';
            document.getElementById('editAnnouncementButton').innerText = 'Edit'; // Change button text back to 'Edit'

            // Show Go Back button and hide Cancel button
            document.getElementById('cancelButton').style.display = 'none';

            // Update visibility text based on selected option in the dropdown
            var selectedVisibility = editableVisibility.value;
            if (selectedVisibility == '1') {
                visibilityText.innerHTML = '<span class="text-success">Visible</span>';
            } else {
                visibilityText.innerHTML = '<span class="text-danger">Hidden</span>';
            }

            // Send AJAX request to update the announcement
            var announcementId = document.getElementById('announcementId').innerText;
            var title = editableTitle.value;
            var content = editableContent.value;
            var visibility = editableVisibility.value;

            // Send AJAX request to update the announcement
            $.ajax({
                url: "{{ route('admin.updateAnnouncement', ['announcementId' => $announcement->announcement_id]) }}",
                method: 'PUT',
                data: {
                    title: title,
                    content: content,
                    visibility: visibility,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Reload the page after successful update
                    window.location.reload(true);
                },
                error: function(xhr, status, error) {
                    console.error('Error updating announcement:', error);
                }
            });

            // Show success message after the page reloads
            setTimeout(function() {
                location.reload();
            }, 500); // Adjust timing as needed
        }
    }
}

// Attach click event listener to the edit button
document.getElementById('editAnnouncementButton').addEventListener('click', toggleEditMode);

// Function to toggle back to view mode
function cancelEdit() {
    if (confirm('Are you sure you want to cancel editing?')) {
        var editableTitle = document.getElementById('editableTitle');
        var editableContent = document.getElementById('editableContent');
        var editableVisibility = document.getElementById('editableVisibility');
        var announcementTitle = document.getElementById('announcementTitle');
        var announcementContent = document.getElementById('announcementContent');
        var visibilitySection = document.getElementById('visibilitySection');
        var editableVisibility = document.getElementById('editableVisibility');
        var visibilityText = document.getElementById('visibilityText');

        // Switch back to view mode
        editableTitle.style.display = 'none';
        editableContent.style.display = 'none';
        editableVisibility.style.display = 'none'; // Hide dropdown list
        visibilityText.style.display = 'inline'; // Show visibility text
        visibilitySection.style.display = 'inline';
        announcementTitle.style.display = 'block';
        announcementContent.style.display = 'block';
        document.getElementById('editAnnouncementButton').innerText = 'Edit'; // Change button text back to 'Edit'

        // Show Go Back button and hide Cancel button
        document.getElementById('cancelButton').style.display = 'none';
    }
}

// Attach click event listener to the Cancel button
document.getElementById('cancelButton').addEventListener('click', cancelEdit);

$(document).ready(function () {
    var currentPath = window.location.pathname;
    if (currentPath.includes('/admin/view-announcement-details/')) {
        $('.nav-link[data-tab="manageAnnouncements"]').addClass('active');
    } else {
        $('.nav-link').each(function () {
            var link = $(this).attr('href');
            if (currentPath === link) {
                $(this).addClass('active');
            }
        });
    }
});                                
</script>
@endsection
