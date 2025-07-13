<?php
http_response_code(500);
$page_title = "Internal Server Error - 500";
$page_description = "Something went wrong on our end.";
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

.suggestions {
    background: var(--background-dark);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 2rem;
    text-align: left;
}

.suggestions h4 {
    color: var(--text-primary);
    margin: 0 0 1rem 0;
    font-size: 1.1rem;
}

.suggestions ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.suggestions li {
    color: var(--text-secondary);
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.suggestions li:last-child {
    border-bottom: none;
}

.suggestions a {
    color: var(--primary-color);
    text-decoration: none;
    transition: color 0.3s ease;
}

.suggestions a:hover {
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
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>
        
        <h1 class="error-code">500</h1>
        <h2 class="error-title">Internal Server Error</h2>
        <p class="error-message">
            Something went wrong on our end. We're working to fix this issue. Please try again in a few moments.
        </p>
        
        <div class="error-actions">
            <a href="/" class="error-btn primary">Go Home</a>
            <button onclick="location.reload()" class="error-btn secondary">Try Again</button>
        </div>
        
        <div class="suggestions">
            <h4>What happened:</h4>
            <ul>
                <li>Our server encountered an unexpected error</li>
                <li>The issue has been automatically reported to our team</li>
                <li>We're working to resolve this as quickly as possible</li>
                <li>You can contact <a href="/contact">support</a> if the problem persists</li>
            </ul>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
