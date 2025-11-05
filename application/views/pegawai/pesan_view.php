<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("pegawai/components/header.php") ?>

    <style>
        /* Gaya Desktop (Default) */
        .chat-container {
            display: flex;
            height: 75vh;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            overflow: hidden;
            background-color: #ffffff;
        }

        .chat-sidebar {
            width: 300px;
            border-right: 1px solid #dee2e6;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .chat-sidebar-header {
            padding: 1.25rem;
            font-weight: 600;
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .chat-user-list {
            overflow-y: auto;
            flex-grow: 1;
        }

        .chat-user {
            display: flex;
            align-items: center;
            padding: 1rem 1.25rem;
            cursor: pointer;
            border-bottom: 1px solid #f1f1f1;
            transition: background-color 0.2s;
            position: relative;
            /* Diperlukan untuk badge notifikasi */
        }

        .chat-user:hover {
            background-color: #f8f9fa;
        }

        .chat-user.active {
            background-color: #007bff;
            color: white;
        }

        .chat-user.active .title,
        .chat-user.active .name {
            color: white;
        }

        .chat-user img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 1rem;
        }

        .chat-user-info .name {
            font-weight: 600;
        }

        .chat-user-info .title {
            font-size: 0.8em;
            color: #6c757d;
        }

        .chat-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background-color: #f4f6f9;
        }

        .chat-header {
            padding: 1.25rem;
            border-bottom: 1px solid #dee2e6;
            background-color: #fff;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .chat-body {
            flex-grow: 1;
            padding: 1.25rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .chat-message {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
            max-width: 70%;
        }

        .chat-message.sent {
            align-self: flex-end;
            align-items: flex-end;
        }

        .chat-message.received {
            align-self: flex-start;
            align-items: flex-start;
        }

        .message-bubble {
            padding: 0.75rem 1rem;
            border-radius: 1.25rem;
        }

        .sent .message-bubble {
            background-color: #007bff;
            color: #fff;
            border-bottom-right-radius: 0.25rem;
        }

        .received .message-bubble {
            background-color: #fff;
            color: #333;
            border: 1px solid #dee2e6;
            border-bottom-left-radius: 0.25rem;
        }

        .message-time {
            font-size: 0.75em;
            color: #6c757d;
            margin-top: 0.25rem;
            padding: 0 0.25rem;
        }

        .chat-footer {
            padding: 1rem 1.25rem;
            background-color: #fff;
            border-top: 1px solid #dee2e6;
        }

        #no-chat-selected {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            flex-direction: column;
            color: #6c757d;
            text-align: center;
        }

        #no-chat-selected i {
            font-size: 4em;
            margin-bottom: 1rem;
        }

        .message-bubble-container {
            display: flex;
            align-items: center;
        }

        .delete-icon {
            color: #6c757d;
            opacity: 0.5;
            margin-left: 10px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .delete-icon:hover {
            opacity: 1;
        }

        .message-bubble.deleted {
            background-color: #f8f9fa !important;
            color: #6c757d !important;
            font-style: italic;
            border: 1px solid #dee2e6 !important;
        }

        /* Gaya untuk badge notifikasi */
        .unread-badge {
            position: absolute;
            top: 50%;
            right: 1.25rem;
            transform: translateY(-50%);
            background-color: #dc3545;
            /* Merah */
            color: white;
            font-size: 0.75em;
            font-weight: bold;
            padding: 0.2em 0.5em;
            border-radius: 0.75rem;
            min-width: 20px;
            text-align: center;
        }

        .chat-user.active .unread-badge {
            display: none;
        }

        /* Sembunyikan jika chat aktif */

        /* Gaya untuk tampilan mobile */
        .back-to-users {
            display: none;
        }

        @media (max-width: 767.98px) {
            .chat-container {
                height: calc(100vh - 140px);
            }

            .chat-sidebar {
                width: 100%;
                border-right: none;
            }

            .chat-main {
                display: none;
            }

            .chat-container.mobile-chat-active .chat-sidebar {
                display: none;
            }

            .chat-container.mobile-chat-active .chat-main {
                display: flex;
                width: 100%;
            }

            .back-to-users {
                display: block;
                margin-right: 15px;
                font-size: 1.2rem;
                cursor: pointer;
                color: #6c757d;
            }
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php $this->load->view("pegawai/components/navbar.php") ?>
        <?php $this->load->view("pegawai/components/sidebar.php") ?>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Pesan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a
                                        href="<?= base_url('Dashboard/dashboard_pegawai') ?>">Home</a></li>
                                <li class="breadcrumb-item active">Pesan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-body p-0">
                <div class="chat-container" id="chat-container">
                    <div class="chat-sidebar">
                        <div class="chat-sidebar-header">Daftar Percakapan</div>
                        <!-- Daftar pengguna akan dimuat di sini oleh JavaScript -->
                        <div class="chat-user-list" id="chat-user-list">
                            <p style="text-align:center; margin-top: 20px; color:#6c757d;">Memuat percakapan...</p>
                        </div>
                    </div>
                    
                    <div class="chat-main">
                        <div class="chat-header">
                            <i class="fas fa-arrow-left back-to-users"></i>
                            <span id="chat-header-name">Pilih Admin untuk Memulai</span>
                        </div>
                        <div class="chat-body" id="chat-body">
                            <div id="no-chat-selected">
                                <i class="fas fa-comments"></i>
                                <p>Pilih admin dari daftar di sebelah kiri<br>untuk melihat percakapan.</p>
                            </div>
                        </div>
                        <div class="chat-footer" style="display: none;">
                            <form id="send-message-form">
                                <input type="hidden" id="penerima-id" name="penerima_id">
                                <div class="input-group">
                                    <input type="text" id="isi-pesan" name="isi_pesan"
                                        placeholder="Ketik pesan..." class="form-control" autocomplete="off"
                                        required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
        </div>
    </div>

    <?php $this->load->view("pegawai/components/js.php") ?>

        <script>
    $(document).ready(function () {
        let activeAdminId = null;
        let chatInterval = null;
        let userListInterval = null;
        const pegawaiId = '<?= $this->session->userdata("id_user") ?>';
        const chatContainer = $('#chat-container');
        const userListContainer = $('#chat-user-list');

        // Fungsi untuk memuat daftar admin
        function updateUserList() {
            $.ajax({
                url: `<?= base_url('Pesan/get_admin_list') ?>`,
                type: 'GET',
                dataType: 'json',
                success: function (admins) {
                    userListContainer.empty();
                    if (admins && admins.length > 0) {
                        admins.forEach(function (admin) {
                            let unreadBadge = '';
                            if (admin.unread_count > 0) {
                                unreadBadge = `<span class="unread-badge">${admin.unread_count}</span>`;
                            }

                            const adminHtml = `
                                <div class="chat-user" data-user-id="${admin.id_user}" data-user-name="${admin.nama_lengkap}">
                                    <img src="<?= base_url(); ?>assets/admin_lte/dist/img/account.jpg" alt="User Image">
                                    <div class="chat-user-info">
                                        <div class="name">${admin.nama_lengkap}</div>
                                        <div class="title">${admin.jabatan || 'Admin'}</div>
                                    </div>
                                    ${unreadBadge}
                                </div>
                            `;
                            userListContainer.append(adminHtml);
                        });

                        // Tandai kembali admin yang sedang aktif
                        if (activeAdminId) {
                            $(`.chat-user[data-user-id="${activeAdminId}"]`).addClass('active');
                        }
                    } else {
                        userListContainer.html('<p style="text-align:center; margin-top: 20px; color:#6c757d;">Belum ada percakapan.</p>');
                    }
                },
                error: function () {
                    userListContainer.html('<p style="text-align:center; margin-top: 20px; color:#dc3545;">Gagal memuat daftar admin.</p>');
                }
            });
        }

        // Fungsi loadChat
        function loadChat(adminId, adminName) {
            if (chatInterval) { clearInterval(chatInterval); }
            activeAdminId = adminId;

            chatContainer.addClass('mobile-chat-active');

            $('#penerima-id').val(adminId);
            $('#chat-header-name').text('Percakapan dengan ' + adminName);
            $('.chat-footer').show();
            $('#chat-body').html('<p style="text-align:center; margin:auto; color:#6c757d;">Memuat pesan...</p>');
            $('.chat-user').removeClass('active');
            $(`.chat-user[data-user-id="${adminId}"]`).addClass('active');

            fetchChatHistory(true);
            chatInterval = setInterval(() => fetchChatHistory(false), 5000);

            // Setelah membuka chat, update list untuk menghilangkan notifikasi
            setTimeout(updateUserList, 500);
        }

        // Fungsi fetchChatHistory
        function fetchChatHistory(forceScroll = false) {
            if (!activeAdminId) return;
            $.ajax({
                url: `<?= base_url('Pesan/get_chat_history/') ?>${activeAdminId}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const chatBody = $('#chat-body');
                    const isScrolledToBottom = chatBody[0].scrollHeight - chatBody.scrollTop() <= chatBody.clientHeight + 5;
                    chatBody.empty();
                    if (response && response.length > 0) {
                        response.forEach(function (msg) {
                            const messageSide = msg.id_pengirim == pegawaiId ? 'sent' : 'received';
                            const messageTimestamp = new Date(msg.waktu_kirim.replace(' ', 'T')).getTime();
                            const now = new Date().getTime();
                            const ageInSeconds = (now - messageTimestamp) / 1000;
                            const isDeleted = msg.isi_pesan === 'Pesan ini telah ditarik.';
                            let deleteButtonHtml = '';
                            if (messageSide === 'sent' && ageInSeconds < 300 && !isDeleted) {
                                deleteButtonHtml = `<i class="fas fa-times-circle delete-icon" data-id="${msg.id_pesan}" title="Tarik Pesan"></i>`;
                            }
                            const bubbleClass = isDeleted ? 'deleted' : '';
                            const messageTime = new Date(msg.waktu_kirim.replace(' ', 'T')).toLocaleString('id-ID', { 
                                hour: '2-digit', 
                                minute: '2-digit' 
                            });
                            const messageHtml = `
                                <div class="chat-message ${messageSide}">
                                    <div class="message-bubble-container">
                                        <div class="message-bubble ${bubbleClass}">${msg.isi_pesan}</div>
                                        ${deleteButtonHtml}
                                    </div>
                                    <div class="message-time">${messageTime}</div>
                                </div>
                            `;
                            chatBody.append(messageHtml);
                        });
                        if (forceScroll || isScrolledToBottom) {
                            chatBody.scrollTop(chatBody[0].scrollHeight);
                        }
                    } else {
                        chatBody.html('<div id="no-chat-selected"><p>Belum ada percakapan. Mulai sekarang!</p></div>');
                    }
                },
                error: function () {
                    clearInterval(chatInterval);
                    $('#chat-body').html('<div id="no-chat-selected" style="color:red;"><p>Gagal memuat percakapan.</p></div>');
                }
            });
        }

        // Event listener menggunakan event delegation
        userListContainer.on('click', '.chat-user', function () {
            const userId = $(this).data('user-id');
            const userName = $(this).data('user-name');
            loadChat(userId, userName);
        });

        $('.back-to-users').on('click', function () {
            chatContainer.removeClass('mobile-chat-active');
            clearInterval(chatInterval);
            activeAdminId = null;
        });

        $('#send-message-form').on('submit', function (e) {
            e.preventDefault();
            const messageInput = $('#isi-pesan');
            if (messageInput.val().trim() === '' || !activeAdminId) return;
            
            $.ajax({
                url: '<?= base_url("Pesan/send_message") ?>',
                type: 'POST',
                data: {
                    id_penerima: $('#penerima-id').val(),
                    pesan: $('#isi-pesan').val()
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        messageInput.val('');
                        fetchChatHistory(true);
                        updateUserList();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan koneksi.');
                }
            });
        });

        $('#chat-body').on('click', '.delete-icon', function () {
            const messageId = $(this).data('id');
            if (confirm('Anda yakin ingin menarik pesan ini?')) {
                $.ajax({
                    url: `<?= base_url('Pesan/tarik_pesan/') ?>${messageId}`,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            fetchChatHistory(true);
                        } else {
                            alert('Gagal menarik pesan: ' + response.message);
                        }
                    },
                    error: function () {
                        alert('Terjadi kesalahan koneksi.');
                    }
                });
            }
        });

        // Inisialisasi
        updateUserList();
        userListInterval = setInterval(updateUserList, 7000);
    });
</script>
</body>

</html>