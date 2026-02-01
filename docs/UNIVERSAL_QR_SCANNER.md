# Universal QR Scanner - Hybrid HTTP/HTTPS Implementation

## Overview

The Universal QR Scanner is a smart hybrid solution that seamlessly works in both HTTP (Production) and HTTPS (Development) environments while providing a consistent user experience.

## Architecture

### Dual-Mode System

The scanner automatically detects the current environment and switches between two modes:

1. **HTTPS Mode** (Secure Context): Live camera streaming using `navigator.mediaDevices.getUserMedia`
2. **HTTP Mode** (Insecure Context): File-based scanning using `capture="environment"` and `scanFileV2()`

### Key Components

```
┌─────────────────────────────────────────────────────────────┐
│                    Universal QR Scanner                     │
├─────────────────────────────────────────────────────────────┤
│  Protocol Detection                                         │
│  ├─ HTTPS/Localhost → Live Camera Mode                      │
│  └─ HTTP/IP Address → File Upload Mode                      │
├─────────────────────────────────────────────────────────────┤
│  Unified Success Handler                                     │
│  └─ Single onScanSuccess() for both modes                   │
├─────────────────────────────────────────────────────────────┤
│  Auto-Fallback Mechanism                                    │
│  └─ HTTPS → Auto-fallback to HTTP if camera fails          │
└─────────────────────────────────────────────────────────────┘
```

## Implementation Details

### 1. Protocol Detection Logic

```javascript
function initScanner() {
    const isSecureContext = location.protocol === 'https:' || 
                           location.hostname === 'localhost' || 
                           location.hostname === '127.0.0.1';
    
    scannerMode = isSecureContext ? 'https' : 'http';
    
    // Update UI and setup appropriate mode
    updateModeIndicator(scannerMode);
    
    if (scannerMode === 'https') {
        setupHttpsMode();
    } else {
        setupHttpMode();
    }
}
```

### 2. HTTPS Mode (Live Camera)

**Features:**
- Real-time camera streaming
- Continuous QR code detection
- Visual scanning frame with animations
- Stop/Start controls

**Implementation:**
```javascript
await html5QrcodeScanner.start(
    { facingMode: "environment" },  // Prefer rear camera
    {
        fps: 10,  // Optimized for performance
        qrbox: { width: 250, height: 250 }
    },
    onScanSuccess,
    onScanFailure
);
```

### 3. HTTP Mode (File Upload)

**Features:**
- Hidden file input with `capture="environment"`
- Direct camera access via native mobile camera app
- Image processing with `scanFileV2()`
- Loading states and error handling

**Implementation:**
```javascript
// Hidden file input
<input 
    type="file" 
    id="qr-input-file" 
    accept="image/*" 
    capture="environment" 
    hidden
>

// File processing
html5QrCode.scanFileV2(file, true)
    .then(decodedText => {
        onScanSuccess(decodedText, null);
    })
    .catch(err => {
        // Handle scanning errors
    });
```

### 4. Unified Success Handler

Both modes use the same success handler:

```javascript
function onScanSuccess(decodedText, decodedResult) {
    // Prevent multiple triggers
    if (isRedirecting) return;
    isRedirecting = true;

    const scannedCode = decodedText.trim();
    console.log("✅ QR CAPTURED:", scannedCode);

    // Play success sound
    playBeep();
    
    // Show success message
    showSuccess(`QR Code berhasil terbaca: ${scannedCode}`);

    // Redirect to asset page
    const targetUrl = `${baseUrl}?code=${encodeURIComponent(scannedCode)}`;
    setTimeout(() => {
        window.location.href = targetUrl;
    }, 1000);
}
```

## User Experience

### Visual Mode Indicators

The UI clearly shows which mode is active:

**HTTPS Mode:**
- Green indicator with shield icon
- "Mode Kamera Live (HTTPS) - Kamera real-time tersedia"
- Camera icon in placeholder

**HTTP Mode:**
- Amber indicator with image icon
- "Mode Kamera Standar (HTTP) - Scan via foto/gambar"
- Image icon in placeholder

### Button Behaviors

**HTTPS Mode:**
- "Mulai Scan Live" → Starts camera stream
- "Stop Scan" → Stops camera stream

**HTTP Mode:**
- "Ambil Foto QR" → Opens native camera app
- No stop button needed (single-shot operation)

### Error Handling

**HTTPS Errors:**
- Permission denied → Clear instructions
- Camera not found → Device guidance
- Camera in use → Application conflict resolution
- Auto-fallback to HTTP mode if camera fails

**HTTP Errors:**
- No QR code found → Image quality guidance
- Processing failed → Retry instructions
- File format issues → Supported format information

## Mobile Experience

### iOS Safari (HTTP Mode)
- Uses native camera app via `capture="environment"`
- Returns to browser after photo capture
- Automatic QR code detection from image

### Android Chrome (HTTP Mode)
- Direct camera access via file input
- Image capture and immediate processing
- Seamless user experience

### HTTPS Mode (All Platforms)
- Real-time camera preview
- Continuous scanning capability
- Visual feedback with scanning frame

## Technical Benefits

### 1. Universal Compatibility
- Works on all modern browsers
- No HTTPS requirement for basic functionality
- Graceful degradation for older devices

### 2. Performance Optimization
- HTTPS: Optimized FPS (10) for battery life
- HTTP: Single-shot processing reduces resource usage
- Efficient memory management

### 3. Security Considerations
- HTTPS: Full secure context compliance
- HTTP: No camera stream, only file access
- Proper error message sanitization

### 4. Developer Experience
- Single codebase for both environments
- Unified success handling
- Comprehensive error logging
- Easy debugging with console output

## Browser Support Matrix

| Browser | HTTPS Mode | HTTP Mode | Notes |
|---------|-----------|-----------|-------|
| Chrome 67+ | ✅ Full Support | ✅ Full Support | Optimal experience |
| Firefox 62+ | ✅ Full Support | ✅ Full Support | Optimal experience |
| Safari 11+ | ✅ Full Support | ✅ Full Support | iOS camera app integration |
| Edge 79+ | ✅ Full Support | ✅ Full Support | Chromium-based |

## Testing Scenarios

### Development (HTTPS/Localhost)
1. Access via `https://localhost` or `https://127.0.0.1`
2. Verify live camera mode activation
3. Test continuous scanning capability
4. Verify stop/start functionality

### Production (HTTP/IP Address)
1. Access via `http://147.93.81.28`
2. Verify file upload mode activation
3. Test camera capture functionality
4. Verify image processing

### Cross-Platform Testing
1. **Mobile Safari (iOS)**: Test HTTP mode camera integration
2. **Mobile Chrome (Android)**: Test both modes
3. **Desktop browsers**: Test file upload fallback
4. **Tablet devices**: Test responsive behavior

## Troubleshooting

### Common Issues

**Camera not starting in HTTPS mode:**
- Check browser permissions
- Verify secure context
- Look for auto-fallback to HTTP mode

**File upload not working in HTTP mode:**
- Verify `capture="environment"` attribute
- Check mobile browser compatibility
- Test with different image formats

**QR code not detected:**
- Ensure proper lighting conditions
- Verify QR code quality and size
- Check image focus and clarity

### Debug Information

The scanner provides comprehensive console logging:
- Mode detection results
- Camera initialization status
- Scan success/failure details
- Error messages with context

## Future Enhancements

### Potential Improvements

1. **Progressive Web App (PWA) Integration**
   - Add to home screen functionality
   - Offline QR code scanning capability

2. **Advanced Image Processing**
   - Image preprocessing for better detection
   - Multiple QR code support in single image

3. **Enhanced Mobile Experience**
   - Fullscreen camera mode
   - Zoom and focus controls
   - Flashlight integration

4. **Analytics and Monitoring**
   - Scan success rate tracking
   - Performance metrics collection
   - Error pattern analysis

## Conclusion

The Universal QR Scanner provides a robust, user-friendly solution that works seamlessly across all environments while maintaining security best practices. The hybrid approach ensures that users can always access QR scanning functionality regardless of their connection security, while still taking advantage of modern browser capabilities when available.

This implementation demonstrates how to handle browser security restrictions gracefully while providing consistent user experience across different deployment scenarios.