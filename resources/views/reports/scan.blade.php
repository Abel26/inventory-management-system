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

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column: Scanner -->
                <div class="glass-card rounded-3xl shadow-2xl p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="ph ph-camera text-ebara-600"></i>
                        <span>Camera Scanner</span>
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

                    <!-- Camera Viewfinder -->
                    <div id="reader" class="mb-6">
                        <!-- Placeholder (Inactive State) -->
                        <div id="cameraPlaceholder" class="camera-placeholder">
                            <i class="ph ph-camera text-6xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500 font-medium">Menunggu akses kamera...</p>
                            <p class="text-gray-400 text-sm mt-2">Klik tombol di bawah untuk memulai</p>
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

                    <!-- Camera Controls -->
                    <div class="flex gap-3">
                        <button
                            type="button"
                            id="startCameraBtn"
                            class="flex-1 bg-ebara-600 text-white hover:bg-ebara-700 font-semibold py-4 px-6 rounded-xl text-lg flex items-center justify-center gap-2 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5"
                        >
                            <i class="ph ph-play-circle text-2xl"></i>
                            <span>Mulai Scan</span>
                        </button>

                        <button
                            type="button"
                            id="stopCameraBtn"
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
                                <span>Gunakan manual input jika kamera gagal</span>
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
        let html5QrcodeScanner = null;
        let isScanning = false;
        let isRedirecting = false; // Semaphore to prevent double-firing

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

        // On scan success - FIRE AND FORGET STRATEGY
        function onScanSuccess(decodedText, decodedResult) {
            // 1. Block multiple triggers
            if (isRedirecting) return;
            isRedirecting = true;

            // 2. Get Clean Data
            const scannedCode = decodedText.trim();
            console.log("✅ QR CAPTURED:", scannedCode);

            // 3. Construct URL
            // We send it to the 'scan' controller method, which validates the asset
            // and then redirects to the 'create' form.
            const baseUrl = "{{ route('reports.scan') }}";
            const targetUrl = `${baseUrl}?code=${encodeURIComponent(scannedCode)}`;

            console.log("🚀 FORCING NAVIGATION TO:", targetUrl);

            // 4. STOP SCANNER (Best effort, don't wait)
            // We try to clear it to stop the camera light, but we don't wait for the callback
            if (html5QrcodeScanner) {
                try { html5QrcodeScanner.clear(); } catch (e) { console.warn("Failed to clear scanner", e); }
            }

            // 5. EXECUTE REDIRECT
            // This happens IMMEDIATELY, before any other code can run
            window.location.href = targetUrl;
        }

        // On scan failure (called frequently, ignore silently)
        function onScanFailure(error) {
            // This is called when no QR code is detected
            // We ignore this silently to avoid spamming the user
            // console.debug('Scan failed:', error);
        }

        // Initialize Scanner on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            // Start camera button
            document.getElementById('startCameraBtn').addEventListener('click', async function() {
                const reader = document.getElementById('reader');
                const placeholder = document.getElementById('cameraPlaceholder');
                const scanningFrame = document.getElementById('scanningFrame');
                const startBtn = this;
                const stopBtn = document.getElementById('stopCameraBtn');

                try {
                    if (!isScanning) {
                        console.log('📷 Starting camera...');

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
                        isRedirecting = false; // Reset flag when starting new scan

                        console.log('✅ Camera started successfully');
                    }
                } catch (err) {
                    console.error('❌ Camera error:', err);

                    let errorMsg = 'Gagal membuka kamera. Silakan coba lagi.';

                    if (err.name === 'NotAllowedError') {
                        errorMsg = 'Kamera tidak diizinkan. Silakan berikan izin akses kamera di browser Anda.';
                    } else if (err.name === 'NotFoundError') {
                        errorMsg = 'Kamera tidak ditemukan. Pastikan perangkat Anda memiliki kamera.';
                    } else if (err.name === 'NotReadableError') {
                        errorMsg = 'Kamera sedang digunakan oleh aplikasi lain.';
                    } else if (err.name === 'OverconstrainedError') {
                        errorMsg = 'Kamera tidak mendukung fitur yang dibutuhkan.';
                    }

                    showError(errorMsg);
                }
            });

            // Stop camera button
            document.getElementById('stopCameraBtn').addEventListener('click', async function() {
                const reader = document.getElementById('reader');
                const placeholder = document.getElementById('cameraPlaceholder');
                const scanningFrame = document.getElementById('scanningFrame');
                const startBtn = document.getElementById('startCameraBtn');
                const stopBtn = this;

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
        });
    </script>
    @endpush
</x-app-layout>
