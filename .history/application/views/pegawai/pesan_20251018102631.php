<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view("pegawai/components/header.php") ?>
    <style>
        .chat-container {
            height: 600px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .contact-list {
            border-right: 1px solid #ddd;
            height: 100%;
            overflow-y: auto;
        }
        .contact-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .contact-item:hover {
            background-color: #f8f9fa;
        }
        .contact-item.active {
            background-color: #e3f2fd;
        }
        .chat-area {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background-color: #f8f9fa;
        }
        .message {
            margin-bottom: 15px;
            max-width: 70%;
        }
        .message.sent {
            margin-left: auto;
        }
        .message.received {
            margin-right: auto;
        }
        .message-bubble {
            padding: 10px 15px;
            border-radius: 18px;
            word-wrap: break-word;
        }
        .message.sent .message-bubble {
            background-color: #007bff;
            color: white;
        }
        .message.received .message-bubble {
            background-color: white;
            border: 1px solid #ddd;
        }
        .message-time {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 5px;
        }
        .chat-input {
            padding: 15px;
            border-top: 1px solid #ddd;
            background-color: white;
        }
        .no-chat-selected {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #6c757d;
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
                                <li class="breadcrumb-item"><a href="<?= base_url('Dashboard/dashboard_pegawai') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Pesan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="row no-gutters chat-container">
                                <!-- Contact List -->
                                <div class="col-md-4 contact-list">
                                    <div class="p-3 border-bottom">
                                        <h6 class="mb-0">Kontak Admin</h6>
                                        <small class="text-muted">Hubungi admin untuk bantuan</small>
                                    </div>
                                    
                                    <?php if (!empty($contacts)): ?>
                                        <?php foreach ($contacts as $contact): ?>
                                        <div class="contact-item <?= ($selected_user && $selected_user['id_user'] == $contact['id_user']) ? 'active' : '' ?>" 
                                             onclick="openChat('<?= $contact['id_user'] ?>')">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar mr-3">
                                                    <?php if (!empty($contact['foto'])): ?>
                                                        <img src="<?= base_url('assets/uploads/foto/' . $contact['foto']) ?>" 
                                                             class="rounded-circle" width="40" height="40" alt="Avatar">
                                                    <?php else: ?>
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px; color: white;">
                                                            <?= strtoupper(substr($contact['nama_lengkap'], 0, 1)) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0"><?= htmlspecialchars($contact['nama_lengkap']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($contact['jabatan']) ?></small>
                                                    <br><small class="text-primary"><?= ucfirst($contact['user_level']) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="p-3 text-center text-muted">
                                            <i class="fas fa-users fa-2x mb-2"></i>
                                            <p>Tidak ada admin tersedia</p>
                                            <small>Hubungi administrator sistem</small>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Chat Area -->
                                <div class="col-md-8">
                                    <?php if ($selected_user): ?>
                                        <div class="chat-area">
                                            <!-- Chat Header -->
                                            <div class="p-3 border-bottom bg-light">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar mr-3">
                                                        <?php if (!empty($selected_user['foto'])): ?>
                                                            <img src="<?= base_url('assets/uploads/foto/' . $selected_user['foto']) ?>" 
                                                                 class="rounded-circle" width="40" height="40" alt="Avatar">
                                                        <?php else: ?>
                                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                                 style="width: 40px; height: 40px; color: white;">
                                                                <?= strtoupper(substr($selected_user['nama_lengkap'], 0, 1)) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= htmlspecialchars($selected_user['nama_lengkap']) ?></h6>
                                                        <small class="text-muted">
                                                            <?= htmlspecialchars($selected_user['jabatan']) ?> - <?= ucfirst($selected_user['user_level']) ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Messages -->
                                            <div class="messages-container" id="messagesContainer">
                                                <?php if (!empty($messages)): ?>
                                                    <?php foreach ($messages as $msg): ?>
                                                    <div class="message <?= ($msg['id_user_pengirim'] == $this->session->userdata('id_user')) ? 'sent' : 'received' ?>">
                                                        <div class="message-bubble">
                                                            <?= htmlspecialchars($msg['pesan']) ?>
                                                        </div>
                                                        <div class="message-time text-right">
                                                            <?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="text-center text-muted mt-5">
                                                        <i class="fas fa-comments fa-3x mb-3"></i>
                                                        <p>Belum ada percakapan. Mulai chat sekarang!</p>
                                                        <small>Ketik pesan di bawah untuk memulai percakapan</small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Message Input -->
                                            <div class="chat-input">
                                                <form id="messageForm" onsubmit="sendMessage(event)">
                                                    <div class="input-group">
                                                        <input type="hidden" id="penerima" value="<?= $selected_user['id_user'] ?>">
                                                        <input type="text" class="form-control" id="messageInput" 
                                                               placeholder="Ketik pesan..." required>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary" type="submit">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="no-chat-selected">
                                            <div class="text-center">
                                                <i class="fas fa-comment-dots fa-4x text-muted mb-3"></i>
                                                <h5 class="text-muted">Pilih admin dari daftar di sebelah kiri</h5>
                                                <p class="text-muted">untuk memulai percakapan dengan admin.</p>
                                                <div class="alert alert-info mt-3">
                                                    <i class="fas fa-info-circle"></i>
                                                    <strong>Tips:</strong> Anda dapat menanyakan tentang status cuti, 
                                                    prosedur pengajuan, atau bantuan lainnya kepada admin.
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
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
    function openChat(userId) {
        window.location.href = '<?= base_url('Pesan/view_pegawai?with=') ?>' + userId;
    }

    function sendMessage(event) {
        event.preventDefault();
        
        const messageInput = document.getElementById('messageInput');
        const penerima = document.getElementById('penerima').value;
        const pesan = messageInput.value.trim();

        if (!pesan) return;

        // Disable input while sending
        messageInput.disabled = true;

        $.ajax({
            url: '<?= base_url('Pesan/send_message') ?>',
            method: 'POST',
            data: {
                penerima: penerima,
                pesan: pesan
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Reload page to show new message
                    location.reload();
                } else {
                    alert('Gagal mengirim pesan: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat mengirim pesan');
            },
            complete: function() {
                messageInput.disabled = false;
            }
        });
    }

    // Auto scroll to bottom
    $(document).ready(function() {
        const container = document.getElementById('messagesContainer');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });

    // Auto refresh messages every 10 seconds if in chat
    <?php if ($selected_user): ?>
    setInterval(function() {
        if (!document.getElementById('messageInput').disabled) {
            // Only refresh if not currently sending a message
            window.location.reload();
        }
    }, 10000);
    <?php endif; ?>
    </script>
</body>
</html>
