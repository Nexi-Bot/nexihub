<?php
http_response_code(503);
$page_title = "Service Unavailable - 503";
$page_description = "Service temporarily unavailable.";
include 'includes/header.php';
?>

<style>
.error-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--background-dark) 0%, var(--background-light) 100%);
    padding: 2rem;
}

.error-content {
    text-align: center;
    max-width: 600px;
    background: var(--background-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    padding: 3rem;
    position: relative;
    overflow: hidden;
}

.error-content::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.error-code {
    font-size: 8rem;
    font-weight: 900;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
    line-height: 1;
}

.error-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 1rem 0;
}

.error-message {
    font-size: 1.2rem;
    color: var(--text-secondary);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.error-btn {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}

.error-btn.primary {
    background: var(--primary-color);
    color: white;
}

.error-btn.primary:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(230, 79, 33, 0.3);
}

.error-btn.secondary {
    background: var(--background-dark);
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
}

.error-btn.secondary:hover {
    color: var(--text-primary);
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

.error-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 2rem;
    opacity: 0.3;
}

.status-info {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 2rem;
    text-align: left;
}

.status-info h4 {
    color: var(--text-primary);
    margin: 0 0 1rem 0;
    font-size: 1.1rem;
}

.status-info p {
    color: var(--text-secondary);
    margin: 0.5rem 0;
}

.status-info a {
    color: var(--primary-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.status-info a:hover {
    color: var(--secondary-color);
}

@media (max-width: 768px) {
    .error-code {
        font-size: 5rem;
    }
    
    .error-title {
        font-size: 2rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .error-content {
        padding: 2rem;
    }
}
</style>

<div class="error-container">
    <div class="error-content">
        <div class="error-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 100%; height: 100%; color: var(--primary-color);">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        
        <h1 class="error-code">503</h1>
        <h2 class="error-title">Service Unavailable</h2>
        <p class="error-message">
            We're currently performing scheduled maintenance to improve your experience. We'll be back shortly.
        </p>
        
        <div class="error-actions">
            <button onclick="location.reload()" class="error-btn primary">Try Again</button>
            <a href="/" class="error-btn secondary">Go Home</a>
        </div>
        
        <div class="status-info">
            <h4>Maintenance Information:</h4>
            <p><strong>Expected Duration:</strong> 30-60 minutes</p>
            <p><strong>Affected Services:</strong> All platform features</p>
            <p><strong>Status Updates:</strong> Follow us on social media for real-time updates</p>
            <p>For urgent matters, please contact <a href="/contact">support</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
