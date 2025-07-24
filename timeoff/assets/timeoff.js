// Time Off Portal JavaScript

function openRequestModal() {
    document.getElementById('requestModal').style.display = 'block';
    // Reset form
    document.getElementById('timeoffForm').reset();
    document.getElementById('balanceWarning').style.display = 'none';
    // Set minimum dates to today
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="date_from"]').setAttribute('min', today);
    document.querySelector('input[name="date_to"]').setAttribute('min', today);
}

function closeRequestModal() {
    document.getElementById('requestModal').style.display = 'none';
}

function calculateDays() {
    const fromDate = document.querySelector('input[name="date_from"]').value;
    const toDate = document.querySelector('input[name="date_to"]').value;
    const daysInput = document.querySelector('input[name="days_requested"]');
    const balanceWarning = document.getElementById('balanceWarning');
    
    if (fromDate && toDate) {
        const from = new Date(fromDate);
        const to = new Date(toDate);
        
        if (to < from) {
            alert('End date cannot be before start date');
            document.querySelector('input[name="date_to"]').value = '';
            return;
        }
        
        // Calculate business days (excluding weekends)
        let days = 0;
        let currentDate = new Date(from);
        
        while (currentDate <= to) {
            const dayOfWeek = currentDate.getDay();
            if (dayOfWeek !== 0 && dayOfWeek !== 6) { // Not Sunday (0) or Saturday (6)
                days++;
            }
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        daysInput.value = days;
        
        // Check if exceeds remaining balance
        const remainingDays = parseInt(document.querySelector('.balance-card:nth-child(3) h3').textContent);
        if (days > remainingDays) {
            balanceWarning.style.display = 'flex';
        } else {
            balanceWarning.style.display = 'none';
        }
        
        // Update to date minimum
        document.querySelector('input[name="date_to"]').setAttribute('min', fromDate);
    }
}

function submitRequest(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Validate dates
    if (!data.date_from || !data.date_to) {
        alert('Please select both start and end dates');
        return;
    }
    
    if (new Date(data.date_to) < new Date(data.date_from)) {
        alert('End date cannot be before start date');
        return;
    }
    
    if (!data.days_requested || data.days_requested <= 0) {
        alert('Please select valid dates');
        return;
    }
    
    // Show loading state
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    submitBtn.disabled = true;
    
    // Submit the request
    fetch('/timeoff/process-request.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Time off request submitted successfully! You will receive email confirmation shortly.');
            closeRequestModal();
            location.reload(); // Refresh to show new request
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error. Please try again.');
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function cancelRequest(requestId) {
    if (!confirm('Are you sure you want to cancel this time off request?')) {
        return;
    }
    
    fetch('/timeoff/process-request.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'cancel',
            request_id: requestId
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Request cancelled successfully');
            location.reload();
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error. Please try again.');
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('requestModal');
    if (event.target === modal) {
        closeRequestModal();
    }
}

// Auto-calculate days when dates change
document.addEventListener('DOMContentLoaded', function() {
    const fromInput = document.querySelector('input[name="date_from"]');
    const toInput = document.querySelector('input[name="date_to"]');
    
    if (fromInput) fromInput.addEventListener('change', calculateDays);
    if (toInput) toInput.addEventListener('change', calculateDays);
});
