<?php
require_once 'config/config.php';

// Company Policies & Procedures content with professional HTML/CSS formatting
$companyPolicies = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Policies & Procedures Agreement - NEXI BOT LTD</title>
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: "Times New Roman", Times, serif;
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 30px; 
            line-height: 1.7;
            background: #ffffff;
            color: #1a1a1a;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 4px solid #2c3e50;
        }
        .header h1 {
            font-size: 2.8em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 1.9em;
            font-weight: normal;
            color: #34495e;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }
        .company-details {
            font-size: 1.1em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .section-divider {
            border-top: 3px solid #2c3e50;
            margin: 35px 0;
        }
        .section-title {
            font-size: 1.5em;
            font-weight: bold;
            color: #2c3e50;
            margin: 30px 0 20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 6px solid #2c3e50;
        }
        .subsection-title {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c3e50;
            margin: 25px 0 15px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #bdc3c7;
        }
        .sub-subsection-title {
            font-size: 1.1em;
            font-weight: bold;
            color: #34495e;
            margin: 20px 0 10px 0;
        }
        .content-block {
            margin: 15px 0;
            padding: 0 10px;
        }
        .policy-box {
            background: #f8f9fa;
            padding: 20px;
            border: 2px solid #2c3e50;
            margin: 20px 0;
            border-radius: 8px;
        }
        .authority-notice {
            background: #fff3cd;
            border: 3px solid #ffc107;
            padding: 20px;
            margin: 25px 0;
            font-weight: bold;
            border-radius: 8px;
        }
        .mandatory-warning {
            background: #f8d7da;
            border: 3px solid #dc3545;
            padding: 25px;
            margin: 30px 0;
            font-weight: bold;
            text-align: center;
            font-size: 1.1em;
            border-radius: 8px;
        }
        .compliance-box {
            background: #d1ecf1;
            border: 3px solid #0c5460;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .procedure-box {
            background: #e2e3e5;
            border: 2px solid #6c757d;
            padding: 18px;
            margin: 20px 0;
            border-radius: 6px;
        }
        ul, ol {
            padding-left: 25px;
            margin: 12px 0;
        }
        li {
            margin-bottom: 8px;
            line-height: 1.6;
        }
        .signature-section {
            margin-top: 50px;
            border-top: 4px solid #2c3e50;
            padding-top: 30px;
        }
        .digital-signing {
            background: #e8f4f8;
            border: 3px solid #17a2b8;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
            border-radius: 8px;
        }
        .digital-signing h3 {
            color: #17a2b8;
            font-size: 1.4em;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .acknowledgment-section {
            background: #f0f8ff;
            border: 2px solid #007bff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .acknowledgment-title {
            color: #007bff;
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .checkbox-item {
            margin: 12px 0;
            padding: 8px;
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .checkbox {
            margin-right: 10px;
            transform: scale(1.2);
        }
        .cta-button {
            background: #17a2b8;
            color: white;
            padding: 12px 30px;
            font-size: 1.1em;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .document-control {
            margin-top: 40px;
            border-top: 2px solid #2c3e50;
            padding-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }
        .legal-emphasis {
            font-weight: bold;
            text-transform: uppercase;
            color: #dc3545;
        }
        .policy-category {
            margin: 20px 0;
            padding-left: 15px;
        }
        .category-header {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.05em;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .division-specific {
            background: #f1f3f4;
            border-left: 5px solid #28a745;
            padding: 15px;
            margin: 15px 0;
        }
        .enforcement-section {
            background: #fff5f5;
            border: 2px solid #e53e3e;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 13px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: bold;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }
        .signature-block {
            text-align: center;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .signature-line {
            border-bottom: 2px solid #333;
            margin: 15px 0 8px 0;
            height: 30px;
        }
        @media (max-width: 768px) {
            body { padding: 15px; font-size: 13px; }
            .header h1 { font-size: 2.2em; }
            .signature-grid { grid-template-columns: 1fr; gap: 15px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>NEXI BOT LTD</h1>
        <h2>COMPREHENSIVE POLICIES & PROCEDURES AGREEMENT</h2>
        <div class="company-details">
            <strong>Company Registration Number:</strong> 16502958<br>
            <strong>ICO Registration Number:</strong> ZB910034
        </div>
    </div>

    <div class="section-divider"></div>

    <div class="mandatory-warning">
        <span class="legal-emphasis">MANDATORY COMPLIANCE NOTICE</span><br>
        This Policies & Procedures Agreement establishes comprehensive operational standards for <span class="legal-emphasis">ALL INDIVIDUALS</span> associated with Nexi Bot LTD in any capacity. <span class="legal-emphasis">COMPLIANCE WITH ALL POLICIES AND PROCEDURES IS MANDATORY AND NON-NEGOTIABLE.</span>
    </div>

    <div class="section-title">1. POLICY FRAMEWORK AND AUTHORITY</div>

    <div class="subsection-title">1.1 Policy Hierarchy and Authority</div>
    <div class="authority-notice">
        <p><strong>Company Directors have <span class="legal-emphasis">ABSOLUTE AUTHORITY</span> to:</strong></p>
        <ul>
            <li><span class="legal-emphasis">CREATE, MODIFY, OR ELIMINATE</span> any policy or procedure at any time</li>
            <li><span class="legal-emphasis">INTERPRET ALL POLICIES</span> with final and binding authority</li>
            <li><span class="legal-emphasis">GRANT OR DENY EXCEPTIONS</span> to any policy at their sole discretion</li>
            <li><span class="legal-emphasis">IMPLEMENT IMMEDIATE CHANGES</span> without prior consultation or notice</li>
            <li><span class="legal-emphasis">OVERRIDE ANY PROVISION</span> when deemed necessary for Company interests</li>
            <li><span class="legal-emphasis">DELEGATE POLICY ENFORCEMENT</span> to designated personnel with full authority</li>
        </ul>
    </div>

    <div class="subsection-title">1.2 Policy Compliance - MANDATORY AND UNIVERSAL</div>
    <div class="compliance-box">
        <p><strong><span class="legal-emphasis">ALL INDIVIDUALS</span> must:</strong></p>
        <ul>
            <li><span class="legal-emphasis">COMPLY FULLY</span> with all policies and procedures without exception</li>
            <li><span class="legal-emphasis">STAY CURRENT</span> with all policy updates and modifications</li>
            <li><span class="legal-emphasis">IMPLEMENT CHANGES IMMEDIATELY</span> upon notification</li>
            <li><span class="legal-emphasis">ACCEPT ALL MODIFICATIONS</span> without right to challenge or negotiate</li>
            <li><span class="legal-emphasis">UNDERSTAND THAT IGNORANCE</span> of policies is not an acceptable excuse</li>
            <li><span class="legal-emphasis">ACKNOWLEDGE THAT POLICIES SUPERSEDE</span> all other agreements or understandings</li>
        </ul>
    </div>

    <div class="subsection-title">1.3 Policy Updates and Communication</div>
    <div class="content-block">
        <ul>
            <li><span class="legal-emphasis">POLICIES MAY BE UPDATED</span> at any time without prior notice</li>
            <li><span class="legal-emphasis">NOTIFICATION OF CHANGES</span> constitutes immediate binding implementation</li>
            <li><span class="legal-emphasis">CONTINUED PARTICIPATION</span> constitutes acceptance of all policy changes</li>
            <li><span class="legal-emphasis">NO INDIVIDUAL CONSENT</span> is required for policy modifications</li>
            <li><span class="legal-emphasis">ALL UPDATES ARE RETROACTIVELY EFFECTIVE</span> from the date of notification</li>
            <li><span class="legal-emphasis">POLICIES ARE AVAILABLE</span> through designated Company channels at all times</li>
        </ul>
    </div>

    <div class="section-divider"></div>

    <div class="section-title">2. OPERATIONAL POLICIES</div>

    <div class="subsection-title">2.1 Digital Systems and Technology Usage</div>

    <div class="sub-subsection-title">Authorized Access and Usage:</div>
    <div class="policy-box">
        <ul>
            <li><span class="legal-emphasis">ACCESS IS GRANTED</span> at the Company\'s sole discretion and may be revoked immediately</li>
            <li><span class="legal-emphasis">ALL SYSTEM USAGE</span> is monitored and logged for security and compliance purposes</li>
            <li><span class="legal-emphasis">PERSONAL USE</span> of Company systems is strictly prohibited</li>
            <li><span class="legal-emphasis">UNAUTHORIZED ACCESS</span> to systems or data is strictly forbidden</li>
            <li><span class="legal-emphasis">SYSTEM MODIFICATIONS</span> without explicit approval are prohibited</li>
            <li><span class="legal-emphasis">ALL DIGITAL ACTIVITIES</span> must comply with Company security protocols</li>
        </ul>
    </div>

    <div class="sub-subsection-title">Security Requirements - NON-NEGOTIABLE:</div>
    <div class="compliance-box">
        <ul>
            <li><span class="legal-emphasis">STRONG PASSWORDS</span> must be used for all Company systems (minimum 12 characters, mixed case, numbers, symbols)</li>
            <li><span class="legal-emphasis">TWO-FACTOR AUTHENTICATION</span> must be enabled on all accounts without exception</li>
            <li><span class="legal-emphasis">IMMEDIATE LOGOUT</span> required when stepping away from systems</li>
            <li><span class="legal-emphasis">SECURE CONNECTIONS</span> (VPN) required for all remote access to Company systems</li>
            <li><span class="legal-emphasis">NO SHARING</span> of login credentials under any circumstances</li>
            <li><span class="legal-emphasis">IMMEDIATE REPORTING</span> of suspected security breaches (within 15 minutes)</li>
            <li><span class="legal-emphasis">REGULAR PASSWORD UPDATES</span> as required by Company security policies</li>
        </ul>
    </div>

    <div class="sub-subsection-title">Prohibited Activities - ZERO TOLERANCE:</div>
    <div class="enforcement-section">
        <ul>
            <li><span class="legal-emphasis">NO INSTALLATION</span> of unauthorized software on Company systems</li>
            <li><span class="legal-emphasis">NO DOWNLOADING</span> of unauthorized content or materials</li>
            <li><span class="legal-emphasis">NO ACCESSING</span> inappropriate websites or content during work time</li>
            <li><span class="legal-emphasis">NO BYPASSING</span> security measures or access controls</li>
            <li><span class="legal-emphasis">NO USING</span> Company systems for competing business activities</li>
            <li><span class="legal-emphasis">NO STORING</span> personal files on Company systems or cloud storage</li>
        </ul>
    </div>

    <div class="subsection-title">2.2 Communication and Correspondence Policies</div>

    <div class="sub-subsection-title">Digital Communication Standards:</div>
    <div class="policy-box">
        <ul>
            <li><span class="legal-emphasis">ALL BUSINESS COMMUNICATIONS</span> must maintain professional standards</li>
            <li><span class="legal-emphasis">COMPANY EMAIL SIGNATURES</span> are mandatory for all external communications</li>
            <li><strong>RESPONSE TIME STANDARDS:</strong></li>
            <ul>
                <li>Customer inquiries: Maximum 2 hours during business hours</li>
                <li>Internal communications: Maximum 4 hours during business hours</li>
                <li>Urgent matters: Maximum 30 minutes at any time</li>
            </ul>
            <li><span class="legal-emphasis">PROPER CHANNELS</span> must be used for different types of communication</li>
            <li><span class="legal-emphasis">CONFIDENTIAL INFORMATION</span> must never be discussed in unsecured channels</li>
        </ul>
    </div>

    <div class="subsection-title">2.3 Data Management and Protection Policies</div>

    <div class="sub-subsection-title">Data Classification and Handling:</div>
    <div class="compliance-box">
        <ul>
            <li><span class="legal-emphasis">ALL COMPANY DATA</span> is classified as confidential unless explicitly stated otherwise</li>
            <li><span class="legal-emphasis">CUSTOMER DATA</span> receives the highest level of protection and security</li>
            <li><span class="legal-emphasis">PERSONAL DATA</span> must be handled in compliance with UK GDPR and all applicable laws</li>
            <li><span class="legal-emphasis">FINANCIAL DATA</span> requires enhanced security measures and restricted access</li>
            <li><span class="legal-emphasis">TECHNICAL DATA</span> including code and configurations must be protected from unauthorized access</li>
        </ul>
    </div>

    <div class="sub-subsection-title">Data Breach Response - IMMEDIATE ACTION REQUIRED:</div>
    <div class="enforcement-section">
        <ul>
            <li><span class="legal-emphasis">IMMEDIATE CONTAINMENT</span> of any suspected or actual data breach</li>
            <li><span class="legal-emphasis">15-MINUTE NOTIFICATION</span> to security@nexibot.uk and data@nexibot.uk</li>
            <li><span class="legal-emphasis">PRESERVATION OF EVIDENCE</span> and detailed incident logging</li>
            <li><span class="legal-emphasis">COOPERATION WITH INVESTIGATION</span> and remediation efforts</li>
            <li><span class="legal-emphasis">IMPLEMENTATION OF CORRECTIVE MEASURES</span> as directed by the Company</li>
            <li><span class="legal-emphasis">NO EXTERNAL COMMUNICATION</span> about breaches without Company authorization</li>
        </ul>
    </div>

    <div class="section-divider"></div>

    <div class="section-title">3. BUSINESS DIVISION SPECIFIC POLICIES</div>

    <div class="subsection-title">3.1 Nexi Hub (Parent Company) Policies</div>
    <div class="division-specific">
        <div class="sub-subsection-title">Corporate Governance Standards:</div>
        <ul>
            <li><span class="legal-emphasis">EXECUTIVE-LEVEL PROFESSIONALISM</span> required in all interactions</li>
            <li><span class="legal-emphasis">STRATEGIC CONFIDENTIALITY</span> for all corporate planning and decision-making</li>
            <li><span class="legal-emphasis">BOARD-LEVEL DISCRETION</span> maintained in all communications</li>
            <li><span class="legal-emphasis">CORPORATE REPUTATION</span> protection is paramount in all activities</li>
            <li><span class="legal-emphasis">INTER-DIVISIONAL COORDINATION</span> must follow established protocols</li>
        </ul>
    </div>

    <div class="subsection-title">3.2 Nexi Bot (Discord Platform) Policies</div>
    <div class="division-specific">
        <div class="sub-subsection-title">Discord Community Management:</div>
        <ul>
            <li><span class="legal-emphasis">COMMUNITY GUIDELINES</span> compliance is mandatory for all interactions</li>
            <li><span class="legal-emphasis">DISCORD TERMS OF SERVICE</span> must be followed without exception</li>
            <li><span class="legal-emphasis">SERVER MODERATION</span> activities must be professional and fair</li>
            <li><span class="legal-emphasis">USER SUPPORT</span> must be provided promptly and professionally</li>
            <li><span class="legal-emphasis">BOT FUNCTIONALITY</span> must be maintained to highest technical standards</li>
        </ul>

        <div class="sub-subsection-title">Customer Support Excellence:</div>
        <ul>
            <li><span class="legal-emphasis">24/7 AVAILABILITY</span> during service hours for critical issues</li>
            <li><span class="legal-emphasis">ESCALATION PROCEDURES</span> for complex technical problems</li>
            <li><span class="legal-emphasis">CUSTOMER SATISFACTION</span> metrics must be maintained above 95%</li>
            <li><span class="legal-emphasis">RESPONSE TIME STANDARDS</span> must be met for all support inquiries</li>
            <li><span class="legal-emphasis">TECHNICAL DOCUMENTATION</span> must be kept current and accessible</li>
        </ul>
    </div>

    <div class="subsection-title">3.3 Nexi Web (Website Platform) Policies</div>
    <div class="division-specific">
        <div class="sub-subsection-title">Web Development Standards:</div>
        <ul>
            <li><span class="legal-emphasis">CODING STANDARDS</span> must comply with industry best practices and Company guidelines</li>
            <li><span class="legal-emphasis">RESPONSIVE DESIGN</span> is mandatory for all web projects</li>
            <li><span class="legal-emphasis">ACCESSIBILITY COMPLIANCE</span> (WCAG 2.1 AA minimum) required for all websites</li>
            <li><span class="legal-emphasis">SECURITY PROTOCOLS</span> must be implemented for all web applications</li>
            <li><span class="legal-emphasis">PERFORMANCE OPTIMIZATION</span> is required for all deliverables</li>
        </ul>

        <div class="sub-subsection-title">Client Project Management:</div>
        <ul>
            <li><span class="legal-emphasis">PROJECT TIMELINES</span> must be realistic and consistently met</li>
            <li><span class="legal-emphasis">CLIENT COMMUNICATION</span> must be professional and regular throughout projects</li>
            <li><span class="legal-emphasis">SCOPE MANAGEMENT</span> procedures must be followed to prevent scope creep</li>
            <li><span class="legal-emphasis">QUALITY ASSURANCE</span> testing is mandatory before client delivery</li>
            <li><span class="legal-emphasis">POST-LAUNCH SUPPORT</span> must be provided as per service agreements</li>
        </ul>
    </div>

    <div class="subsection-title">3.4 Nexi Pulse (Business Platform) Policies</div>
    <div class="division-specific">
        <div class="sub-subsection-title">Business Analytics and Reporting:</div>
        <ul>
            <li><span class="legal-emphasis">DATA ACCURACY</span> is paramount in all analytics and reporting functions</li>
            <li><span class="legal-emphasis">CLIENT CONFIDENTIALITY</span> must be maintained for all business data</li>
            <li><span class="legal-emphasis">REPORTING STANDARDS</span> must meet professional business intelligence requirements</li>
            <li><span class="legal-emphasis">DASHBOARD FUNCTIONALITY</span> must be intuitive and user-friendly</li>
            <li><span class="legal-emphasis">PERFORMANCE METRICS</span> must be meaningful and actionable for clients</li>
        </ul>

        <div class="sub-subsection-title">HR and Business Process Management:</div>
        <ul>
            <li><span class="legal-emphasis">COMPLIANCE WITH EMPLOYMENT LAW</span> in all HR-related functions</li>
            <li><span class="legal-emphasis">PROCESS DOCUMENTATION</span> must be comprehensive and current</li>
            <li><span class="legal-emphasis">WORKFLOW OPTIMIZATION</span> should continuously improve business efficiency</li>
            <li><span class="legal-emphasis">AUTOMATION PROTOCOLS</span> must be reliable and well-tested</li>
            <li><span class="legal-emphasis">CHANGE MANAGEMENT</span> procedures must be followed for all process modifications</li>
        </ul>
    </div>

    <div class="section-divider"></div>

    <div class="section-title">4. HUMAN RESOURCES POLICIES</div>

    <div class="subsection-title">4.1 Recruitment and Selection</div>
    <div class="procedure-box">
        <div class="sub-subsection-title">Recruitment Standards:</div>
        <ul>
            <li><span class="legal-emphasis">ALL POSITIONS</span> must be filled based on merit and Company needs</li>
            <li><span class="legal-emphasis">EQUAL OPPORTUNITY</span> principles apply to all recruitment activities</li>
            <li><span class="legal-emphasis">BACKGROUND CHECKS</span> are mandatory for all positions with system access</li>
            <li><span class="legal-emphasis">REFERENCE VERIFICATION</span> is required for all appointments</li>
            <li><span class="legal-emphasis">PROBATIONARY PERIODS</span> apply to all new appointments as determined by the Company</li>
        </ul>
    </div>

    <div class="subsection-title">4.2 Performance Management</div>
    <div class="policy-box">
        <div class="sub-subsection-title">Performance Standards:</div>
        <ul>
            <li><span class="legal-emphasis">INDIVIDUAL OBJECTIVES</span> must align with Company goals and priorities</li>
            <li><span class="legal-emphasis">REGULAR PERFORMANCE REVIEWS</span> will be conducted as determined by the Company</li>
            <li><span class="legal-emphasis">CONTINUOUS IMPROVEMENT</span> is expected from all personnel</li>
            <li><span class="legal-emphasis">PROFESSIONAL DEVELOPMENT</span> opportunities may be provided at Company discretion</li>
            <li><span class="legal-emphasis">PERFORMANCE ISSUES</span> will be addressed promptly and professionally</li>
        </ul>
    </div>

    <div class="subsection-title">4.3 Discipline and Corrective Action</div>
    <div class="enforcement-section">
        <div class="sub-subsection-title">Disciplinary Framework:</div>
        <ul>
            <li><span class="legal-emphasis">PROGRESSIVE DISCIPLINE</span> may be applied at Company discretion</li>
            <li><span class="legal-emphasis">IMMEDIATE TERMINATION</span> may be imposed for serious violations</li>
            <li><span class="legal-emphasis">CORRECTIVE ACTION PLANS</span> may be implemented for performance issues</li>
            <li><span class="legal-emphasis">SUSPENSION</span> of privileges or access may be imposed during investigations</li>
            <li><span class="legal-emphasis">NO GUARANTEED PROCESS</span> - Company retains absolute discretion</li>
        </ul>
    </div>

    <div class="section-divider"></div>

    <div class="section-title">5. FINANCIAL AND ADMINISTRATIVE POLICIES</div>

    <div class="subsection-title">5.1 Expense and Resource Management</div>
    <div class="policy-box">
        <div class="sub-subsection-title">Resource Usage Standards:</div>
        <ul>
            <li><span class="legal-emphasis">ALL COMPANY RESOURCES</span> must be used efficiently and responsibly</li>
            <li><span class="legal-emphasis">PERSONAL USE</span> of Company resources is strictly prohibited</li>
            <li><span class="legal-emphasis">EXPENSE AUTHORIZATION</span> is required for all business expenditures</li>
            <li><span class="legal-emphasis">RECEIPTS AND DOCUMENTATION</span> are mandatory for all expenses</li>
            <li><span class="legal-emphasis">BUDGET COMPLIANCE</span> is required for all authorized expenditures</li>
        </ul>
    </div>

    <div class="subsection-title">5.2 Procurement and Vendor Management</div>
    <div class="procedure-box">
        <div class="sub-subsection-title">Procurement Standards:</div>
        <ul>
            <li><span class="legal-emphasis">APPROVED VENDORS</span> must be used for all purchases where possible</li>
            <li><span class="legal-emphasis">PROCUREMENT AUTHORIZATION</span> is required for all purchases above designated limits</li>
            <li><span class="legal-emphasis">CONTRACT REVIEW</span> is mandatory for all vendor agreements</li>
            <li><span class="legal-emphasis">PAYMENT TERMS</span> must be negotiated to optimize Company cash flow</li>
            <li><span class="legal-emphasis">VENDOR PERFORMANCE</span> monitoring and evaluation procedures</li>
        </ul>
    </div>

    <div class="section-divider"></div>

    <div class="section-title">6. COMPLIANCE AND REGULATORY POLICIES</div>

    <div class="subsection-title">6.1 Legal Compliance Framework</div>
    <div class="compliance-box">
        <div class="sub-subsection-title">Regulatory Compliance:</div>
        <ul>
            <li><span class="legal-emphasis">ALL APPLICABLE LAWS</span> must be followed in all business activities</li>
            <li><span class="legal-emphasis">REGULATORY UPDATES</span> must be monitored and implemented</li>
            <li><span class="legal-emphasis">COMPLIANCE TRAINING</span> is mandatory for all personnel</li>
            <li><span class="legal-emphasis">AUDIT COOPERATION</span> is required for all regulatory examinations</li>
            <li><span class="legal-emphasis">CORRECTIVE ACTION</span> implementation for compliance violations</li>
        </ul>

        <div class="sub-subsection-title">Industry-Specific Compliance:</div>
        <ul>
            <li><span class="legal-emphasis">DATA PROTECTION</span> compliance (UK GDPR, DPA 2018, international equivalents)</li>
            <li><span class="legal-emphasis">CONSUMER PROTECTION</span> law compliance for all customer-facing activities</li>
            <li><span class="legal-emphasis">EMPLOYMENT LAW</span> compliance for all HR-related activities</li>
            <li><span class="legal-emphasis">INTELLECTUAL PROPERTY</span> protection and compliance</li>
            <li><span class="legal-emphasis">COMPETITION LAW</span> compliance in all business activities</li>
        </ul>
    </div>

    <div class="subsection-title">6.2 Anti-Corruption and Ethics</div>
    <div class="enforcement-section">
        <div class="sub-subsection-title">Ethical Business Practices:</div>
        <ul>
            <li><span class="legal-emphasis">ZERO TOLERANCE</span> for corruption, bribery, and unethical practices</li>
            <li><span class="legal-emphasis">CONFLICTS OF INTEREST</span> must be declared and managed appropriately</li>
            <li><span class="legal-emphasis">GIFT AND ENTERTAINMENT</span> policies must be followed strictly</li>
            <li><span class="legal-emphasis">FAIR DEALING</span> with customers, suppliers, and competitors</li>
            <li><span class="legal-emphasis">TRANSPARENCY</span> in all business dealings and transactions</li>
        </ul>
    </div>

    <div class="section-divider"></div>

    <div class="section-title">7. ENFORCEMENT AND MONITORING</div>

    <div class="subsection-title">7.1 Policy Compliance Monitoring</div>
    <div class="enforcement-section">
        <div class="sub-subsection-title">Monitoring Framework:</div>
        <ul>
            <li><span class="legal-emphasis">REGULAR AUDITS</span> of policy compliance across all business areas</li>
            <li><span class="legal-emphasis">AUTOMATED MONITORING</span> of system usage and security compliance</li>
            <li><span class="legal-emphasis">PERFORMANCE METRICS</span> tracking for policy adherence</li>
            <li><span class="legal-emphasis">EXCEPTION REPORTING</span> for policy violations and non-compliance</li>
            <li><span class="legal-emphasis">CORRECTIVE ACTION</span> tracking and implementation</li>
        </ul>
    </div>

    <div class="subsection-title">7.2 Violation Response and Consequences</div>
    <div class="mandatory-warning">
        <div class="sub-subsection-title">Disciplinary Consequences:</div>
        <ul style="text-align: left;">
            <li><span class="legal-emphasis">VERBAL WARNINGS</span> for minor first-time policy violations</li>
            <li><span class="legal-emphasis">WRITTEN WARNINGS</span> for repeated or more serious violations</li>
            <li><span class="legal-emphasis">SUSPENSION</span> of access or privileges during investigations</li>
            <li><span class="legal-emphasis">TERMINATION</span> of relationship for serious or repeated violations</li>
            <li><span class="legal-emphasis">LEGAL ACTION</span> for violations involving illegal activity or significant harm to the Company</li>
        </ul>
    </div>

    <div class="section-divider"></div>

    <div class="section-title">8. ACKNOWLEDGMENT AND COMPLIANCE</div>

    <div class="acknowledgment-section">
        <div class="acknowledgment-title">🎯 MANDATORY ACKNOWLEDGMENT</div>
        <p>By participating in any Company activities, you acknowledge that:</p>
        <div class="checkbox-item">
            <input type="checkbox" class="checkbox" id="read-policies"> 
            <label for="read-policies"><strong>YOU HAVE READ AND UNDERSTOOD</strong> all policies and procedures in their entirety</label>
        </div>
        <div class="checkbox-item">
            <input type="checkbox" class="checkbox" id="agree-comply"> 
            <label for="agree-comply"><strong>YOU AGREE TO COMPLY</strong> with all current and future policies without exception</label>
        </div>
        <div class="checkbox-item">
            <input type="checkbox" class="checkbox" id="accept-responsibility"> 
            <label for="accept-responsibility"><strong>YOU ACCEPT PERSONAL RESPONSIBILITY</strong> for staying current with policy updates</label>
        </div>
        <div class="checkbox-item">
            <input type="checkbox" class="checkbox" id="understand-consequences"> 
            <label for="understand-consequences"><strong>YOU UNDERSTAND THE CONSEQUENCES</strong> of policy violations</label>
        </div>
        <div class="checkbox-item">
            <input type="checkbox" class="checkbox" id="acknowledge-authority"> 
            <label for="acknowledge-authority"><strong>YOU ACKNOWLEDGE COMPANY\'S ABSOLUTE AUTHORITY</strong> to modify policies at any time</label>
        </div>
        <div class="checkbox-item">
            <input type="checkbox" class="checkbox" id="report-violations"> 
            <label for="report-violations"><strong>YOU WILL REPORT VIOLATIONS</strong> as required by Company policies</label>
        </div>
    </div>

    <div class="signature-section">
        <div class="digital-signing">
            <h3>📝 DIGITAL SIGNATURE PROCESS</h3>
            <p><strong>This agreement must be signed electronically through the HR Portal system.</strong></p>
            <p>By clicking "Sign Agreement" in the HR Portal, you acknowledge that you have read, understood, and agree to be legally bound by all policies and procedures outlined in this comprehensive agreement.</p>
            <div class="cta-button">PROCEED TO HR PORTAL TO SIGN</div>
            <p style="margin-top: 15px; font-size: 14px; color: #666;">Digital signatures have full legal validity under UK Electronic Communications Act 2000</p>
        </div>

        <div class="signature-grid">
            <div class="signature-block">
                <strong>Participant Signature</strong>
                <div class="signature-line"></div>
                <p>Printed Name: _________________________</p>
                <p>Position/Role: ________________________</p>
                <p>Department: ___________________________</p>
            </div>
            <div class="signature-block">
                <strong>Date & Authorization</strong>
                <div class="signature-line"></div>
                <p>Date Signed: __________________________</p>
                <p>HR Representative: ____________________</p>
                <div class="signature-line"></div>
                <p>HR Signature & Date</p>
            </div>
        </div>
    </div>

    <div class="document-control">
        <div class="section-divider"></div>
        <p><strong>Document Control:</strong><br>
        Policy Version: 1.0<br>
        Effective Date: 22/07/2025<br>
        Review Date: 22/07/2026<br>
        Company: NEXI BOT LTD (Registration: 16502958 | ICO: ZB910034)</p>
        
        <div class="mandatory-warning" style="margin-top: 20px;">
            <span class="legal-emphasis">COMPLIANCE WITH ALL POLICIES AND PROCEDURES IS MANDATORY</span><br>
            <span class="legal-emphasis">VIOLATIONS WILL RESULT IN IMMEDIATE DISCIPLINARY ACTION</span><br>
            <span class="legal-emphasis">COMPANY DIRECTORS RETAIN ABSOLUTE AUTHORITY OVER ALL POLICIES</span><br>
            <span class="legal-emphasis">NO EXCEPTIONS WILL BE GRANTED WITHOUT EXPLICIT WRITTEN AUTHORIZATION</span>
        </div>
    </div>
</body>
</html>';

try {
    // Update the Company Policies & Procedures template (ID 4)
    $stmt = $pdo->prepare("UPDATE contract_templates SET content = ? WHERE id = 4");
    $result = $stmt->execute([$companyPolicies]);
    
    if ($result) {
        echo "✅ COMPANY POLICIES & PROCEDURES AGREEMENT UPDATED SUCCESSFULLY\n\n";
        
        // Verify the update
        $stmt = $pdo->prepare("SELECT name, LENGTH(content) as content_length FROM contract_templates WHERE id = 4");
        $stmt->execute();
        $template = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($template) {
            echo "📋 Template Details:\n";
            echo "   Name: " . $template['name'] . "\n";
            echo "   Content Length: " . number_format($template['content_length']) . " characters\n\n";
            
            echo "✨ Update Summary:\n";
            echo "   - Comprehensive 8-section policies & procedures framework\n";
            echo "   - Professional HTML/CSS formatting with corporate styling\n";
            echo "   - Digital signing capabilities with mandatory acknowledgment checkboxes\n";
            echo "   - Complete operational policies for all business divisions\n";
            echo "   - Absolute authority framework with mandatory compliance\n";
            echo "   - Division-specific policies (Hub, Bot, Web, Pulse)\n";
            echo "   - HR, financial, and compliance policy frameworks\n";
            echo "   - Enforcement and monitoring procedures\n\n";
            
            echo "🎯 Key Features Added:\n";
            echo "   ✓ Mandatory compliance acknowledgment system\n";
            echo "   ✓ Division-specific policy sections\n";
            echo "   ✓ Interactive checkbox confirmations\n";
            echo "   ✓ Corporate-grade visual design\n";
            echo "   ✓ Comprehensive enforcement framework\n";
            echo "   ✓ Digital and physical signing options\n";
            echo "   ✓ Absolute authority declarations\n";
            echo "   ✓ Zero tolerance policy sections\n";
        }
    } else {
        echo "❌ Error: Failed to update Company Policies & Procedures template\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}
?>
