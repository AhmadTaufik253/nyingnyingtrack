<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NyingnyingTrack | Fleet Monitor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/logo-curut-v2.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Marker Cluster -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

    <!-- Responsive Fleet Map Styles -->
    <link rel="stylesheet" href="{{ asset('css/fleet-map.css') }}">
</head>

<body>

    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="sidebar-header">
            <div class="brand">
                <img src="{{ asset('assets/logo-curut-v2.png') }}" alt="Logo">
                <h1>NyingnyingTrack</h1>
            </div>
            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="deviceSearch" class="search-input"
                    placeholder="Search devices by name or IMEI...">
                <button type="button" class="search-action-btn" onclick="openAddDeviceModal()"
                    aria-label="Tambah device" title="Tambah device">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="device-list-container" id="deviceList">
            <!-- Loader -->
            <div style="padding: 20px; text-align: center; color: var(--secondary);">
                <i class="fa-solid fa-circle-notch fa-spin"></i> Loading devices...
            </div>
        </div>
    </aside>

    <!-- Mobile overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div id="sidebar-toggle" aria-label="Toggle sidebar">
        <i class="fa-solid fa-bars"></i>
    </div>

    <!-- Stats Panel -->
    <div id="stats-panel" class="floating-panel">
        <div class="stat-item">
            <span class="stat-label">Total</span>
            <span class="stat-value" id="count-total">0</span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Moving</span>
            <span class="stat-value" style="color: var(--primary);" id="count-moving">0</span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Online</span>
            <span class="stat-value" style="color: var(--success);" id="count-online">0</span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Offline</span>
            <span class="stat-value" style="color: var(--secondary);" id="count-offline">0</span>
        </div>
    </div>

    <!-- Top Right Map Controls -->
    <div id="top-right-panel" class="floating-panel">
        <div class="top-controls">
            <a href="{{ route('admin.users') }}" style="text-decoration: none" class="top-control-btn" aria-label="Setup" title="Setup">
                <i class="fa-solid fa-gears"></i>
            </a>
            <button class="top-control-btn" aria-label="Tools" title="Tools">
                <i class="fa-solid fa-wrench"></i>
            </button>
            <div class="settings-container" style="position: relative;">
                <button class="top-control-btn" aria-label="Settings" title="Settings" onclick="toggleSettingsMenu()">
                    <i class="fa-solid fa-gear"></i>
                </button>
                <div class="device-dropdown" id="settingsDropdown">
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="danger" style="width:100%;text-align:left;">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
                        </button>
                    </form>
                </div>
            </div>
            <button class="top-control-btn" aria-label="Messages" title="Messages">
                <i class="fa-solid fa-comments"></i>
            </button>
            <button class="top-control-btn" aria-label="Profile" title="Profile">
                <i class="fa-solid fa-user"></i>
            </button>
            <button class="top-control-btn" aria-label="Language" title="Language" style="font-size: 1.2rem;">
                🇬🇧
            </button>
        </div>
    </div>

    <!-- Details Panel -->
    <div id="details-panel" class="floating-panel">
        <div class="details-header">
            <h3 id="detail-name">Device Details</h3>
            <button onclick="hideDetails()" class="close-btn" aria-label="Close details">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="details-tabs">
            <button class="tab-btn active" onclick="openTab(event, 'info')">
                <i class="fa-solid fa-circle-info"></i> Info
            </button>
            <button class="tab-btn" onclick="openTab(event, 'logs')">
                <i class="fa-solid fa-list-ul"></i> Logs
            </button>
        </div>
        <div id="tab-info">
            <!-- detail list -->
            <div class="details-list">
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-microchip"></i> IMEI</span>
                    <span class="detail-value" id="detail-imei">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-tag"></i> Model</span>
                    <span class="detail-value" id="detail-model">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-gauge-high"></i> Speed</span>
                    <span class="detail-value" id="detail-speed">0 km/h</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-regular fa-clock"></i> Time</span>
                    <span class="detail-value" id="detail-time">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-location-dot"></i> Latitude</span>
                    <span class="detail-value" id="detail-latitude">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-location-dot"></i> Longitude</span>
                    <span class="detail-value" id="detail-longitude">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-mountain"></i> Altitude</span>
                    <span class="detail-value" id="detail-altitude">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-compass"></i> Angle</span>
                    <span class="detail-value" id="detail-angle">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-satellite"></i> Satellites</span>
                    <span class="detail-value" id="detail-satellites">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-battery-three-quarters"></i> Battery</span>
                    <span class="detail-value" id="detail-battery">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-bolt"></i> Voltage</span>
                    <span class="detail-value" id="detail-voltage">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-signal"></i> GSM Signal</span>
                    <span class="detail-value" id="detail-gsm">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="fa-solid fa-key"></i> Ignition</span>
                    <span class="detail-value" id="detail-ignition">-</span>
                </div>
            </div>
        </div>
        <div id="tab-logs" style="display:none">
            <div class="log-table-wrapper">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th><i class="fa-regular fa-clock"></i> Time</th>
                            <th><i class="fa-solid fa-gauge-high"></i> Speed</th>
                            <th><i class="fa-solid fa-location-dot"></i> Latitude</th>
                            <th><i class="fa-solid fa-location-dot"></i> Longitude</th>
                            <th><i class="fa-solid fa-battery-three-quarters"></i> Battery</th>
                            <th><i class="fa-solid fa-signal"></i> Signal</th>
                            <th><i class="fa-solid fa-key"></i> Ignition</th>
                            <th><i class="fa-solid fa-road"></i> Odometer</th>
                            <th><i class="fa-solid fa-power-off"></i> D OUT 1</th>
                            <th><i class="fa-solid fa-satellite"></i> GNSS Status</th>
                        </tr>
                    </thead>
                    <tbody id="device-log-table">
                        <tr>
                            <td colspan="10" style="text-align:center">
                                No Data
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Map -->
    <main id="map"></main>

    <!-- Add Device Modal -->
    <div class="modal-overlay" id="addDeviceModal">
        <div class="modal-box modal-box-wide">
            <div class="modal-header">
                <h3>Tambah Device</h3>
                <button type="button" class="close-btn" onclick="closeAddDeviceModal()" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Tab navigation -->
            <div class="modal-tabs">
                <button type="button" class="modal-tab-btn active" onclick="switchDeviceTab(event, 'main')">Main</button>
                <button type="button" class="modal-tab-btn" onclick="switchDeviceTab(event, 'icons')">Icons</button>
                <button type="button" class="modal-tab-btn" onclick="switchDeviceTab(event, 'advance')">Advanced</button>
                <button type="button" class="modal-tab-btn" onclick="switchDeviceTab(event, 'sensor')">Sensors</button>
                <button type="button" class="modal-tab-btn" onclick="switchDeviceTab(event, 'tail')">Tail</button>
                <button type="button" class="modal-tab-btn" onclick="switchDeviceTab(event, 'services')">Services</button>
            </div>

            <form id="addDeviceForm">

                <!-- TAB: Main -->
                <div class="modal-tab-panel active" id="tab-main">
                    <div class="form-group form-group-checkbox">
                        <label class="checkbox-label-inline">
                            <input type="checkbox" id="add-active">
                            Active
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="add-user">User<span class="required">*</span>:</label>
                        <select id="add-user" class="form-control" required>
                            <option value="">-- Pilih user --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="add-name">Name<span class="required">*</span>:</label>
                        <input type="text" id="add-name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="add-expiration">Expiration date:</label>
                        <div class="expiration-row">
                            <input type="checkbox" id="add-expiration-enabled" class="expiration-toggle">
                            <input type="date" id="add-expiration" class="form-control" disabled>
                        </div>
                    </div>
                </div>

                <!-- TAB: Icons -->
                <div class="modal-tab-panel" id="tab-icons">
                    <div class="form-group">
                        <label>Pilih Icon</label>
                        <div class="icon-picker">
                            <button type="button" class="icon-option selected" data-icon="truck-fast"
                                onclick="selectDeviceIcon(this)">
                                <i class="fa-solid fa-truck-fast"></i>
                            </button>
                            <button type="button" class="icon-option" data-icon="motorcycle"
                                onclick="selectDeviceIcon(this)">
                                <i class="fa-solid fa-motorcycle"></i>
                            </button>
                            <button type="button" class="icon-option" data-icon="car" onclick="selectDeviceIcon(this)">
                                <i class="fa-solid fa-car"></i>
                            </button>
                            <button type="button" class="icon-option" data-icon="van-shuttle"
                                onclick="selectDeviceIcon(this)">
                                <i class="fa-solid fa-van-shuttle"></i>
                            </button>
                            <button type="button" class="icon-option" data-icon="bus" onclick="selectDeviceIcon(this)">
                                <i class="fa-solid fa-bus"></i>
                            </button>
                            <button type="button" class="icon-option" data-icon="box" onclick="selectDeviceIcon(this)">
                                <i class="fa-solid fa-box"></i>
                            </button>
                        </div>
                        <input type="hidden" id="add-icon" value="truck-fast">
                    </div>
                </div>

                <!-- TAB: Advance -->
                <div class="modal-tab-panel" id="tab-advance">
                    <div class="form-group">
                        <label for="add-imei">IMEI<span class="required">*</span>:</label>
                        <input type="text" id="add-imei" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="add-model">Model:</label>
                        <input type="text" id="add-model" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="add-protocol">Protocol:</label>
                        <select id="add-protocol" class="form-control">
                            <option value="gt06">GT06</option>
                            <option value="vt100">VT100 / iStartek</option>
                            <option value="teltonika">Teltonika</option>
                            <option value="generic">Generic</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add-timezone">Timezone:</label>
                        <input type="text" id="add-timezone" class="form-control" placeholder="Asia/Jakarta"
                            value="Asia/Jakarta">
                    </div>
                    <div class="form-group">
                        <label for="add-plate">Plat Nomor:</label>
                        <input type="text" id="add-plate" class="form-control" placeholder="B 1234 CD">
                    </div>
                </div>

                <!-- TAB: Sensor -->
                <div class="modal-tab-panel" id="tab-sensor">
                    <div class="form-group">
                        <label class="checkbox-label-inline">
                            <input type="checkbox" id="add-fuel-enabled">
                            Aktifkan Fuel Sensor
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="add-fuel-capacity">Kapasitas Tangki (liter)</label>
                        <input type="number" id="add-fuel-capacity" class="form-control" placeholder="60">
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label-inline">
                            <input type="checkbox" id="add-temp-enabled">
                            Aktifkan Temperature Sensor
                        </label>
                    </div>
                </div>

                <!-- TAB: Tail -->
                <div class="modal-tab-panel" id="tab-tail">
                    <div class="form-group">
                        <label for="add-trailer-name">Nama Tail/Trailer</label>
                        <input type="text" id="add-trailer-name" class="form-control" placeholder="Opsional">
                    </div>
                    <div class="form-group">
                        <label for="add-trailer-plate">Plat Trailer</label>
                        <input type="text" id="add-trailer-plate" class="form-control" placeholder="Opsional">
                    </div>
                </div>

                <!-- TAB: Services -->
                <div class="modal-tab-panel" id="tab-services">
                    <div class="form-group">
                        <label for="add-service-start">Tanggal Aktivasi</label>
                        <input type="date" id="add-service-start" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="add-service-expiry">Tanggal Expired</label>
                        <input type="date" id="add-service-expiry" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="add-service-notes">Catatan</label>
                        <textarea id="add-service-notes" class="form-control" rows="3"
                            placeholder="Opsional"></textarea>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeAddDeviceModal()">Batal</button>
                    <button type="submit" class="btn-submit-modal">Tambah Device</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Device Modal -->
    <div class="modal-overlay" id="editDeviceModal">
        <div class="modal-box modal-box-wide">
            <div class="modal-header">
                <h3>Edit Device</h3>
                <button type="button" class="close-btn" onclick="closeEditModal()" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Tab navigation -->
            <div class="modal-tabs">
                <button type="button" class="modal-tab-btn active" onclick="switchEditTab(event, 'main')">Main</button>
                <button type="button" class="modal-tab-btn" onclick="switchEditTab(event, 'icons')">Icons</button>
                <button type="button" class="modal-tab-btn" onclick="switchEditTab(event, 'advance')">Advanced</button>
                <button type="button" class="modal-tab-btn" onclick="switchEditTab(event, 'sensor')">Sensors</button>
                <button type="button" class="modal-tab-btn" onclick="switchEditTab(event, 'tail')">Tail</button>
                <button type="button" class="modal-tab-btn" onclick="switchEditTab(event, 'services')">Services</button>
            </div>

            <form id="editDeviceForm">
                <input type="hidden" id="edit-device-id">

                <!-- TAB: Main -->
                <div class="modal-tab-panel active" id="edit-tab-main">
                    <div class="form-group form-group-checkbox">
                        <label class="checkbox-label-inline">
                            <input type="checkbox" id="edit-active" checked>
                            Active
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="edit-user">User<span class="required">*</span>:</label>
                        <select id="edit-user" class="form-control" required>
                            <option value="">-- Pilih user --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit-name">Name<span class="required">*</span>:</label>
                        <input type="text" id="edit-name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-expiration">Expiration date:</label>
                        <div class="expiration-row">
                            <input type="checkbox" id="edit-expiration-enabled" class="expiration-toggle">
                            <input type="date" id="edit-expiration" class="form-control" disabled>
                        </div>
                    </div>
                </div>

                <!-- TAB: Icons -->
                <div class="modal-tab-panel" id="edit-tab-icons">
                    <div class="form-group">
                        <label>Pilih Icon</label>
                        <div class="icon-picker">
                            <button type="button" class="icon-option selected" data-icon="truck-fast"
                                onclick="selectEditDeviceIcon(this)">
                                <i class="fa-solid fa-truck-fast"></i>
                            </button>
                            <button type="button" class="icon-option" data-icon="motorcycle"
                                onclick="selectEditDeviceIcon(this)">
                                <i class="fa-solid fa-motorcycle"></i>
                            </button>
                            <button type="button" class="icon-option" data-icon="car"
                                onclick="selectEditDeviceIcon(this)">
                                <i class="fa-solid fa-car"></i>
                            </button>
                            <button type="button" class="icon-option" data-icon="van-shuttle"
                                onclick="selectEditDeviceIcon(this)">
                                <i class="fa-solid fa-van-shuttle"></i>
                            </button>
                            <button type="button" class="icon-option" data-icon="bus"
                                onclick="selectEditDeviceIcon(this)">
                                <i class="fa-solid fa-bus"></i>
                            </button>
                            <button type="button" class="icon-option" data-icon="box"
                                onclick="selectEditDeviceIcon(this)">
                                <i class="fa-solid fa-box"></i>
                            </button>
                        </div>
                        <input type="hidden" id="edit-icon" value="truck-fast">
                    </div>
                </div>

                <!-- TAB: Advance -->
                <div class="modal-tab-panel" id="edit-tab-advance">
                    <div class="form-group">
                        <label for="edit-imei">IMEI<span class="required">*</span>:</label>
                        <input type="text" id="edit-imei" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-model">Model:</label>
                        <input type="text" id="edit-model" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit-protocol">Protocol:</label>
                        <select id="edit-protocol" class="form-control">
                            <option value="gt06">GT06</option>
                            <option value="vt100">VT100 / iStartek</option>
                            <option value="teltonika">Teltonika</option>
                            <option value="generic">Generic</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-timezone">Timezone:</label>
                        <input type="text" id="edit-timezone" class="form-control" placeholder="Asia/Jakarta"
                            value="Asia/Jakarta">
                    </div>
                    <div class="form-group">
                        <label for="edit-plate">Plat Nomor:</label>
                        <input type="text" id="edit-plate" class="form-control" placeholder="B 1234 CD">
                    </div>
                </div>

                <!-- TAB: Sensor -->
                <div class="modal-tab-panel" id="edit-tab-sensor">
                    <div class="form-group">
                        <label class="checkbox-label-inline">
                            <input type="checkbox" id="edit-fuel-enabled">
                            Aktifkan Fuel Sensor
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="edit-fuel-capacity">Kapasitas Tangki (liter)</label>
                        <input type="number" id="edit-fuel-capacity" class="form-control" placeholder="60">
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label-inline">
                            <input type="checkbox" id="edit-temp-enabled">
                            Aktifkan Temperature Sensor
                        </label>
                    </div>
                </div>

                <!-- TAB: Tail -->
                <div class="modal-tab-panel" id="edit-tab-tail">
                    <div class="form-group">
                        <label for="edit-trailer-name">Nama Tail/Trailer</label>
                        <input type="text" id="edit-trailer-name" class="form-control" placeholder="Opsional">
                    </div>
                    <div class="form-group">
                        <label for="edit-trailer-plate">Plat Trailer</label>
                        <input type="text" id="edit-trailer-plate" class="form-control" placeholder="Opsional">
                    </div>
                </div>

                <!-- TAB: Services -->
                <div class="modal-tab-panel" id="edit-tab-services">
                    <div class="form-group">
                        <label for="edit-service-start">Tanggal Aktivasi</label>
                        <input type="date" id="edit-service-start" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit-service-expiry">Tanggal Expired</label>
                        <input type="date" id="edit-service-expiry" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit-service-notes">Catatan</label>
                        <textarea id="edit-service-notes" class="form-control" rows="3"
                            placeholder="Opsional"></textarea>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-submit-modal">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle active state: when unchecked, disable other inputs
        function toggleActiveFields() {
            const isActive = document.getElementById('add-active').checked;
            // Select all inputs/selects/textarea within the form except the active checkbox itself
            const form = document.getElementById('addDeviceForm');
            const controls = form.querySelectorAll('input, select, textarea');
            controls.forEach(ctrl => {
                if (ctrl.id === 'add-active' || ctrl.id === 'add-expiration-enabled') return; // skip checkbox and expiration toggle
                ctrl.disabled = !isActive;
            });
            // Also disable expiration date if its toggle is unchecked (already handled)
        }

        // Toggle active state for edit modal
        function toggleEditActiveFields() {
            const isActive = document.getElementById('edit-active').checked;
            const form = document.getElementById('editDeviceForm');
            const controls = form.querySelectorAll('input, select, textarea');
            controls.forEach(ctrl => {
                if (ctrl.id === 'edit-active' || ctrl.id === 'edit-expiration-enabled') return;
                ctrl.disabled = !isActive;
            });
        }
        document.getElementById('edit-active').addEventListener('change', toggleEditActiveFields);

        document.getElementById('add-active').addEventListener('change', function () {
            toggleActiveFields();
        });

        document.getElementById('add-expiration-enabled').addEventListener('change', function () {
            document.getElementById('add-expiration').disabled = !this.checked;
        });
        // Edit modal expiration toggle
        document.getElementById('edit-expiration-enabled').addEventListener('change', function () {
            document.getElementById('edit-expiration').disabled = !this.checked;
        });

        async function loadUserOptions() {
            try {
                const res = await fetch('/api/users');
                const users = await res.json();
                const select = document.getElementById('add-user');
                select.innerHTML = '<option value="">-- Pilih user --</option>' +
                    users.map(u => `<option value="${u.id}">${u.email}</option>`).join('');
            } catch (err) {
                console.error('Gagal load user list:', err);
            }
        }

        function switchDeviceTab(event, tabName) {
            // Scope to Add Device Modal only
            document.querySelectorAll('#addDeviceModal .modal-tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('#addDeviceModal .modal-tab-btn').forEach(b => b.classList.remove('active'));

            document.getElementById(`tab-${tabName}`).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        function selectEditDeviceIcon(btn) {
            document.querySelectorAll('#edit-tab-icons .icon-option').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            document.getElementById('edit-icon').value = btn.dataset.icon;
        }

        function selectDeviceIcon(btn) {
            document.querySelectorAll('#tab-icons .icon-option').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            document.getElementById('add-icon').value = btn.dataset.icon;
        }

        // Function for edit modal icon selection
        function switchEditTab(event, tabName) {
            // Scope to Edit Device Modal only
            document.querySelectorAll('#editDeviceModal .modal-tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('#editDeviceModal .modal-tab-btn').forEach(b => b.classList.remove('active'));

            document.getElementById(`edit-tab-${tabName}`).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        // Reset edit modal tabs to default (Main) and activate first tab button
        function resetEditModalTabs() {
            document.querySelectorAll('#editDeviceModal .modal-tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('#editDeviceModal .modal-tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('edit-tab-main').classList.add('active');
            const firstBtn = document.querySelector('#editDeviceModal .modal-tab-btn');
            if (firstBtn) firstBtn.classList.add('active');
        }

        function openAddDeviceModal() {
            document.getElementById('addDeviceForm').reset();

            // reset ke tab Main
            document.querySelectorAll('.modal-tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.modal-tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-main').classList.add('active');
            document.querySelector('.modal-tab-btn').classList.add('active');

            // reset icon picker
            document.querySelectorAll('#tab-icons .icon-option').forEach(b => b.classList.remove('selected'));
            document.querySelector('#tab-icons .icon-option').classList.add('selected');
            document.getElementById('add-icon').value = 'truck-fast';

            // set active fields based on checkbox state
            toggleActiveFields();

            document.getElementById('addDeviceModal').classList.add('open');
            loadUserOptions(); // load user options setiap kali modal dibuka
        }

        function closeAddDeviceModal() {
            document.getElementById('addDeviceModal').classList.remove('open');
        }

        document.getElementById('addDeviceForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const payload = {
                name: document.getElementById('add-name').value,
                imei: document.getElementById('add-imei').value,
                model: document.getElementById('add-model').value,
            };

            try {
                const res = await fetch('/api/devices', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) {
                    const err = await res.json();
                    throw new Error(err.message || 'Gagal menambah device');
                }

                closeAddDeviceModal();
                loadDevices(); // refresh list biar device baru langsung keliatan

            } catch (err) {
                console.error('Add device error:', err);
                alert(err.message || 'Gagal menambah device.');
            }
        });

        // tutup modal kalau klik area gelap di luar box
        document.getElementById('addDeviceModal').addEventListener('click', (e) => {
            if (e.target.id === 'addDeviceModal') closeAddDeviceModal();
        });
    </script>

    <script>
        function openTab(event, tab) {

            document.getElementById('tab-info').style.display =
                tab === 'info' ? 'block' : 'none';

            document.getElementById('tab-logs').style.display =
                tab === 'logs' ? 'block' : 'none';

            document.querySelectorAll(".tab-btn")
                .forEach(b => b.classList.remove("active"));

            event.currentTarget.classList.add("active");
        }
        function formatDateTime(dateString) {
            if (!dateString) return '-';

            const date = new Date(dateString);

            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
        }
    </script>

    <script>
        // --- CONFIG & STATE ---
        let allDevices = [];
        let activeDeviceId = null;
        let activePolyline = null;
        let markersMap = {};
        const markerGroup = L.markerClusterGroup({
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true
        });

        // --- INIT MAP ---
        const map = L.map('map', {
            zoomControl: false,
            attributionControl: false
        }).setView([-4.0106646, 113.8587308], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        L.control.zoom({ position: 'topright' }).addTo(map);
        map.addLayer(markerGroup);

        const customIcon = L.icon({
            iconUrl: "{{ asset('assets/logo-curut-v2.png') }}",
            iconSize: [45, 45],
            iconAnchor: [22, 22],
            popupAnchor: [0, -20]
        });

        // --- UI HELPERS ---
        const isMobile = () => window.innerWidth <= 768;
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleIcon = document.querySelector('#sidebar-toggle i');

        const openSidebar = () => {
            sidebar.classList.add('open');
            toggleIcon.className = 'fa-solid fa-xmark';
            if (isMobile()) overlay.classList.add('active');
        };

        const closeSidebar = () => {
            sidebar.classList.remove('open');
            toggleIcon.className = 'fa-solid fa-bars';
            overlay.classList.remove('active');
        };

        const toggleSidebar = () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        };

        document.getElementById('sidebar-toggle').addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', closeSidebar);

        const hideDetails = () => {
            document.getElementById('details-panel').classList.remove('visible');
            activeDeviceId = null;
            if (activePolyline) {
                map.removeLayer(activePolyline);
                activePolyline = null;
            }
        };

        async function loadDeviceLogs(deviceId) {

            const res = await fetch(`/api/fleet/devices/${deviceId}/logs`);

            const logs = await res.json();

            const tbody = document.getElementById("device-log-table");

            tbody.innerHTML = "";

            if (logs.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="10" align="center">
                            No Logs
                        </td>
                    </tr>
                `;
                return;
            }

            logs.forEach(log => {

                tbody.innerHTML += `
                    <tr>
                        <td>${log.gps_time}</td>
                        <td>${log.speed} km/h</td>
                        <td>${Number(log.latitude).toFixed(6)}</td>
                        <td>${Number(log.longitude).toFixed(6)}</td>
                        <td>${log.battery ?? "-"} V</td>
                        <td>${log.gsm_signal ?? "-"}</td>
                        <td>${log.ignition ? '<span class="badge badge-success">ON</span>' : '<span class="badge badge-secondary">OFF</span>'}</td>
                        <td>${log.odometer ?? "-"}</td>
                        <td>${log.dout1 ?? "-"}</td>
                        <td>${log.gnss ?? "-"}</td>
                    </tr>
                `;

            });

        }

        const showDetails = (device) => {
            activeDeviceId = device.id;
            document.getElementById('detail-name').textContent = device.name;
            document.getElementById('detail-imei').textContent = device.imei;
            document.getElementById('detail-model').textContent = device.model;
            document.getElementById('detail-speed').textContent = `${device.speed || 0} km/h`;
            document.getElementById('detail-latitude').textContent = device.latitude ?? '-';
            document.getElementById('detail-longitude').textContent = device.longitude ?? '-';
            document.getElementById('detail-altitude').textContent = device.altitude ?? '-';
            document.getElementById('detail-angle').textContent = device.angle ?? '-';
            document.getElementById('detail-satellites').textContent = device.satellites ?? '-';
            document.getElementById('detail-battery').textContent = device.battery ? `${device.battery} V` : '-';
            document.getElementById('detail-voltage').textContent = device.voltage ? `${device.voltage} V` : '-';
            document.getElementById('detail-gsm').textContent = device.gsm_signal ?? '-';
            document.getElementById('detail-ignition').textContent = device.ignition ? 'ON' : 'OFF';
            document.getElementById('detail-time').textContent = device.gps_time ?? '-';
            loadDeviceLogs(device.id);
            // const btn = document.getElementById('view-history-btn');
            // btn.onclick = () => loadHistory(device.id, device.name);

            document.getElementById('details-panel').classList.add('visible');

            // Close sidebar on mobile to show the map
            if (isMobile()) closeSidebar();

            // Zoom to marker
            if (markersMap[device.id]) {
                const marker = markersMap[device.id];
                map.flyTo(marker.getLatLng(), 15);
                marker.openPopup();
            }
        };

        // --- DATA FETCHING ---
        async function loadDevices() {
            try {
                const res = await fetch("{{ route('fleet.devices') }}");
                const devices = await res.json();
                allDevices = devices;
                renderDeviceList(devices);
                updateMarkers(devices);
                updateStats(devices);
            } catch (err) {
                console.error("Fetch error:", err);
            }
        }

        function updateStats(devices) {
            document.getElementById('count-total').textContent = devices.length;
            document.getElementById('count-moving').textContent = devices.filter(d => d.online && (d.speed || 0) > 0).length;
            document.getElementById('count-online').textContent = devices.filter(d => d.online && (d.speed || 0) == 0).length;
            document.getElementById('count-offline').textContent = devices.filter(d => !d.online).length;
        }

        function renderDeviceList(devices) {
            const container = document.getElementById('deviceList');
            const searchTerm = document.getElementById('deviceSearch').value.toLowerCase();

            const filtered = devices.filter(d =>
                d.name.toLowerCase().includes(searchTerm) ||
                d.imei.toLowerCase().includes(searchTerm)
            );

            if (filtered.length === 0) {
                container.innerHTML = `<div style="padding: 20px; text-align: center; color: var(--secondary);">No devices found</div>`;
                return;
            }

            container.innerHTML = filtered.map(d => {
                const isOnline = d.online;
                const isMoving = isOnline && (d.speed || 0) > 0;
                const statusClass = !isOnline ? 'status-offline' : (isMoving ? 'status-moving' : 'status-online');

                return `
                    <div class="device-item ${activeDeviceId === d.id ? 'active' : ''}" onclick="showDetails(${JSON.stringify(d).replace(/"/g, '&quot;')})">
                        <div class="device-icon">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <div class="device-info">
                            <div class="device-name">${d.customer_name} - ${d.name}</div>
                            <div class="device-meta">
                                <span class="status-dot ${statusClass}"></span>
                                ${d.model} • ${isMoving ? d.speed + ' km/h' : (isOnline ? 'Online' : 'Offline')}
                            </div>
                        </div>
                        <div class="device-actions">
                            <button class="device-menu-btn" onclick="event.stopPropagation(); toggleDeviceMenu(${d.id})" aria-label="Device options">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="device-dropdown" id="device-menu-${d.id}">
                                <button onclick="event.stopPropagation(); editDevice(${d.id})">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                                <button onclick="event.stopPropagation(); viewDeviceHistory(${d.id})">
                                    <i class="fa-solid fa-route"></i> History (Last Hour)
                                </button>
                                <button onclick="event.stopPropagation(); viewDeviceHistory(${d.id})">
                                    <i class="fa-solid fa-route"></i> History (Today)
                                </button>
                                <button onclick="event.stopPropagation(); viewDeviceHistory(${d.id})">
                                    <i class="fa-solid fa-route"></i> History (Yesterday)
                                </button>
                                <button onclick="event.stopPropagation(); confirmDeleteDevice(${d.id})" class="danger">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function toggleDeviceMenu(deviceId) {
            const menu = document.getElementById(`device-menu-${deviceId}`);
            const isOpen = menu.classList.contains('open');

            // tutup semua dropdown lain dulu
            document.querySelectorAll('.device-dropdown.open').forEach(el => el.classList.remove('open'));

            if (!isOpen) menu.classList.add('open');
        }

        // tutup dropdown kalau klik di luar area menu
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.device-actions')) {
                document.querySelectorAll('.device-dropdown.open').forEach(el => el.classList.remove('open'));
            }
        });

        function editDevice(deviceId) {
            const device = allDevices.find(d => d.id === deviceId);
            if (!device) return;

            // Reset form and tabs
            document.getElementById('editDeviceForm').reset();
            resetEditModalTabs();

            // Populate fields
            document.getElementById('edit-device-id').value = device.id;
            document.getElementById('edit-name').value = device.name || '';
            document.getElementById('edit-imei').value = device.imei || '';
            document.getElementById('edit-model').value = device.model || '';
            document.getElementById('edit-active').checked = true;
            toggleEditActiveFields();
            // Populate user select
            const userSelect = document.getElementById('edit-user');
            userSelect.innerHTML = '<option value="">-- Pilih user --</option>';
            fetch('/api/users')
                .then(res => res.json())
                .then(users => {
                    users.forEach(u => {
                        const opt = document.createElement('option');
                        opt.value = u.id;
                        opt.textContent = u.email;
                        if (device.user_id && u.id === device.user_id) opt.selected = true;
                        userSelect.appendChild(opt);
                    });
                })
                .catch(err => console.error('Failed load users for edit:', err));
            // Icon selection
            const iconBtn = document.querySelector(`#edit-tab-icons .icon-option[data-icon="${device.icon || 'truck-fast'}"]`);
            if (iconBtn) selectEditDeviceIcon(iconBtn);
            // Expiration
            if (device.expiration) {
                document.getElementById('edit-expiration-enabled').checked = true;
                document.getElementById('edit-expiration').value = device.expiration.split('T')[0];
                document.getElementById('edit-expiration').disabled = false;
            } else {
                document.getElementById('edit-expiration-enabled').checked = false;
                document.getElementById('edit-expiration').value = '';
                document.getElementById('edit-expiration').disabled = true;
            }
            // Open modal
            document.getElementById('editDeviceModal').classList.add('open');
        }

        function closeEditModal() {
            document.getElementById('editDeviceModal').classList.remove('open');
        }

        document.getElementById('editDeviceForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const deviceId = document.getElementById('edit-device-id').value;
            const payload = {
                name: document.getElementById('edit-name').value,
                imei: document.getElementById('edit-imei').value,
                model: document.getElementById('edit-model').value,
            };

            try {
                const res = await fetch(`/api/fleet/devices/${deviceId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) throw new Error('Gagal update device');

                closeEditModal();
                loadDevices(); // refresh list biar data terbaru keliatan

            } catch (err) {
                console.error('Edit device error:', err);
                alert('Gagal menyimpan perubahan device.');
            }
        });

        // tutup modal kalau klik area gelap di luar box
        document.getElementById('editDeviceModal').addEventListener('click', (e) => {
            if (e.target.id === 'editDeviceModal') closeEditModal();
        });

        function viewDeviceHistory(deviceId) {
            const device = allDevices.find(d => d.id === deviceId);
            if (device) loadHistory(deviceId, device.name);
        }

        function confirmDeleteDevice(deviceId) {
            if (confirm('Yakin mau hapus device ini?')) {
                // TODO: panggil DELETE /api/devices/{id}
                console.log('Delete device', deviceId);
            }
        }

        function updateMarkers(devices) {
            markerGroup.clearLayers();
            markersMap = {};

            devices.forEach(d => {
                if (!d.latitude || !d.longitude) return;

                const marker = L.marker([d.latitude, d.longitude], { icon: customIcon });

                const popupContent = `
                    <div style="min-width:220px">
                    <b>${d.name}</b>
                    <hr>
                    <div class="custom-popup-row">
                    <span>IMEI</span>
                    <span>${d.imei}</span>
                    </div>

                    <div class="custom-popup-row">
                    <span>Speed</span>
                    <span>${d.speed} km/h</span>
                    </div>

                    <div class="custom-popup-row">
                    <span>Altitude</span>
                    <span>${d.altitude ?? 0} m</span>
                    </div>

                    <div class="custom-popup-row">
                    <span>Battery</span>
                    <span>${d.battery ?? '-'} V</span>
                    </div>

                    <div class="custom-popup-row">
                    <span>Voltage</span>
                    <span>${d.voltage ?? '-'} V</span>
                    </div>

                    <div class="custom-popup-row">
                    <span>Signal</span>
                    <span>${d.gsm_signal ?? '-'}</span>
                    </div>

                    <div class="custom-popup-row">
                    <span>Ignition</span>
                    <span>${d.ignition ? 'ON' : 'OFF'}</span>
                    </div>

                    <div class="custom-popup-row">
                    <span>GPS Time</span>
                    <span>${formatDateTime(d.gps_time)}</span>
                    </div>

                    </div>
                    `;

                marker.bindPopup(popupContent);
                marker.on('click', () => showDetails(d));

                markerGroup.addLayer(marker);
                markersMap[d.id] = marker;
            });
        }

        async function loadHistory(deviceId, deviceName) {
            // const btn = document.getElementById('view-history-btn');
            // const originalHtml = btn.innerHTML;

            try {
                // btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Loading...`;
                // btn.disabled = true;

                if (activePolyline) map.removeLayer(activePolyline);

                const res = await fetch(`/api/fleet/devices/${deviceId}/history`);
                const history = await res.json();

                if (!Array.isArray(history) || history.length < 2) {
                    alert("No movement history found for this device.");
                    return;
                }

                const path = history.map(p => [p.latitude, p.longitude]);
                activePolyline = L.polyline(path, {
                    color: '#0d9488',
                    weight: 5,
                    opacity: 0.7,
                    lineJoin: 'round'
                }).addTo(map);

                map.fitBounds(activePolyline.getBounds(), { padding: [50, 50] });

            } catch (err) {
                console.error("History error:", err);
            } finally {
                // btn.innerHTML = originalHtml;
                // btn.disabled = false;
            }
        }

        // --- EVENTS ---
        document.getElementById('deviceSearch').addEventListener('input', () => renderDeviceList(allDevices));

        // Handle window resize: close sidebar on transition to desktop, fix map
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (!isMobile()) {
                    overlay.classList.remove('active');
                }
                map.invalidateSize();
            }, 150);
        });

        // Fix map size after sidebar transitions
        sidebar.addEventListener('transitionend', () => map.invalidateSize());

        // --- INITIAL LOAD ---
        loadDevices();
        setInterval(loadDevices, 15000); // Update every 15s

    </script>
</body>

</html>