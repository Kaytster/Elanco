/**
 * Notification system for Elanco Pet Health Dashboard
 * Provides toast notifications using either SweetAlert2 or custom HTML
 */

// Global notification function
window.showNotification = function(title, message, type, duration) {
    // Default values
    type = type || 'info';
    duration = duration || 5000;
    
    // Choose between SweetAlert2 and custom notification
    const useToast = true; // Set to false to use custom notifications
    
    if (useToast) {
        // Using SweetAlert2 toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: duration,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        
        Toast.fire({
            icon: type === 'error' ? 'error' : type === 'warning' ? 'warning' : type === 'success' ? 'success' : 'info',
            title: title,
            text: message
        });
    } else {
        // Check if notification container exists, create if not
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            document.body.appendChild(container);
        }
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        // Get icon based on type
        let iconClass = '';
        switch(type) {
            case 'success': iconClass = '✅'; break;
            case 'warning': iconClass = '⚠️'; break;
            case 'error': iconClass = '❌'; break;
            default: iconClass = 'ℹ️'; break;
        }
        
        // Create notification content
        notification.innerHTML = `
            <div class="notification-icon">${iconClass}</div>
            <div class="notification-content">
                <div class="notification-title">${title}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close">&times;</button>
            <div class="notification-progress"></div>
        `;
        
        // Add to DOM
        container.appendChild(notification);
        
        // Show animation
        setTimeout(() => notification.classList.add('show'), 10);
        
        // Close button event
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        });
        
        // Auto close after duration
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }
};

// Function to check vital signs and show notifications if needed
function checkVitalSigns(heartRate, temperature, steps, waterIntake) {
    // Set thresholds for alerts
    const highHeartRate = 140;
    const lowHeartRate = 60;
    const highTemp = 39.5;
    const lowTemp = 37.0;
    const lowSteps = 2000;
    const lowWater = 500;
    
    let alerts = [];
    
    // Check heart rate
    if (heartRate > highHeartRate) {
        alerts.push({
            title: 'Heart Rate Alert',
            message: 'Heart rate is high at ' + heartRate + ' bpm', 
            type: 'warning'
        });
    } else if (heartRate < lowHeartRate && heartRate > 0) {
        alerts.push({
            title: 'Heart Rate Alert',
            message: 'Heart rate is low at ' + heartRate + ' bpm', 
            type: 'warning'
        });
    }
    
    // Check temperature
    if (temperature > highTemp) {
        alerts.push({
            title: 'Temperature Alert',
            message: 'Temperature is high at ' + temperature + '°C', 
            type: 'warning'
        });
    } else if (temperature < lowTemp && temperature > 0) {
        alerts.push({
            title: 'Temperature Alert',
            message: 'Temperature is low at ' + temperature + '°C', 
            type: 'warning'
        });
    }
    
    // Check activity level
    if (steps < lowSteps && steps > 0) {
        alerts.push({
            title: 'Activity Alert',
            message: 'Activity level is low at ' + steps + ' steps', 
            type: 'info'
        });
    }
    
    // Check water intake
    if (waterIntake < lowWater && waterIntake > 0) {
        alerts.push({
            title: 'Hydration Alert',
            message: 'Water intake is low at ' + waterIntake + ' ml', 
            type: 'info'
        });
    }
    
    // Show notifications with delay between them
    alerts.forEach((alert, index) => {
        setTimeout(() => {
            showNotification(alert.title, alert.message, alert.type);
        }, index * 1000);
    });
    
    return alerts.length > 0;
} 