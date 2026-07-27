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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Marker Cluster -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary: #64748b;
            --dark: #0f172a;
            --light: #f8fafc;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --glass: rgba(255, 255, 255, 0.85);
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --sidebar-width: 360px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            height: 100vh;
            overflow: hidden;
            display: flex;
        }

        /* --- MAP CONTAINER --- */
        #map {
            flex-grow: 1;
            height: 100%;
            z-index: 1;
        }

        /* --- SIDEBAR --- */
        #sidebar {
            width: var(--sidebar-width);
            height: 100%;
            background: white;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid #f1f5f9;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .brand img {
            height: 40px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        .brand h1 {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: var(--dark);
        }

        .search-container {
            position: relative;
        }

        .search-container i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary);
        }

        .search-input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #f1f5f9;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.2s;
        }

        .search-input:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .device-list-container {
            flex-grow: 1;
            overflow-y: auto;
            padding: 12px;
        }

        .device-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .device-item:hover {
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        .device-item.active {
            background: rgba(37, 99, 235, 0.05);
            border-color: var(--primary);
        }

        .device-icon {
            width: 48px;
            height: 48px;
            background: #f1f5f9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--primary);
            flex-shrink: 0;
        }

        .device-info {
            flex-grow: 1;
        }

        .device-name {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .device-meta {
            font-size: 0.75rem;
            color: var(--secondary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-online { background: var(--success); box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2); }
        .status-offline { background: var(--secondary); }
        .status-moving { background: var(--primary); animation: pulse 2s infinite; }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(37, 99, 235, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
        }

        /* --- FLOATING CONTROLS --- */
        .floating-panel {
            position: absolute;
            z-index: 999;
            background: var(--glass);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255,255,255,0.4);
            padding: 16px;
        }

        #stats-panel {
            top: 20px;
            left: calc(var(--sidebar-width) + 20px);
            display: flex;
            gap: 24px;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
        }

        .stat-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--secondary);
            letter-spacing: 0.05em;
        }

        /* #details-panel {
            bottom: 20px;
            right: 20px;
            width: 400px;
            max-height: 250px;
            overflow-y: auto;
            transform: translateY(120%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        } */
        #details-panel{
            bottom:20px;
            right:20px;

            width:700px;
            max-height:85vh;
            overflow-y:auto;

            transform:translateY(120%);
            transition:transform .4s cubic-bezier(.4,0,.2,1);
        }

        .log-table-wrapper{
            margin-top:20px;
            max-height:300px;
            overflow-y:auto;
            border:1px solid #e5e7eb;
            border-radius:10px;
        }

        .log-table{
            width:100%;
            border-collapse:collapse;
            font-size:13px;
        }

        .log-table thead{
            position:sticky;
            top:0;
            background:#f8fafc;
            z-index:10;
        }

        .log-table th{
            padding:10px;
            text-align:left;
            border-bottom:1px solid #e5e7eb;
            font-weight:600;
        }

        .log-table td{
            padding:8px 10px;
            border-bottom:1px solid #f1f5f9;
        }

        .log-table tbody tr:hover{
            background:#f8fafc;
        }

        .log-table td{
            white-space:nowrap;
        }

        #details-panel.visible {
            transform: translateY(0);
        }

        .details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .detail-card {
            background: rgba(255,255,255,0.5);
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #f1f5f9;
        }

        .detail-label {
            font-size: 0.7rem;
            color: var(--secondary);
            margin-bottom: 2px;
        }

        .detail-value {
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* baru */
        .details-tabs{
            display:flex;
            margin-bottom:15px;
            gap:8px;
        }

        .tab-btn{

            flex:1;

            border:none;

            background:#f4f4f4;

            padding:10px;

            cursor:pointer;

            border-radius:8px;

            font-weight:600;

        }

        .tab-btn.active{

            background:#2563eb;

            color:white;

        }

        #device-log-list{

            max-height:430px;

            overflow-y:auto;

            display:flex;

            flex-direction:column;

            gap:10px;

        }
        /* end baru */

        /* --- TOGGLE BUTTON --- */
        #sidebar-toggle {
            position: absolute;
            left: var(--sidebar-width);
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 48px;
            background: white;
            border: 1px solid #e2e8f0;
            border-left: none;
            border-radius: 0 8px 8px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1001;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }

        /* --- MOBILE RESPONSIVE --- */
        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                transform: translateX(-100%);
                width: 280px;
            }

            #sidebar.open {
                transform: translateX(0);
            }

            #sidebar-toggle {
                left: 0;
                top: 20px;
                transform: none;
                width: 40px;
                height: 40px;
                border-radius: 8px;
                border: 1px solid #e2e8f0;
                margin-left: 10px;
                transition: left 0.3s;
            }

            #sidebar.open + #sidebar-toggle {
                left: 280px;
            }

            #stats-panel {
                left: 70px;
                top: 20px;
                padding: 10px 16px;
                gap: 16px;
                right: 20px;
                overflow-x: auto;
            }

            #details-panel {
                width: calc(100% - 40px);
                left: 20px;
            }
        }

        /* --- LEAFLET CUSTOMS --- */
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 4px;
        }

        .leaflet-popup-content b {
            font-size: 1rem;
            display: block;
            margin-bottom: 8px;
        }

        .custom-popup-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 4px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside id="sidebar">
        <div class="sidebar-header">
            <div class="brand">
                <img src="{{ asset('assets/logo-curut-v2.png') }}" alt="Logo">
                <h1>NyingnyingTrack</h1>
                
                <form action="{{ route('logout') }}" method="POST" style="margin-left: auto;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; font-size: 1.25rem; padding: 4px; transition: transform 0.2s;" title="Logout" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                </form>
            </div>
            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="deviceSearch" class="search-input" placeholder="Search devices by name or IMEI...">
            </div>
        </div>

        <div class="device-list-container" id="deviceList">
            <!-- Loader -->
            <div style="padding: 20px; text-align: center; color: var(--secondary);">
                <i class="fa-solid fa-circle-notch fa-spin"></i> Loading devices...
            </div>
        </div>
    </aside>

    <div id="sidebar-toggle">
        <i class="fa-solid fa-chevron-right"></i>
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
            <span class="stat-label">Stationary</span>
            <span class="stat-value" style="color: var(--success);" id="count-stationary">0</span>
        </div>
        <div class="stat-item">
            <span class="stat-label">Offline</span>
            <span class="stat-value" style="color: var(--secondary);" id="count-offline">0</span>
        </div>
    </div>

    <!-- Details Panel -->
    <div id="details-panel" class="floating-panel">
        <div class="details-header">
            <h3 id="detail-name" style="font-weight: 700;">Device Details</h3>
            <button onclick="hideDetails()" style="background:none; border:none; cursor:pointer; color:var(--secondary);">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <button
            class="tab-btn active"
            onclick="openTab(event, 'info')">
            Device Info
        </button>

        <button
            class="tab-btn"
            onclick="openTab(event, 'logs')">
            Logs
        </button>
        <div id="tab-info">
            <!-- details-grid -->
            <div class="details-grid">
                <div class="detail-card">
                    <div class="detail-label">IMEI</div>
                    <div class="detail-value" id="detail-imei">-</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Model</div>
                    <div class="detail-value" id="detail-model">-</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Current Speed</div>
                    <div class="detail-value" id="detail-speed">0 km/h</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Last Connection</div>
                    <div class="detail-value" id="detail-time">-</div>
                </div>
                <div class="detail-card">
                    <div class="detail-label">Latitude</div>
                    <div class="detail-value" id="detail-latitude">-</div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">Longitude</div>
                    <div class="detail-value" id="detail-longitude">-</div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">Altitude</div>
                    <div class="detail-value" id="detail-altitude">-</div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">Course</div>
                    <div class="detail-value" id="detail-angle">-</div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">Satellites</div>
                    <div class="detail-value" id="detail-satellites">-</div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">Battery</div>
                    <div class="detail-value" id="detail-battery">-</div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">Voltage</div>
                    <div class="detail-value" id="detail-voltage">-</div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">GSM Signal</div>
                    <div class="detail-value" id="detail-gsm">-</div>
                </div>

                <div class="detail-card">
                    <div class="detail-label">Ignition</div>
                    <div class="detail-value" id="detail-ignition">-</div>
                </div>
            </div>
        </div>
        <div id="tab-logs" style="display:none">
            <div class="log-table-wrapper">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Speed</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Battery</th>
                            <th>Signal</th>
                            <th>Ignition</th>
                        </tr>
                    </thead>
                    <tbody id="device-log-table">
                        <tr>
                            <td colspan="7" style="text-align:center">
                                No Data
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div style="margin-top: 16px;">
            <button id="view-history-btn" class="search-input" style="padding: 10px; background: var(--primary); color: white; border: none; font-weight: 600; cursor: pointer; width: 100%;">
                <i class="fa-solid fa-route"></i> View Movement History
            </button>
        </div>
    </div>

    <!-- Map -->
    <main id="map"></main>

    <script>
        function openTab(event, tab){

            document.getElementById('tab-info').style.display =
                tab === 'info' ? 'block' : 'none';

            document.getElementById('tab-logs').style.display =
                tab === 'logs' ? 'block' : 'none';

            document.querySelectorAll(".tab-btn")
                .forEach(b=>b.classList.remove("active"));

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

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
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
        const toggleSidebar = () => {
            const sidebar = document.getElementById('sidebar');
            const icon = document.querySelector('#sidebar-toggle i');
            sidebar.classList.toggle('open');
            icon.classList.toggle('fa-chevron-right');
            icon.classList.toggle('fa-chevron-left');
        };

        document.getElementById('sidebar-toggle').addEventListener('click', toggleSidebar);

        const hideDetails = () => {
            document.getElementById('details-panel').classList.remove('visible');
            activeDeviceId = null;
            if (activePolyline) {
                map.removeLayer(activePolyline);
                activePolyline = null;
            }
        };

        async function loadDeviceLogs(deviceId){

            const res = await fetch(`/api/fleet/devices/${deviceId}/logs`);

            const logs = await res.json();

            const tbody = document.getElementById("device-log-table");

            tbody.innerHTML = "";

            if(logs.length===0){

                tbody.innerHTML=`
                    <tr>
                        <td colspan="7" align="center">
                            No Logs
                        </td>
                    </tr>
                `;

                return;
            }

            // logs.forEach(log=>{

            //     tbody.innerHTML += `
            //         <tr>
            //             <td>${log.gps_time}</td>
            //             <td>${log.speed} km/h</td>
            //             <td>${Number(log.latitude).toFixed(6)}</td>
            //             <td>${Number(log.longitude).toFixed(6)}</td>
            //             <td>${log.battery ?? "-"}</td>
            //             <td>${log.gsm_signal ?? "-"}</td>
            //             <td>${log.ignition ? "ON" : "OFF"}</td>
            //         </tr>
            //     `;

            // });
            logs.forEach(log => {

                tbody.innerHTML += `
                    <tr>
                        <td>${log.gps_time}</td>
                        <td>${log.speed} km/h</td>
                        <td>${Number(log.latitude).toFixed(6)}</td>
                        <td>${Number(log.longitude).toFixed(6)}</td>
                        <td>${log.battery ?? "-"} V</td>
                        <td>${log.gsm_signal ?? "-"}</td>
                        <td>
                            ${
                                log.ignition
                                    ? '<span class="badge bg-success">ON</span>'
                                    : '<span class="badge bg-secondary">OFF</span>'
                            }
                        </td>
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
            // document.getElementById('detail-time').textContent = device.updated_at || 'Never';
            document.getElementById('detail-time').textContent = formatDateTime(device.updated_at);
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
            const btn = document.getElementById('view-history-btn');
            btn.onclick = () => loadHistory(device.id, device.name);
            
            document.getElementById('details-panel').classList.add('visible');

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
            document.getElementById('count-moving').textContent = devices.filter(d => (d.speed || 0) > 0).length;
            document.getElementById('count-stationary').textContent = devices.filter(d => (d.speed || 0) == 0 && d.latitude).length;
            document.getElementById('count-offline').textContent = devices.filter(d => !d.latitude).length;
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
                const isOnline = d.latitude && d.longitude;
                const isMoving = (d.speed || 0) > 0;
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
                                ${d.model} • ${isMoving ? d.speed + ' km/h' : (isOnline ? 'Stationary' : 'Offline')}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function updateMarkers(devices) {
            markerGroup.clearLayers();
            markersMap = {};

            devices.forEach(d => {
                if (!d.latitude || !d.longitude) return;

                const marker = L.marker([d.latitude, d.longitude], { icon: customIcon });
                
                // const popupContent = `
                //     <div style="min-width: 180px;">
                //         <b>${d.name}</b>
                //         <div class="custom-popup-row"><span>Speed:</span> <span>${d.speed || 0} km/h</span></div>
                //         <div class="custom-popup-row"><span>Model:</span> <span>${d.model}</span></div>
                //         <div class="custom-popup-row"><span>Updated:</span> <span>${d.updated_at || '-'}</span></div>
                //     </div>
                // `;
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
                    <span>${d.gps_time ?? '-'}</span>
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
            const btn = document.getElementById('view-history-btn');
            const originalHtml = btn.innerHTML;
            
            try {
                btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin"></i> Loading...`;
                btn.disabled = true;

                if (activePolyline) map.removeLayer(activePolyline);

                const res = await fetch(`/api/fleet/devices/${deviceId}/history`);
                const history = await res.json();

                if (!Array.isArray(history) || history.length < 2) {
                    alert("No movement history found for this device.");
                    return;
                }

                const path = history.map(p => [p.latitude, p.longitude]);
                activePolyline = L.polyline(path, {
                    color: 'var(--primary)',
                    weight: 5,
                    opacity: 0.7,
                    lineJoin: 'round'
                }).addTo(map);

                map.fitBounds(activePolyline.getBounds(), { padding: [50, 50] });

            } catch (err) {
                console.error("History error:", err);
            } finally {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        }

        // --- EVENTS ---
        document.getElementById('deviceSearch').addEventListener('input', () => renderDeviceList(allDevices));

        // --- INITIAL LOAD ---
        loadDevices();
        setInterval(loadDevices, 15000); // Update every 15s

    </script>
</body>
</html>
