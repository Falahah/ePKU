@extends('layouts.admin')

@section('content')
    <style>
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
    <div class="row justify-content-center">
        <div>
            <div class="card" style="min-height: 70vh;">
                <div class="card-header text-white d-flex align-items-center justify-content-between">
                    <span>Manage Medical Services</span>
                    @include('partials.tabs')
                </div>
                <div class="tab-content mt-2" id="adminTabsContent">
                    <div class="tab-pane fade show active" id="manageMedicalServices" role="tabpanel" aria-labelledby="manageMedicalServices-tab">
                        <div id="manageMedicalServicesContent" class="container mt-3">
                            <h2 class="text-center text-primary"><strong>Manage Medical Services' Opening & Closing Hours</strong></h2><hr>
                            <div id="successMessage" class="alert alert-success" style="display: none;">
                                Service hours updated successfully!
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th class="bg-primary text-white">Services</th>
                                            <th class="bg-primary text-white">Sunday</th>
                                            <th class="bg-primary text-white">Monday</th>
                                            <th class="bg-primary text-white">Tuesday</th>
                                            <th class="bg-primary text-white">Wednesday</th>
                                            <th class="bg-primary text-white">Thursday</th>
                                            <th class="bg-primary text-white">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($services as $service)
                                            <tr>
                                                <td><strong>{{ $service->service_type }}</strong></td>
                                                @foreach(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'] as $day)
                                                    <td class="editable-time" data-service-id="{{ $service->service_id }}" data-day="{{ $day }}">
                                                        <span class="view-mode">
                                                            {{ $service->{$day . '_opening'} ? \Carbon\Carbon::parse($service->{$day . '_opening'})->format('h:i A') : '-' }}
                                                            -
                                                            {{ $service->{$day . '_closing'} ? \Carbon\Carbon::parse($service->{$day . '_closing'})->format('h:i A') : '-' }}
                                                        </span>
                                                        <span class="edit-mode" style="display: none;">
                                                            <input type="time" class="form-control" value="{{ $service->{$day . '_opening'} }}" name="{{ $day }}_opening">
                                                            -
                                                            <input type="time" class="form-control" value="{{ $service->{$day . '_closing'} }}" name="{{ $day }}_closing">
                                                        </span>
                                                    </td>
                                                @endforeach
                                                <td>
                                                    <button class="btn btn-primary edit-btn" onclick="toggleEditMode(this)"><i class="fas fa-edit"></i></button>
                                                    <button class="btn btn-success save-btn d-none" onclick="confirmSave(this, {{ $service->service_id }})"><i class="fas fa-save"></i></button>
                                                    <button class="btn btn-secondary cancel-btn d-none" onclick="confirmCancel(this)"><i class="fas fa-times"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleEditMode(btn) {
            var row = $(btn).closest('tr');
            row.find('.view-mode').hide();
            row.find('.edit-mode').show();
            row.find('.edit-btn').hide();
            row.find('.save-btn, .cancel-btn').removeClass('d-none');
        }

        function confirmSave(btn, serviceId) {
            if (confirm('Are you sure you want to save changes?')) {
                saveChanges(btn, serviceId);
            }
        }

        function confirmCancel(btn) {
            if (confirm('Are you sure you want to cancel changes?')) {
                cancelChanges(btn);
            }
        }

        function cancelChanges(btn) {
            var row = $(btn).closest('tr');
            row.find('.view-mode').show();
            row.find('.edit-mode').hide();
            row.find('.edit-btn').show();
            row.find('.save-btn, .cancel-btn').addClass('d-none');
        }

        function saveChanges(btn, serviceId) {
            var row = $(btn).closest('tr');
            var formData = {
                _token: '{{ csrf_token() }}',
                service_id: serviceId
            };

            ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'].forEach(function(day) {
                formData[day + '_opening'] = row.find('input[name="' + day + '_opening"]').val();
                formData[day + '_closing'] = row.find('input[name="' + day + '_closing"]').val();
            });

            console.log('Sending data:', formData);

            $.ajax({
                url: "{{ route('admin.updateServiceHours') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log('Success response:', response);
                    sessionStorage.setItem('successMessage', 'Service hours updated successfully!');
                    window.location.reload();
                },
                error: function(xhr) {
                    // Handle error
                    console.log('Error response:', xhr.responseText); 
                    alert('Failed to update service hours. Please try again.');
                }
            });
        }
        $(document).ready(function() {
            if (sessionStorage.getItem('successMessage')) {
                $('#successMessage').text(sessionStorage.getItem('successMessage')).show();
                sessionStorage.removeItem('successMessage');
            }
        });
    </script>
@endsection
