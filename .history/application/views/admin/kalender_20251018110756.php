<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view("admin/components/header.php") ?>
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <style>
        .fc-event {
            cursor: pointer;
        }
        .fc-event:hover {
            opacity: 0.8;
        }
        .calendar-legend {
            margin-bottom: 20px;
        }
        .legend-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 10px;
        }
        .legend-color {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 8px;
            border-radius: 4px;
            vertical-align: middle;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php $this->load->view("admin/components/navbar.php") ?>
        <?php $this->load->view("admin/components/sidebar.php") ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Kalender Cuti Pegawai</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Kalender</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Kalender Cuti dan Hari Libur</h3>
                        </div>
                        <div class="card-body">
                            <!-- Legend -->
                            <div class="calendar-legend">
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #28a745;"></span>
                                    <span>Cuti Pegawai</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #dc3545;"></span>
                                    <span>Hari Libur Nasional</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-color" style="background-color: #ffc107;"></span>
                                    <span>Cuti Menunggu Persetujuan</span>
                                </div>
                            </div>
                            
                            <!-- Calendar -->
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal Detail Event -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Cuti</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="eventModalBody">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view("admin/components/js.php") ?>
    
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'id',
            firstDay: 1, // Mulai dari Senin
            weekends: true,
            editable: false,
            selectable: false,
            
            // Load events dari API
            eventSources: [
                {
                    url: '<?= base_url('Api_kalender/events') ?>',
                    failure: function() {
                        alert('Gagal memuat data cuti!');
                    }
                },
                {
                    url: '<?= base_url('Api_kalender/hari_libur') ?>',
                    failure: function() {
                        console.log('Gagal memuat hari libur');
                    }
                }
            ],
            
            // Event click handler
            eventClick: function(info) {
                var event = info.event;
                var props = event.extendedProps;
                
                if (props.nip) {
                    // Jika ini adalah event cuti pegawai
                    var content = `
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Nama Pegawai:</th>
                                        <td><strong>${event.title.split(' - ')[0]}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>NIP:</th>
                                        <td>${props.nip}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Cuti:</th>
                                        <td><span class="badge badge-info">${props.jenis_cuti}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Periode:</th>
                                        <td>${event.start.toLocaleDateString('id-ID')} - ${new Date(event.end.getTime() - 86400000).toLocaleDateString('id-ID')}</td>
                                    </tr>
                                    <tr>
                                        <th>Alasan:</th>
                                        <td>${props.alasan}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td><span class="badge badge-success">${props.status}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    `;
                    document.getElementById('eventModalBody').innerHTML = content;
                    $('#eventModal').modal('show');
                } else {
                    // Jika ini adalah hari libur
                    var content = `
                        <div class="alert alert-info">
                            <h5><i class="fas fa-calendar-alt"></i> ${event.title}</h5>
                            <p class="mb-0">Tanggal: ${event.start.toLocaleDateString('id-ID')}</p>
                        </div>
                    `;
                    document.getElementById('eventModalBody').innerHTML = content;
                    $('#eventModal').modal('show');
                }
            },
            
            // Loading state
            loading: function(bool) {
                if (bool) {
                    // Show loading
                    console.log('Loading calendar...');
                } else {
                    // Hide loading
                    console.log('Calendar loaded');
                }
            }
        });
        
        calendar.render();
    });
    </script>
</body>
</html>
