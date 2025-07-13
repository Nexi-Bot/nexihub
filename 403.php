<?php
http_response_code(403);
$page_title = "Access Forbidden - 403";
$page_description = "You don't have permission to access this resource.";
include 'includes/header.php';
?>

<style>
:root {
    --primary-color: #e64f21;
    --secondary-color: #d63917;
    --background-dark: #0a0a0a;
    --background-light: #1a1a1a;
    --text-primary: #ffffff;
    --text-secondary: #b0b0b0;
    --border-color: #333333;
}

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
                <path d="M18 6L6 18"/>
                <path d="M6 6l12 12"/>
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            </svg>
        </div>
        
        <h1 class="error-code">403</h1>
        <h2 class="error-title">Access Forbidden</h2>
        <p class="error-message">
            You don't have permission to access this resource. This could be due to insufficient privileges or expired authentication.
        </p>
        
        <div class="error-actions">
            <a href="/" class="error-btn primary">Go Home</a>
            <a href="/staff/login" class="error-btn secondary">Staff Login</a>
        </div>
        
        <div class="suggestions">
            <h4>What you can do:</h4>
            <ul>
                <li>Make sure you're logged in with the correct account</li>
                <li>Check if you have the necessary permissions</li>
                <li>Contact your administrator if you believe this is an error</li>
                <li>Try logging out and logging back in</li>
            </ul>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
