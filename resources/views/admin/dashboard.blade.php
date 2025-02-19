@extends('layouts.admin')

@section('content')
<style>
    .card-round {
        border-radius: 15px;
    }

    .card-header {
        font-weight: bold;
    }

    .card-body {
        padding: 20px;
    }

    .chart-container {
        position: relative;
        margin: auto;
    }

    .icon-big {
        font-size: 3rem;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 70px;
        width: 70px;
        border-radius: 15px;
    }

    .icon-primary {
        background-color: #6d95ea;
        color: white;
    }

    .icon-success {
        background-color: #28a745;
        color: white;
    }

    .icon-info {
        background-color: #17a2b8;
        color: white;
    }

    .icon-warning {
        background-color: #ffc107;
        color: white;
    }

    .numbers {
        text-align: center;
    }

    .numbers p {
        margin: 0;
    }

    .numbers h4 {
        margin-top: 5px;
        font-size: 1.5rem;
        font-weight: bold;
    }
    
/*     Card Stats    */
.card-stats .card-body {
  padding: 15px !important; }
.card-stats .card-title {
  margin-bottom: 0px !important; }
.card-stats .card-category {
  margin-top: 0px; }
.card-stats .col-icon {
  width: 65px;
  height: 65px;
  padding-left: 0;
  padding-right: 0;
  margin-left: 15px; }
.card-stats .icon-big {
  width: 100%;
  height: 100%;
  font-size: 2.2em;
  min-height: 64px;
  display: flex;
  align-items: center;
  justify-content: center; }
  .card-stats .icon-big.icon-black, .card-stats .icon-big.icon-primary, .card-stats .icon-big.icon-secondary, .card-stats .icon-big.icon-success, .card-stats .icon-big.icon-info, .card-stats .icon-big.icon-warning, .card-stats .icon-big.icon-danger {
    border-radius: 5px; }
    .card-stats .icon-big.icon-black i, .card-stats .icon-big.icon-primary i, .card-stats .icon-big.icon-secondary i, .card-stats .icon-big.icon-success i, .card-stats .icon-big.icon-info i, .card-stats .icon-big.icon-warning i, .card-stats .icon-big.icon-danger i {
      color: #ffffff !important; }
  .card-stats .icon-big.icon-black {
    background: #1a2035; }
  .card-stats .icon-big.icon-primary {
    background: #1572E8; }
  .card-stats .icon-big.icon-secondary {
    background: #6861CE; }
  .card-stats .icon-big.icon-success {
    background: #31CE36; }
  .card-stats .icon-big.icon-warning {
    background: #FFAD46; }
  .card-stats .icon-big.icon-info {
    background: #48ABF7; }
  .card-stats .icon-big.icon-danger {
    background: #F25961; }
  .card-stats .icon-big.round {
    border-radius: 50% !important; }
  .card-stats .icon-big i.fa, .card-stats .icon-big i.fab, .card-stats .icon-big i.fal, .card-stats .icon-big i.far, .card-stats .icon-big i.fas {
    font-size: 0.8em; }
.card-stats .col-stats {
  align-items: center;
  display: flex;
  padding-left: 15px; }

    @media (max-width: 767.98px) {
        .card-stats {
            margin-bottom: 20px;
        }
    }

    .card-round {
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.card-header {
    font-weight: bold;
    background-color: #fff;
    border-bottom: 1px solid #e0e0e0;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    font-size: 1.25rem;
    color: #2b2b2b;
}

.card-body {
    padding: 20px;
}

.chart-container {
    position: relative;
    margin: auto;
    height: 100%;
}

.btn-label-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.btn-label-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.btn-round {
    border-radius: 30px;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 0.875rem;
}

.me-2 {
    margin-right: 0.5rem;
}
.card-header-orange {
    background-color: #e6561e; 
    align-items: center;

}
.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: #007bff; /* Change this to your desired color */
    border-radius: 50%;
    padding: 10px;
    width: 30px;
    height: 30px;
}

.carousel-control-prev,
.carousel-control-next {
    width: 5%;
}   

.card-stats-custom {
        border-radius: 20px;
        background-color: #80b7de; /* Customize this color */
        color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        padding: 20px;
        text-align: left;
    }

    .card-stats-custom .icon-big {
        font-size: 3rem;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 70px;
        width: 70px;
        border-radius: 20px;
        background-color: rgba(255, 255, 255, 0.2);
        margin-right: 20px;
    }

    .card-stats-custom .numbers {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .card-stats-custom .numbers p {
        margin: 0;
        font-size: 1rem;
    }

    .card-stats-custom .numbers h4 {
        margin: 0;
        font-size: 2rem;
        font-weight: bold;
    }

@media print {
    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .card {
        border: none;
        box-shadow: none;
    }
    .card-header, .card-body {
        page-break-inside: avoid;
    }
    .no-print {
        display: none;
    }
    .chart-container {
        height: auto !important;
    }
}
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg" style="min-height: 70vh;">
                <div class="card-header card-header-orange text-white d-flex align-items-center justify-content-between">
                    <span>{{ Auth::user()->username }}'s Dashboard</span>
                    @include('partials.tabs') <!-- Include the tabs partial -->
                </div>
                <div class="tab-content mt-2" id="adminTabsContent">
                    <div class="container mt-4">
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="card-stats-custom">
                                <div class="icon-big text-center">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="numbers">
                                    <h4>{{ $totalUsers }}</h4>
                                    <p>Users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card-stats-custom">
                                <div class="icon-big text-center">
                                    <i class="fas fa-list"></i>
                                </div>
                                <div class="numbers">
                                    <h4>{{ $totalAppointments }}</h4>
                                    <p>Appointments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card-stats-custom">
                                <div class="icon-big text-center">
                                    <i class="fas fa-syringe"></i>
                                </div>
                                <div class="numbers">
                                    <h4>{{ $totalServices }}</h4>
                                    <p>Services</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="card-stats-custom">
                                <div class="icon-big text-center">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div class="numbers">
                                    <h4>{{ $totalAnnouncements }}</h4>
                                    <p>Announcements</p>
                                </div>
                            </div>
                        </div>
                    </div><br>
                        <!-- The rest of your chart sections go here -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card card-round shadow-sm">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span class="card-title">Appointments Trend</span>
                                        <div>
                                            <a href="#" class="btn btn-label-info btn-round btn-sm no-print" onclick="window.print()">
                                                <i class="fa fa-print"></i> Print
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="chartCarousel" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="chart-container" style="min-height: 375px">
                                                        <canvas id="appointmentsTrendChart"></canvas>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="chart-container" style="min-height: 375px">
                                                        <canvas id="appointmentsByMonthChart"></canvas> 
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="chart-container" style="min-height: 375px">
                                                        <canvas id="appointmentsByServiceChart"></canvas>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="chart-container" style="min-height: 375px">
                                                        <canvas id="servicesByAppointmentChart"></canvas>
                                                    </div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="chart-container" style="min-height: 375px">
                                                        <canvas id="appointmentsByStatusChart"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#chartCarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#chartCarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="col-md-4">
                            <div class="card card-primary card-round">
                            <div class="card-header">
                                <div class="card-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="card-title me-2"><h1><strong>Today's Appointments</strong></h1></div>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pb-0">
                                <div class="pull-in">
                                <canvas id="appointmentsByServiceTodayChart"></canvas>
                                </div>
                            </div>
                            </div>
                            </div>
                        <!-- End of chart sections -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-treemap"></script>
    <script>
        $(document).ready(function () {
            var currentPath = window.location.pathname;
            if (currentPath.includes('/admin/dashboard')) {
                $('.nav-link[data-tab="dashboard"]').addClass('active');
            } else {
                $('.nav-link').each(function () {
                    var link = $(this).attr('href');
                    if (currentPath === link) {
                        $(this).addClass('active');
                    }
                });
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            var ctxTodayService = document.getElementById('appointmentsByServiceTodayChart').getContext('2d');
            var appointmentsByServiceTodayChart = new Chart(ctxTodayService, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($appointmentsByServiceToday->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($appointmentsByServiceToday->values()) !!},
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var data = context.raw;
                                    var total = {!! $appointmentsByServiceToday->sum() !!};
                                    var percentage = ((data / total) * 100).toFixed(2);
                                    return context.label + ': ' + data + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
            var ctxService = document.getElementById('appointmentsByServiceChart').getContext('2d');
            var appointmentsByServiceChart = new Chart(ctxService, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($appointmentsByService->pluck('service')) !!},
                    datasets: [{
                        data: {!! json_encode($appointmentsByService->pluck('count')) !!},
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var data = context.raw;
                                    var total = {!! $appointmentsByService->sum('count') !!};
                                    var percentage = ((data / total) * 100).toFixed(2);
                                    return context.label + ': ' + data + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            var ctxStatus = document.getElementById('appointmentsByStatusChart').getContext('2d');
            var appointmentsByStatusChart = new Chart(ctxStatus, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($appointmentsByStatus->pluck('status')) !!},
                    datasets: [{
                        data: {!! json_encode($appointmentsByStatus->pluck('count')) !!},
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var data = context.raw;
                                    var total = {!! $appointmentsByStatus->sum('count') !!};
                                    var percentage = ((data / total) * 100).toFixed(2);
                                    return context.label + ': ' + data + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            var ctxServices = document.getElementById('servicesByAppointmentChart').getContext('2d');
            var servicesByAppointmentChart = new Chart(ctxServices, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($servicesByAppointment->pluck('service_type')) !!},
                    datasets: [{
                        label: 'Appointments',
                        data: {!! json_encode($servicesByAppointment->pluck('appointments_count')) !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var data = context.raw;
                                    var total = {!! $servicesByAppointment->sum('appointments_count') !!};
                                    var percentage = ((data / total) * 100).toFixed(2);
                                    return context.label + ': ' + data + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            var ctxTrend = document.getElementById('appointmentsTrendChart').getContext('2d');
            var appointmentsTrendChart = new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: {!! json_encode($appointmentsTrend->pluck('date')) !!},
                    datasets: [{
                        label: 'Appointments',
                        data: {!! json_encode($appointmentsTrend->pluck('count')) !!},
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var data = context.raw;
                                    var total = {!! $appointmentsTrend->sum('count') !!};
                                    var percentage = ((data / total) * 100).toFixed(2);
                                    return context.label + ': ' + data + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            var ctxMonth = document.getElementById('appointmentsByMonthChart').getContext('2d');
            var appointmentsByMonthChart = new Chart(ctxMonth, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($appointmentsByMonth->pluck('month')) !!},
                    datasets: [{
                        label: 'Appointments',
                        data: {!! json_encode($appointmentsByMonth->pluck('count')) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var data = context.raw;
                                    var total = {!! $appointmentsByMonth->sum('count') !!};
                                    var percentage = ((data / total) * 100).toFixed(2);
                                    return context.label + ': ' + data + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        });


    </script>
@endsection
