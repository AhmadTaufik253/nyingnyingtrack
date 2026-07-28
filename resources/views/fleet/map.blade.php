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
        
        <button id="view-history-btn" class="history-btn">
            <i class="fa-solid fa-route"></i> View Movement History
        </button>
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
                                    ? '<span class="badge badge-success">ON</span>'
                                    : '<span class="badge badge-secondary">OFF</span>'
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
            // document.getElementById('detail-time').textContent = formatDateTime(device.updated_at);
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
            document.getElementById('count-stationary').textContent = devices.filter(d => d.online && (d.speed || 0) == 0).length;
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
                    color: '#0d9488',
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
