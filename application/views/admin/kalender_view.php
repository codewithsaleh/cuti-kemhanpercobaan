<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view("admin/components/header.php") ?>
    <!-- CSS untuk FullCalendar -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        /* Gaya kustom agar kalender serasi dengan tema Anda */
        #calendar {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 2rem 0 rgba(0,0,0,.05);
        }
        .fc .fc-toolbar-title {
            font-size: 1.5em;
            font-weight: 600;
        }
        .fc .fc-button-primary {
            background-color: #7267EF; /* Warna ungu dari tema Anda */
            border-color: #7267EF;
        }
        .fc .fc-button-primary:hover {
            background-color: #5a51d4;
            border-color: #5a51d4;
        }
        .fc .fc-daygrid-day.fc-day-today {
            background-color: #eef2f6;
        }
        .fc-event {
            border: none !important;
            padding: 5px;
            font-size: 0.85em;
        }
        /* [BARU] Menambahkan warna untuk event cuti */
        .fc-event {
            background-color: #28a745 !important; /* Warna hijau untuk cuti yang disetujui */
            color: #ffffff !important;
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
                            <h1 class="m-0">Kalender Tim</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('Dashboard/dashboard_admin')?>">Home</a></li>
                                <li class="breadcrumb-item active">Kalender Tim</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div id='calendar'></div>
                </div>
            </section>
        </div>
    </div>

    <!-- Skrip-skrip penting yang dibutuhkan halaman ini -->
    <script src="<?= base_url();?>assets/admin_lte/plugins/jquery/jquery.min.js"></script>
    <script src="<?= base_url();?>assets/admin_lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url();?>assets/admin_lte/dist/js/adminlte.js"></script>
    
    <!-- Skrip KHUSUS untuk FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                // Mengambil data cuti dari controller yang sudah kita buat
                events: '<?= base_url() ?>Kalender/get_events',
                editable: false, // Event tidak bisa digeser-geser
                selectable: false, // Tanggal tidak bisa dipilih
            });
            calendar.render();
        });
    </script>
</body>
</html>
