// NEXI BOT LTD - Cookie & Legal Consent Popup
class ConsentPopup {
    constructor() {
        this.consentKey = 'nexihub_consent_accepted';
        this.sessionKey = 'nexihub_session_consent';
        this.init();
    }
    init() {
        console.log('ConsentPopup: Initializing...');
        console.log('ConsentPopup: Session consent:', this.hasSessionConsent());
        console.log('ConsentPopup: Permanent consent:', this.hasPermanentConsent());
        if (!this.hasSessionConsent()) {
            console.log('ConsentPopup: No session consent found, creating popup...');
            this.createPopup();
            this.showPopup();
        } else {
            console.log('ConsentPopup: Session consent already exists, skipping popup');
        }
    }
    hasSessionConsent() {
        return sessionStorage.getItem(this.sessionKey) === 'true';
    }
    hasPermanentConsent() {
        return localStorage.getItem(this.consentKey) === 'true';
    }
    createPopup() {
        const popup = document.createElement('div');
        popup.id = 'consent-popup';
        popup.className = 'consent-popup-overlay';
        popup.innerHTML = `
            <div class="consent-popup-container">
                <div class="consent-popup-header">
                    <div class="consent-logo">
                        <img src="/nexi.png" alt="NEXI BOT LTD" class="consent-logo-img">
                        <h2 class="consent-title">NEXI BOT LTD</h2>
                    </div>
                    <div class="consent-badge">
                        <span class="consent-badge-text">Legal Compliance</span>
                    </div>
                </div>
                <div class="consent-popup-content">
                    <h3 class="consent-heading">🍪 Cookies & Legal Consent</h3>
                    <p class="consent-text">
                        We use <strong>essential cookies</strong> and tracking technologies to enhance your experience and analyze our services. 
                        By continuing to use our website, you acknowledge and agree to our comprehensive legal framework.
                    </p>
                    <div class="consent-legal-info">
                        <div class="consent-info-grid">
                            <div class="consent-info-item">
                                <div class="consent-icon">🔒</div>
                                <div class="consent-info-content">
                                    <h4>Privacy & Data Protection</h4>
                                    <p>Your data is protected under UK GDPR and our comprehensive privacy policies</p>
                                </div>
                            </div>
                            <div class="consent-info-item">
                                <div class="consent-icon">📋</div>
                                <div class="consent-info-content">
                                    <h4>Terms of Service</h4>
                                    <p>Our terms govern your use of all NEXI BOT services and platforms</p>
                                </div>
                            </div>
                            <div class="consent-info-item">
                                <div class="consent-icon">⚖️</div>
                                <div class="consent-info-content">
                                    <h4>Legal Compliance</h4>
                                    <p>All services comply with UK law and international standards</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="consent-legal-notice">
                        <p><strong>By using our services, you accept:</strong></p>
                        <ul class="consent-legal-list">
                            <li>Our <a href="/legal" target="_blank" class="consent-link">Terms of Service</a></li>
                            <li>Our <a href="/legal" target="_blank" class="consent-link">Privacy Policy</a></li>
                            <li>Our <a href="/legal" target="_blank" class="consent-link">Cookie Policy</a></li>
                            <li>All applicable <a href="/legal" target="_blank" class="consent-link">Legal Documents</a></li>
                        </ul>
                    </div>
                </div>
                <div class="consent-popup-actions">
                    <button id="consent-accept" class="consent-btn consent-btn-accept">
                        <span class="consent-btn-icon">✓</span>
                        Accept All & Continue
                    </button>
                    <button id="consent-session" class="consent-btn consent-btn-session">
                        <span class="consent-btn-icon">⏱️</span>
                        Accept for This Session
                    </button>
                    <a href="/legal" class="consent-btn consent-btn-learn-more" target="_blank">
                        <span class="consent-btn-icon">📖</span>
                        Learn More
                    </a>
                </div>
                <div class="consent-popup-footer">
                    <p class="consent-company-info">
                        NEXI BOT LTD | Company Registration: 16502958 | ICO: ZB910034
                    </p>
                </div>
            </div>
        `;
        document.body.appendChild(popup);
        this.attachEventListeners();
    }
    attachEventListeners() {
        const acceptBtn = document.getElementById('consent-accept');
        const sessionBtn = document.getElementById('consent-session');
        const popup = document.getElementById('consent-popup');
        acceptBtn.addEventListener('click', () => this.acceptPermanent());
        sessionBtn.addEventListener('click', () => this.acceptSession());
        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                this.acceptSession();
            }
        });
        document.body.style.overflow = 'hidden';
    }
    acceptPermanent() {
        localStorage.setItem(this.consentKey, 'true');
        sessionStorage.setItem(this.sessionKey, 'true');
        this.hidePopup();
        this.trackConsent('permanent');
    }
    acceptSession() {
        sessionStorage.setItem(this.sessionKey, 'true');
        this.hidePopup();
        this.trackConsent('session');
    }
    showPopup() {
        const popup = document.getElementById('consent-popup');
        if (popup) {
            setTimeout(() => {
                popup.classList.add('consent-popup-show');
            }, 500);
        }
    }
    hidePopup() {
        const popup = document.getElementById('consent-popup');
        if (popup) {
            popup.classList.add('consent-popup-hide');
            document.body.style.overflow = '';
            setTimeout(() => {
                popup.remove();
            }, 300);
        }
    }
    trackConsent(type) {
        if (typeof gtag !== 'undefined') {
            gtag('event', 'consent_given', {
                'consent_type': type,
                'timestamp': new Date().toISOString()
            });
        }
        fetch('/api/track-consent', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                type: type,
                timestamp: new Date().toISOString(),
                userAgent: navigator.userAgent,
                url: window.location.href
            })
        }).catch(err => {});
    }
}
document.addEventListener('DOMContentLoaded', () => { 
    console.log('ConsentPopup: DOM loaded, creating ConsentPopup instance...');
    new ConsentPopup(); 
});
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        console.log('ConsentPopup: DOM still loading, waiting for DOMContentLoaded...');
        new ConsentPopup();
    });
} else {
    console.log('ConsentPopup: DOM already loaded, creating ConsentPopup instance immediately...');
    new ConsentPopup();
}
