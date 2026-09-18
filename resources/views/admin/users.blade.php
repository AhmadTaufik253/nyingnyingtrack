<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NyingnyingTrack | Fleet Monitor</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- CSS utama kamu (style.css yang sudah ada, isinya sidebar/map/badge/modal dll) --}}
    <link rel="stylesheet" href="{{ asset('css/fleet-map.css') }}">

    <style>
        /* ==== Halaman ini nggak pake sidebar map, jadi override body ==== */
        body {
            display: block;
            overflow: auto;
            height: auto;
            min-height: 100vh;
        }

        /* ==== Top Navbar ==== */
        .topnav {
            height: 56px;
            background: white;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topnav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-right: 32px;
        }

        .topnav-brand img { height: 26px; }

        .topnav-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .topnav-brand-text strong {
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: -0.01em;
        }

        .topnav-brand-text span {
            font-size: 0.62rem;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .topnav-menu {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-grow: 1;
        }

        .topnav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--secondary);
            text-decoration: none;
            transition: all 0.15s;
            white-space: nowrap;
        }

        .topnav-link:hover { background: var(--bg-hover); color: var(--dark); }

        .topnav-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .topnav-link .count { font-size: 0.7rem; opacity: 0.75; }

        .topnav-user {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: var(--secondary);
            white-space: nowrap;
        }

        .topnav-user strong { color: var(--dark); font-weight: 600; }

        /* ==== Page body ==== */
        .page-body {
            padding: 20px 24px;
            background: var(--light);
            min-height: calc(100vh - 56px - 36px);
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
        }

        .page-title i { color: var(--secondary); font-size: 0.95rem; }

        .page-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .page-header-actions .search-container { width: 220px; }

        .btn-primary-solid {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 0.82rem;
            font-family: inherit;
            cursor: pointer;
            transition: filter 0.2s;
            white-space: nowrap;
        }

        .btn-primary-solid:hover { filter: brightness(1.08); }

        /* ==== Users table card ==== */
        .table-card {
            background: white;
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .users-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }

        .users-table thead th {
            text-align: left;
            padding: 12px 16px;
            background: var(--bg-hover);
            color: var(--secondary);
            font-weight: 600;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border-bottom: 1px solid var(--border-subtle);
            white-space: nowrap;
        }

        .users-table tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-faint);
            color: var(--dark);
            white-space: nowrap;
        }

        .users-table tbody tr:last-child td { border-bottom: none; }
        .users-table tbody tr:hover { background: var(--bg-hover); }

        .users-table td.email { font-weight: 500; }
        .users-table td.muted { color: var(--secondary); }

        .chevron-toggle-btn i {
            transition: transform 0.2s;
        }
        .chevron-toggle-btn.expanded i {
            transform: rotate(180deg);
        }

        .device-list-row td {
            padding: 0 16px 16px !important;
            border-bottom: 1px solid var(--border-faint);
            background: var(--bg-hover);
        }

        .mini-devices-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }
        .mini-devices-table thead th {
            text-align: left;
            padding: 8px 10px;
            color: var(--secondary);
            font-weight: 600;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border-bottom: 1px solid var(--border-subtle);
        }
        .mini-devices-table tbody td {
            padding: 10px;
            border-bottom: 1px solid var(--border-faint);
            color: var(--dark);
        }
        .mini-devices-table tbody tr:last-child td {
            border-bottom: none;
        }
        .mini-devices-table tbody tr:hover {
            background: var(--bg-hover);
        }
        .online-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        .online-dot.online {
            background: var(--success);
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
        }
        .online-dot.offline {
            background: var(--danger);
        }

        .device-list-panel {
            padding: 12px;
            background: white;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
        }

        .device-list-loading, .device-list-empty {
            padding: 12px;
            text-align: center;
            color: var(--secondary);
            font-size: 0.8rem;
        }

        .device-mini-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 10px;
            border-bottom: 1px solid var(--border-faint);
            font-size: 0.82rem;
        }
        .device-mini-item:last-child { border-bottom: none; }
        .device-mini-item .plate { font-weight: 600; color: var(--dark); }
        .device-mini-item .imei { color: var(--secondary); font-size: 0.75rem; }

        .row-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 4px;
        }

        .icon-btn {
            background: none;
            border: none;
            color: var(--secondary);
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 6px;
            transition: background 0.15s, color 0.15s;
            font-size: 0.85rem;
        }

        .icon-btn:hover { background: var(--bg-hover-strong); color: var(--dark); }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--secondary);
            font-size: 0.85rem;
        }

        .page-footer {
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            color: var(--secondary);
            border-top: 1px solid var(--border-faint);
            background: white;
        }

        /* ==== Responsive: navbar & table jadi scrollable di layar kecil ==== */
        @media (max-width: 900px) {
            .topnav-menu {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .topnav-user span.email-text { display: none; }
            .page-header-actions .search-container { width: 160px; }
        }

        @media (max-width: 600px) {
            .topnav { padding: 0 12px; }
            .topnav-brand { margin-right: 12px; }
            .page-body { padding: 14px 12px; }
            .page-header-actions .search-container { width: 100%; }
            .page-header-actions { width: 100%; }
            .users-table { font-size: 0.76rem; }
        }
    </style>
</head>
<body>

    {{-- ============ TOP NAVBAR ============ --}}
    <nav class="topnav">
        <div class="topnav-brand">
            <img src="{{ asset('assets/logo-curut-v2.png') }}" alt="{{ config('app.name') }}">
            <div class="topnav-brand-text">
                <strong>{{ strtoupper('NyingNyingTrack') }}</strong>
                <span>Admin</span>
            </div>
        </div>

        <div class="topnav-menu">
            <a href="{{ route('fleet.map') }}" class="topnav-link">
                <i class="fa-solid fa-map"></i> Map
            </a>
            <a href="{{ route('admin.users') }}" class="topnav-link active">
                <i class="fa-solid fa-users"></i> Users
                <span class="count">({{ method_exists($users, 'total') ? $users->total() : $users->count() }})</span>
            </a>
            <a href="{{ route('admin.objects') }}" class="topnav-link">
                <i class="fa-solid fa-truck-fast"></i> Objects
                <span class="count">({{ method_exists($devices, 'total') ? $devices->total() : $devices->count() }})</span>
            </a>
            <a href="#" class="topnav-link">
                <i class="fa-solid fa-file-lines"></i> Content
            </a>
        </div>

        <div class="topnav-user-wrapper" style="position:relative;">
            <div class="topnav-user" onclick="toggleUserMenu(event)" style="cursor:pointer;">
                <span class="email-text">{{ auth()->user()->email ?? '-' }}</span>
                <strong>({{ auth()->user()->role }})</strong>
                <i class="fa-solid fa-chevron-down" style="font-size:0.65rem;"></i>
            </div>

            <div class="device-dropdown" id="topnavUserDropdown" style="right:0; left:auto; min-width:180px;">
                <div style="padding:10px 10px 8px; border-bottom:1px solid var(--border-faint); margin-bottom:4px;">
                    <div style="font-size:0.8rem; font-weight:600; color:var(--dark);">
                        {{ auth()->user()->email ?? '-' }}
                    </div>
                    <div style="font-size:0.7rem; color:var(--secondary);">
                        {{ auth()->user()->role }}
                    </div>
                </div>

                <button type="button" onclick="window.location.href='#'">
                    <i class="fa-solid fa-user"></i> Profile
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- ============ PAGE BODY ============ --}}
    <div class="page-body">

        <div class="page-header">
            <div class="page-title">
                <i class="fa-solid fa-users"></i> Users
            </div>

            <div class="page-header-actions">
                <div class="search-container">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                        type="text"
                        id="userSearch"
                        class="search-input"
                        placeholder="Search"
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="search-container">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="deviceImeiSearch" class="search-input" placeholder="Search device imei" value="{{ request('imei') }}">
                </div>

                {{-- <button type="button" class="btn-primary-solid" onclick="openAddUserModal()"> --}}
                <button type="button" class="btn-primary-solid" onclick="openAddDeviceModal()">
                    <i class="fa-solid fa-plus"></i> Add
                </button>
            </div>
        </div>

        <div class="table-card">
            <div class="log-table-wrapper" style="border:none; border-radius:0; max-height:none;">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th style="width:90px;">Active</th>
                            <th>Email</th>
                            <th>Devices</th>
                            <th>Devices limit</th>
                            <th>Expiration date</th>
                            <th>Last login</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <button type="button"
                                            class="status-toggle-btn {{ $user->is_active ? 'badge-success' : 'badge-secondary' }}"
                                            onclick="toggleUserActive({{ $user->id }}, this)"
                                            title="Klik untuk {{ $user->is_active ? 'nonaktifkan' : 'aktifkan' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="email">{{ $user->email }}</td>
                                <td>{{ $user->devices_count }}</td>
                                <td class="muted">
                                    {{ $user->devices_limit ? $user->devices_limit : 'Unlimited' }}
                                </td>
                                <td class="muted">
                                    {{ $user->expiration_date ? \Carbon\Carbon::parse($user->expiration_date)->format('Y-m-d') : 'Unlimited' }}
                                </td>
                                <td class="muted">
                                    {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('Y-m-d H:i:s') : '-' }}
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <div class="device-actions">
                                            <button type="button" class="device-menu-btn"
                                                    onclick="toggleUserDropdown(event, {{ $user->id }})">
                                                <i class="fa-solid fa-gear"></i>
                                            </button>
                                            <div class="device-dropdown" id="user-dropdown-{{ $user->id }}">
                                                <button type="button" onclick="openEditUserModal({{ $user->id }})">
                                                    <i class="fa-solid fa-pen"></i> Edit
                                                </button>
                                                <button type="button" onclick="impersonateUser({{ $user->id }})">
                                                    <i class="fa-solid fa-right-to-bracket"></i> Login as
                                                </button>
                                            </div>
                                        </div>
                                        <button type="button" class="icon-btn chevron-toggle-btn" title="Lihat devices"
                                                onclick="toggleUserDevices({{ $user->id }}, this)">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr class="device-list-row" id="device-row-{{ $user->id }}" style="display:none;">
                                <td colspan="7">
                                    <div class="device-list-panel" id="device-list-{{ $user->id }}">
                                        <div class="device-list-loading">Loading devices...</div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">No users found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if (method_exists($users, 'links'))
            <div style="margin-top:16px;">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <div class="page-footer">
        {{ date('Y') }} &copy; Fleet Telematics System | {{ request()->ip() }} | v3.6.12
    </div>

    {{-- ============ ADD / EDIT USER MODAL ============ --}}
    <div class="modal-overlay" id="addDeviceModal">
        <div class="modal-box modal-box-wide">
            <div class="modal-header">
                <h3>Add</h3>
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
                    <button type="submit" class="btn-submit-modal">Save</button>
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

        document.getElementById('add-active').addEventListener('change', function () {
            toggleActiveFields();
        });

        document.getElementById('add-expiration-enabled').addEventListener('change', function () {
            document.getElementById('add-expiration').disabled = !this.checked;
        });

        async function loadUserOptions() {
            try {
                const res = await fetch('/api/users'); // sesuaikan endpoint yang ada
                const users = await res.json();
                const select = document.getElementById('add-user');
                select.innerHTML = '<option value="">-- Pilih user --</option>' +
                    users.map(u => `<option value="${u.id}">${u.email}</option>`).join('');
            } catch (err) {
                console.error('Gagal load user list:', err);
            }
        }

        function closeAddDeviceModal() {
            document.getElementById('addDeviceModal').classList.remove('open');
        }

        function switchDeviceTab(event, tabName) {
            // Scope to Add Device Modal only
            document.querySelectorAll('#addDeviceModal .modal-tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('#addDeviceModal .modal-tab-btn').forEach(b => b.classList.remove('active'));

            document.getElementById(`tab-${tabName}`).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        const loadedDeviceLists = new Set();

        async function toggleUserDevices(userId, btnEl) {
            const row = document.getElementById(`device-row-${userId}`);
            const isOpen = row.style.display !== 'none';

            if (isOpen) {
                row.style.display = 'none';
                btnEl.classList.remove('expanded');
                return;
            }

            row.style.display = 'table-row';
            btnEl.classList.add('expanded');

            if (loadedDeviceLists.has(userId)) return;

            const container = document.getElementById(`device-list-${userId}`);

            try {
                const res = await fetch(`/admin/users/${userId}/devices`, {
                    headers: { 'Accept': 'application/json' }
                });
                const devices = await res.json();

               if (!devices.length) {
                    container.innerHTML = '<div class="device-list-empty">Belum ada device terpasang</div>';
                } else {
                    container.innerHTML = `
                        <table class="mini-devices-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>IMEI</th>
                                    <th>Online</th>
                                    <th>Expiration date</th>
                                    <th style="text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${devices.map(d => `
                                    <tr>
                                        <td>${d.plate_number ?? d.name}</td>
                                        <td class="muted">${d.imei ?? '-'}</td>
                                        <td>
                                            <span class="online-dot ${d.is_online ? 'online' : 'offline'}"></span>
                                        </td>
                                        <td class="muted">${d.expiration_date ?? '-'}</td>
                                        <td style="text-align:right;">
                                            <button type="button" class="icon-btn" title="Settings" onclick="openEditDeviceModal(${d.id})">
                                                <i class="fa-solid fa-gear"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    `;
                }

                loadedDeviceLists.add(userId);
            } catch (err) {
                console.error(err);
                container.innerHTML = '<div class="device-list-empty">Gagal memuat devices</div>';
            }
        }

        function toggleUserMenu(event) {
            event.stopPropagation();
            document.querySelectorAll('.device-dropdown.open').forEach(el => {
                if (el.id !== 'topnavUserDropdown') el.classList.remove('open');
            });
            document.getElementById('topnavUserDropdown').classList.toggle('open');
        }

        // --- Dropdown per baris (tutup dropdown lain kalau buka yang baru) ---
        function toggleUserDropdown(event, userId) {
            event.stopPropagation();
            document.querySelectorAll('.device-dropdown.open').forEach(el => {
                if (el.id !== `user-dropdown-${userId}`) el.classList.remove('open');
            });
            document.getElementById(`user-dropdown-${userId}`).classList.toggle('open');
        }

        document.addEventListener('click', () => {
            document.querySelectorAll('.device-dropdown.open').forEach(el => el.classList.remove('open'));
        });

        // --- Modal Add/Edit ---
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
        function openAddUserModal() {
            document.getElementById('userModalTitle').textContent = 'Add User';
            document.getElementById('userForm').reset();
            document.getElementById('userFormId').value = '';
            document.getElementById('userFormMethod').value = 'POST';
            document.getElementById('userForm').action = "{{ url('/admin/users') }}";
            document.getElementById('expirationDateInput').disabled = true;
            document.getElementById('userModal').classList.add('open');
        }



        async function openEditUserModal(userId) {
            document.getElementById('userModalTitle').textContent = 'Edit User';
            document.getElementById('userFormId').value = userId;
            document.getElementById('userFormMethod').value = 'PUT';
            document.getElementById('userForm').action = `/admin/users/${userId}`;

            try {
                const res = await fetch(`/admin/users/${userId}/edit`, {
                    headers: { 'Accept': 'application/json' }
                });
                const user = await res.json();

                document.querySelector('#userForm [name="email"]').value = user.email ?? '';
                document.querySelector('#userForm [name="devices_limit"]').value = user.devices_limit ?? '';
                document.querySelector('#userForm [name="is_active"]').checked = !!user.is_active;

                if (user.expiration_date) {
                    document.getElementById('expirationToggle').checked = true;
                    document.getElementById('expirationDateInput').disabled = false;
                    document.getElementById('expirationDateInput').value = user.expiration_date;
                } else {
                    document.getElementById('expirationToggle').checked = false;
                    document.getElementById('expirationDateInput').disabled = true;
                    document.getElementById('expirationDateInput').value = '';
                }
            } catch (err) {
                console.error('Failed to load user data:', err);
            }

            document.getElementById('userModal').classList.add('open');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.remove('open');
        }

        function toggleUserActive(userId, btnEl) {
            btnEl.disabled = true;

            fetch(`/admin/users/${userId}/toggle-active`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(res => {
                if (!res.ok) throw new Error('Failed to toggle status');
                return res.json();
            })
            .then(data => {
                const isActive = data.is_active;
                btnEl.textContent = isActive ? 'Active' : 'Inactive';
                btnEl.classList.toggle('badge-success', isActive);
                btnEl.classList.toggle('badge-secondary', !isActive);
                btnEl.title = `Klik untuk ${isActive ? 'nonaktifkan' : 'aktifkan'}`;
            })
            .catch(err => {
                console.error(err);
                alert('Gagal update status user, coba lagi.');
            })
            .finally(() => {
                btnEl.disabled = false;
            });
        }

        function impersonateUser(userId) {
            window.location.href = `/admin/users/${userId}/login-as`;
        }

        // --- Search (debounce sederhana, reload via query string) ---
        let searchTimer;
        document.getElementById('userSearch').addEventListener('input', (e) => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                const params = new URLSearchParams(window.location.search);
                params.set('search', e.target.value);
                window.location.search = params.toString();
            }, 500);
        });

        document.getElementById('deviceImeiSearch').addEventListener('input', (e) => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                const params = new URLSearchParams(window.location.search);
                params.set('imei', e.target.value);
                window.location.search = params.toString();
            }, 500);
        });
    </script>
</body>
</html>
