// iOS Performance Debug Script
// Add this to your HTML files to monitor performance on iOS devices

class iOSPerformanceMonitor {
  constructor() {
    this.startTime = performance.now();
    this.loadTimes = {};
    this.errors = [];
    this.isIOS = this.detectIOS();
  }

  detectIOS() {
    return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
  }

  log(message, type = 'info') {
    const timestamp = new Date().toISOString();
    const logEntry = {
      timestamp,
      message,
      type,
      userAgent: navigator.userAgent,
      isIOS: this.isIOS
    };

    console.log(`[iOS Debug] ${message}`, logEntry);
    
    if (type === 'error') {
      this.errors.push(logEntry);
    }
  }

  startTimer(label) {
    this.loadTimes[label] = performance.now();
    this.log(`Started: ${label}`);
  }

  endTimer(label) {
    if (this.loadTimes[label]) {
      const duration = performance.now() - this.loadTimes[label];
      this.log(`Completed: ${label} in ${duration.toFixed(2)}ms`);
      return duration;
    }
    return 0;
  }

  monitorMemory() {
    if ('memory' in performance) {
      const memory = performance.memory;
      this.log(`Memory - Used: ${(memory.usedJSHeapSize / 1048576).toFixed(2)}MB, Total: ${(memory.totalJSHeapSize / 1048576).toFixed(2)}MB`);
    }
  }

  monitorNetwork() {
    if ('connection' in navigator) {
      const connection = navigator.connection;
      this.log(`Network - Type: ${connection.effectiveType}, Speed: ${connection.downlink}Mbps`);
    }
  }

  checkWebGLSupport() {
    const canvas = document.createElement('canvas');
    const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
    
    if (gl) {
      const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
      if (debugInfo) {
        const renderer = gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL);
        this.log(`WebGL Renderer: ${renderer}`);
      }
    } else {
      this.log('WebGL not supported', 'error');
    }
  }

  checkCameraSupport() {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      this.log('Modern getUserMedia API supported');
    } else if (navigator.getUserMedia) {
      this.log('Legacy getUserMedia API supported');
    } else {
      this.log('No getUserMedia support', 'error');
    }
  }

  getSummary() {
    const summary = {
      isIOS: this.isIOS,
      userAgent: navigator.userAgent,
      errors: this.errors.length,
      loadTimes: this.loadTimes,
      timestamp: new Date().toISOString()
    };

    this.log('Performance Summary:', summary);
    return summary;
  }
}

// Initialize the monitor
const iosMonitor = new iOSPerformanceMonitor();

// Log initial device info
iosMonitor.log('Device detected');
iosMonitor.checkWebGLSupport();
iosMonitor.checkCameraSupport();
iosMonitor.monitorNetwork();

// Monitor page load performance
window.addEventListener('load', () => {
  iosMonitor.endTimer('pageLoad');
  iosMonitor.monitorMemory();
  
  // Log summary after 5 seconds
  setTimeout(() => {
    iosMonitor.getSummary();
  }, 5000);
});

// Start page load timer
iosMonitor.startTimer('pageLoad');

// Export for use in other scripts
window.iOSMonitor = iosMonitor;
