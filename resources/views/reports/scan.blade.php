<x-app-layout>
    <x-slot name="title">Scan QR Code</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        /* Glass morphism card */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Camera container */
        #reader {
            width: 100%;
            max-width: 500px;
            aspect-ratio: 4/3;
            border-radius: 1.5rem;
            overflow: hidden;
            position: relative;
            background: #f8fafc;
        }

        #reader video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 1.5rem;
        }

        /* Inactive state placeholder */
        .camera-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 3px dashed #cbd5e1;
            border-radius: 1.5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transition: all 0.3s ease;
        }

        .camera-placeholder:hover {
            border-color: #009B77;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }

        /* Scanning frame overlay */
        .scanning-frame {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid #009B77;
            border-radius: 1rem;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .scanning-frame.active {
            opacity: 1;
        }

        /* Corner accents */
        .corner-accent {
            position: absolute;
            width: 24px;
            height: 24px;
            border: 3px solid #009B77;
        }

        .corner-accent.tl { top: -3px; left: -3px; border-right: none; border-bottom: none; border-radius: 8px 0 0 0; }
        .corner-accent.tr { top: -3px; right: -3px; border-left: none; border-bottom: none; border-radius: 0 8px 0 0; }
        .corner-accent.bl { bottom: -3px; left: -3px; border-right: none; border-top: none; border-radius: 0 0 0 8px; }
        .corner-accent.br { bottom: -3px; right: -3px; border-left: none; border-top: none; border-radius: 0 0 8px 0; }

        /* Scanning line animation */
        .scanning-line {
            position: absolute;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #009B77, #009B77, transparent);
            box-shadow: 0 0 10px rgba(0, 155, 119, 0.5);
            animation: scan 2.5s ease-in-out infinite;
        }

        @keyframes scan {
            0%, 100% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: calc(100% - 3px); opacity: 0; }
        }

        /* Input field with icon */
        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            transition: color 0.2s;
        }

        .input-wrapper input:focus + i {
            color: #009B77;
        }

        /* Info cards */
        .info-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            border-color: #009B77;
            transform: translateY(-2px);
        }

        /* Toast notification */
        .toast {
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* HTTP Mode Indicator */
        .http-mode-indicator {
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            border: 1px solid #fbbf24;
            color: #92400e;
        }

        /* HTTPS Mode Indicator */
        .https-mode-indicator {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 1px solid #34d399;
            color: #065f46;
        }

        /* File input styling */
        .file-input-button {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .file-input-button input[type=file] {
            position: absolute;
            left: -9999px;
        }

        /* Loading spinner */
        .spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #009B77;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    @endpush

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-6xl">
            <!-- Hero Section -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-ebara-600 to-ebara-800 rounded-2xl shadow-lg mb-4">
                    <i class="ph ph-qr-code text-4xl text-white"></i>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Scan Asset QR Code</h1>
                <p class="text-lg text-gray-600 max-w-md mx-auto">
                    Gunakan kamera perangkat Anda untuk memindai QR code aset atau masukkan kode secara manual
                </p>
            </div>

            <!-- Mode Indicator -->
            <div id="modeIndicator" class="mb-6 rounded-xl p-4 text-center font-medium">
                <!-- Will be populated by JavaScript -->
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column: Scanner -->
                <div class="glass-card rounded-3xl shadow-2xl p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="ph ph-camera text-ebara-600"></i>
                        <span>QR Scanner</span>
                    </h2>

                    <!-- Error Toast -->
                    <div id="errorToast" class="hidden toast mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <i class="ph ph-warning-circle text-2xl text-red-600 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-red-900">Terjadi Kesalahan</p>
                                <p id="errorMessage" class="text-red-700 text-sm mt-1"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Success Toast -->
                    <div id="successToast" class="hidden toast mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <i class="ph ph-check-circle text-2xl text-green-600 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="font-semibold text-green-900">Berhasil</p>
                                <p id="successMessage" class="text-green-700 text-sm mt-1"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Camera Viewfinder -->
                    <div id="reader" class="mb-6">
                        <!-- Placeholder (Inactive State) -->
                        <div id="cameraPlaceholder" class="camera-placeholder">
                            <i class="ph ph-camera text-6xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500 font-medium">Menyiapkan scanner...</p>
                            <p class="text-gray-400 text-sm mt-2">Mohon tunggu sebentar</p>
                        </div>

                        <!-- Scanning Frame Overlay -->
                        <div id="scanningFrame" class="scanning-frame">
                            <div class="corner-accent tl"></div>
                            <div class="corner-accent tr"></div>
                            <div class="corner-accent bl"></div>
                            <div class="corner-accent br"></div>
                            <div class="scanning-line"></div>
                        </div>
                    </div>

                    <!-- Hidden File Input for HTTP Mode -->
                    <input 
                        type="file" 
                        id="qr-input-file" 
                        accept="image/*" 
                        capture="environment" 
                        hidden
                    >

                    <!-- Scanner Controls -->
                    <div class="flex gap-3">
                        <button
                            type="button"
                            id="startScanBtn"
                            class="flex-1 bg-ebara-600 text-white hover:bg-ebara-700 font-semibold py-4 px-6 rounded-xl text-lg flex items-center justify-center gap-2 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5"
                        >
                            <i class="ph ph-qr-code text-2xl"></i>
                            <span>Mulai Scan</span>
                        </button>

                        <button
                            type="button"
                            id="stopScanBtn"
                            class="flex-1 bg-red-600 text-white hover:bg-red-700 font-semibold py-4 px-6 rounded-xl text-lg flex items-center justify-center gap-2 hidden transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5"
                        >
                            <i class="ph ph-stop-circle text-2xl"></i>
                            <span>Stop Scan</span>
                        </button>
                    </div>
                </div>

                <!-- Right Column: Manual Input & Info -->
                <div class="space-y-6">
                    <!-- Manual Input Card -->
                    <div class="glass-card rounded-3xl shadow-2xl p-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="ph ph-keyboard text-ebara-600"></i>
                            <span>Input Manual</span>
                        </h2>

                        <form action="{{ route('reports.scan') }}" method="GET" class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode QR / Kode Aset</label>
                                <div class="input-wrapper">
                                    <i class="ph ph-magnifying-glass text-xl"></i>
                                    <input
                                        type="text"
                                        name="code"
                                        id="codeInput"
                                        required
                                        class="w-full pl-12 pr-4 py-4 text-lg font-medium border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-4 focus:ring-ebara-500/20 outline-none transition-all"
                                        placeholder="Contoh: A001 atau MOD-001"
                                    >
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-ebara-600 text-white hover:bg-ebara-700 font-semibold py-4 px-6 rounded-xl text-lg flex items-center justify-center gap-2 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                <i class="ph ph-arrow-right text-xl"></i>
                                <span>Cari Aset</span>
                            </button>
                        </form>
                    </div>

                    <!-- Supported Assets Info -->
                    <div class="info-card rounded-2xl p-6">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ph ph-info text-ebara-600"></i>
                            <span>Aset yang Didukung</span>
                        </h3>

                        <div class="grid grid-cols-3 gap-3">
                            <div class="text-center p-4 bg-white rounded-xl">
                                <i class="ph ph-cube text-3xl text-blue-600 mb-2"></i>
                                <p class="text-sm font-medium text-gray-700">Materials</p>
                            </div>
                            <div class="text-center p-4 bg-white rounded-xl">
                                <i class="ph ph-wrench text-3xl text-orange-600 mb-2"></i>
                                <p class="text-sm font-medium text-gray-700">Tools</p>
                            </div>
                            <div class="text-center p-4 bg-white rounded-xl">
                                <i class="ph ph-package text-3xl text-purple-600 mb-2"></i>
                                <p class="text-sm font-medium text-gray-700">Models</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="info-card rounded-2xl p-6">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ph ph-lightbulb text-yellow-600"></i>
                            <span>Tips Scan</span>
                        </h3>

                        <ul class="space-y-2">
                            <li class="flex items-start gap-2 text-sm text-gray-600">
                                <i class="ph ph-check-circle text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Pastikan pencahayaan cukup terang</span>
                            </li>
                            <li class="flex items-start gap-2 text-sm text-gray-600">
                                <i class="ph ph-check-circle text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Tahan perangkat dengan stabil</span>
                            </li>
                            <li class="flex items-start gap-2 text-sm text-gray-600">
                                <i class="ph ph-check-circle text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Jarak ideal: 10-20 cm dari QR code</span>
                            </li>
                            <li class="flex items-start gap-2 text-sm text-gray-600">
                                <i class="ph ph-check-circle text-green-600 flex-shrink-0 mt-0.5"></i>
                                <span>Gunakan manual input jika scan gagal</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        // Global variables
        let html5QrcodeScanner = null;
        let isScanning = false;
        let isRedirecting = false;
        let scannerMode = 'unknown'; // 'https' or 'http'

        // Audio context for beep sound
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();

        function playBeep() {
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = 880;
            oscillator.type = 'sine';
            gainNode.gain.value = 0.3;

            oscillator.start();

            setTimeout(() => {
                oscillator.stop();
            }, 150);
        }

        // Show error toast
        function showError(message) {
            const toast = document.getElementById('errorToast');
            const msgEl = document.getElementById('errorMessage');
            msgEl.textContent = message;
            toast.classList.remove('hidden');

            setTimeout(() => {
                toast.classList.add('hidden');
            }, 5000);
        }

        // Show success toast
        function showSuccess(message) {
            const toast = document.getElementById('successToast');
            const msgEl = document.getElementById('successMessage');
            msgEl.textContent = message;
            toast.classList.remove('hidden');

            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // Unified success handler for both HTTP and HTTPS modes
        function onScanSuccess(decodedText, decodedResult) {
            // Block multiple triggers
            if (isRedirecting) return;
            isRedirecting = true;

            // Get clean data
            const scannedCode = decodedText.trim();
            console.log("✅ QR CAPTURED:", scannedCode);

            // Play success sound
            playBeep();

            // Show success message
            showSuccess(`QR Code berhasil terbaca: ${scannedCode}`);

            // Construct URL
            const baseUrl = "{{ route('reports.scan') }}";
            const targetUrl = `${baseUrl}?code=${encodeURIComponent(scannedCode)}`;

            console.log("🚀 NAVIGATING TO:", targetUrl);

            // Stop scanner if running
            if (html5QrcodeScanner && isScanning) {
                try {
                    if (scannerMode === 'https') {
                        html5QrcodeScanner.stop();
                    }
                } catch (e) {
                    console.warn("Failed to stop scanner", e);
                }
            }

            // Execute redirect after a short delay to show success message
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 1000);
        }

        // On scan failure (called frequently, ignore silently)
        function onScanFailure(error) {
            // This is called when no QR code is detected
            // We ignore this silently to avoid spamming the user
            // console.debug('Scan failed:', error);
        }

        // Update mode indicator
        function updateModeIndicator(mode) {
            const indicator = document.getElementById('modeIndicator');
            const placeholder = document.getElementById('cameraPlaceholder');
            const startBtn = document.getElementById('startScanBtn');
            
            if (mode === 'https') {
                indicator.className = 'https-mode-indicator mb-6 rounded-xl p-4 text-center font-medium';
                indicator.innerHTML = `
                    <i class="ph ph-shield-check text-2xl mr-2"></i>
                    <span>Mode Kamera Live (HTTPS) - Kamera real-time tersedia</span>
                `;
                
                placeholder.innerHTML = `
                    <i class="ph ph-camera text-6xl text-green-500 mb-4"></i>
                    <p class="text-green-600 font-medium">Kamera Live Siap</p>
                    <p class="text-green-500 text-sm mt-2">Klik "Mulai Scan" untuk memulai</p>
                `;
                
                startBtn.innerHTML = `
                    <i class="ph ph-camera text-2xl"></i>
                    <span>Mulai Scan Live</span>
                `;
            } else {
                indicator.className = 'http-mode-indicator mb-6 rounded-xl p-4 text-center font-medium';
                indicator.innerHTML = `
                    <i class="ph ph-image text-2xl mr-2"></i>
                    <span>Mode Kamera Standar (HTTP) - Scan via foto/gambar</span>
                `;
                
                placeholder.innerHTML = `
                    <i class="ph ph-image text-6xl text-amber-500 mb-4"></i>
                    <p class="text-amber-600 font-medium">Scan via Foto</p>
                    <p class="text-amber-500 text-sm mt-2">Klik "Mulai Scan" untuk ambil foto</p>
                `;
                
                startBtn.innerHTML = `
                    <i class="ph ph-camera text-2xl"></i>
                    <span>Ambil Foto QR</span>
                `;
            }
        }

        // Initialize scanner based on protocol
        function initScanner() {
            const isSecureContext = location.protocol === 'https:' || 
                                   location.hostname === 'localhost' || 
                                   location.hostname === '127.0.0.1';
            
            scannerMode = isSecureContext ? 'https' : 'http';
            
            console.log(`🔍 Initializing scanner in ${scannerMode.toUpperCase()} mode`);
            
            // Update UI based on mode
            updateModeIndicator(scannerMode);
            
            // Setup event listeners based on mode
            if (scannerMode === 'https') {
                setupHttpsMode();
            } else {
                setupHttpMode();
            }
        }

        // Setup HTTPS mode (live camera)
        function setupHttpsMode() {
            const startBtn = document.getElementById('startScanBtn');
            const stopBtn = document.getElementById('stopScanBtn');
            
            startBtn.addEventListener('click', async function() {
                const placeholder = document.getElementById('cameraPlaceholder');
                const scanningFrame = document.getElementById('scanningFrame');
                
                try {
                    if (!isScanning) {
                        console.log('📷 Starting live camera...');
                        
                        // Initialize scanner
                        html5QrcodeScanner = new Html5Qrcode("reader");
                        
                        await html5QrcodeScanner.start(
                            { facingMode: "environment" },
                            {
                                fps: 10,
                                qrbox: { width: 250, height: 250 }
                            },
                            onScanSuccess,
                            onScanFailure
                        );
                        
                        // Show scanning state
                        placeholder.classList.add('hidden');
                        scanningFrame.classList.add('active');
                        startBtn.classList.add('hidden');
                        stopBtn.classList.remove('hidden');
                        isScanning = true;
                        isRedirecting = false;
                        
                        console.log('✅ Live camera started successfully');
                    }
                } catch (err) {
                    console.error('❌ Camera error:', err);
                    
                    let errorMsg = 'Gagal membuka kamera. Silakan coba lagi.';
                    
                    if (err.name === 'NotAllowedError') {
                        errorMsg = 'Izin kamera ditolak. Silakan berikan izin akses kamera di browser Anda.';
                    } else if (err.name === 'NotFoundError') {
                        errorMsg = 'Kamera tidak ditemukan. Pastikan perangkat Anda memiliki kamera.';
                    } else if (err.name === 'NotReadableError') {
                        errorMsg = 'Kamera sedang digunakan oleh aplikasi lain.';
                    } else if (err.name === 'OverconstrainedError') {
                        errorMsg = 'Kamera tidak memenuhi persyaratan yang dibutuhkan.';
                    }
                    
                    showError(errorMsg);
                    
                    // Fallback to HTTP mode if camera fails
                    console.log('🔄 Auto-falling back to HTTP mode...');
                    scannerMode = 'http';
                    updateModeIndicator('http');
                    setupHttpMode();
                }
            });
            
            // Stop camera button
            stopBtn.addEventListener('click', async function() {
                const placeholder = document.getElementById('cameraPlaceholder');
                const scanningFrame = document.getElementById('scanningFrame');
                
                if (html5QrcodeScanner && isScanning) {
                    console.log('🛑 Stopping camera...');
                    
                    await html5QrcodeScanner.stop();
                    
                    // Reset UI state
                    placeholder.classList.remove('hidden');
                    scanningFrame.classList.remove('active');
                    startBtn.classList.remove('hidden');
                    stopBtn.classList.add('hidden');
                    isScanning = false;
                    isRedirecting = false;
                    
                    console.log('✅ Camera stopped successfully');
                }
            });
        }

        // Setup HTTP mode (file upload)
        function setupHttpMode() {
            const startBtn = document.getElementById('startScanBtn');
            const fileInput = document.getElementById('qr-input-file');
            
            startBtn.addEventListener('click', function() {
                console.log('📷 Triggering file capture...');
                fileInput.click();
            });
            
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;
                
                console.log('📸 File selected:', file.name);
                
                // Show loading state
                const placeholder = document.getElementById('cameraPlaceholder');
                placeholder.innerHTML = `
                    <div class="flex flex-col items-center">
                        <div class="spinner mb-4"></div>
                        <p class="text-gray-600 font-medium">Memproses gambar...</p>
                        <p class="text-gray-500 text-sm mt-2">Membaca QR code dari foto</p>
                    </div>
                `;
                
                // Create scanner instance for file processing
                const html5QrCode = new Html5Qrcode("reader");
                
                // Process the image
                html5QrCode.scanFileV2(file, true)
                    .then(decodedText => {
                        console.log('✅ QR Code found in image:', decodedText);
                        onScanSuccess(decodedText, null);
                    })
                    .catch(err => {
                        console.error('❌ Failed to scan QR code from image:', err);
                        
                        let errorMsg = 'Tidak dapat membaca QR code dari gambar.';
                        
                        if (err.includes('No QR code found')) {
                            errorMsg = 'QR code tidak ditemukan dalam gambar. Pastikan QR code terlihat jelas.';
                        } else if (err.includes('Unable to start decoding')) {
                            errorMsg = 'Gagal memproses gambar. Silakan coba dengan gambar lain.';
                        }
                        
                        showError(errorMsg);
                        
                        // Reset placeholder
                        updateModeIndicator('http');
                    })
                    .finally(() => {
                        // Clear file input
                        fileInput.value = '';
                    });
            });
        }

        // Initialize scanner on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            initScanner();
        });
    </script>
    @endpush
</x-app-layout>
