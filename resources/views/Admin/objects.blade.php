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
            <a href="{{ route('admin.users') }}" class="topnav-link">
                <i class="fa-solid fa-users"></i> Users
                <span class="count">({{ method_exists($users, 'total') ? $users->total() : $users->count() }})</span>
            </a>
            <a href="{{ route('admin.objects') }}" class="topnav-link active">
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
                <i class="fa-solid fa-users"></i> Objects
            </div>

            <div class="page-header-actions">
                <div class="search-container">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="imeiSearch" class="search-input" placeholder="Search" value="{{ request('search') }}">
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="log-table-wrapper" style="border:none; border-radius:0; max-height:none;">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th style="width:90px;">Active</th>
                            <th>Name</th>
                            <th>IMEI</th>
                            <th>Online</th>
                            <th>Last Connection</th>
                            <th>Expiration Date</th>
                            <th>User</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($devices as $device)
                            <tr>
                                <td>
                                    <button type="button"
                                            class="status-toggle-btn {{ $device->is_active ? 'badge-success' : 'badge-secondary' }}"
                                            onclick="toggleDeviceActive({{ $device->id }}, this)"
                                            title="Klik untuk {{ $device->is_active ? 'nonaktifkan' : 'aktifkan' }}">
                                        {{ $device->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="email">{{ $device->plate_number }}</td>
                                <td>{{ $device->imei }}</td>
                                <td class="muted">
                                    <span class="online-dot {{ $device->is_online ? 'online' : 'offline' }}"></span>
                                </td>
                                <td class="muted">
                                    {{ $device->last_position_time ? \Carbon\Carbon::parse($device->last_position_time)->format('Y-m-d H:i:s') : '-' }}
                                </td>
                                <td class="muted">
                                    -
                                </td>
                                <td class="muted">
                                    {{ $device->customer->user->email ?? '-' }}
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <div class="device-actions">
                                            <button type="button" class="device-menu-btn"
                                                    onclick="toggleUserDropdown(event, {{ $device->id }})">
                                                <i class="fa-solid fa-gear"></i>
                                            </button>
                                            <div class="device-dropdown" id="user-dropdown-{{ $device->id }}">
                                                <button type="button">
                                                    <i class="fa-solid fa-pen"></i> Edit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">No Objects found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if (method_exists($devices, 'links'))
            <div style="margin-top:16px;">
                {{ $devices->links() }}
            </div>
        @endif
    </div>

    <div class="page-footer">
        {{ date('Y') }} &copy; Fleet Telematics System | {{ request()->ip() }} | v3.6.12
    </div>

    <script>
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

        // --- Search (debounce sederhana, reload via query string) ---
        let searchTimer;
        document.getElementById('imeiSearch').addEventListener('input', (e) => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                const params = new URLSearchParams(window.location.search);
                params.set('search', e.target.value);
                window.location.search = params.toString();
            }, 500);
        });
    </script>
</body>
</html>
