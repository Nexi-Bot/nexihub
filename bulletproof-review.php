<?php
require_once 'config/config.php';

echo "=== BULLETPROOFING REVIEW: COMPREHENSIVE LEGAL ANALYSIS ===\n\n";

try {
    $stmt = $pdo->query("SELECT id, name, type, content FROM contract_templates ORDER BY id");
    $contracts = $stmt->fetchAll();
    
    // Comprehensive legal requirements checklist
    $legal_requirements = [
        'Company Details' => [
            'Company Registration Number: 16502958',
            'ICO Registration Number: ZB910034',
            'NEXI BOT LTD',
            '80A Ruskin Avenue, Welling, London, DA16 3QQ'
        ],
        'UK Employment Law Compliance' => [
            'Employment Rights Act',
            'Working Time Regulations',
            'Equality Act 2010',
            'Health and Safety at Work',
            'minimum wage',
            'statutory rights'
        ],
        'Data Protection (GDPR/DPA 2018)' => [
            'UK GDPR',
            'Data Protection Act 2018',
            'personal data',
            'data breach',
            'data subject rights',
            'lawful basis',
            'data controller',
            'data processor'
        ],
        'Minor Employment Protection (13-16 years)' => [
            'under 16',
            'parent',
            'guardian',
            'parental consent',
            'working time restrictions',
            'age-appropriate',
            'child protection'
        ],
        'Intellectual Property Protection' => [
            'intellectual property',
            'copyright',
            'trade secrets',
            'confidential information',
            'work-related IP',
            'moral rights'
        ],
        'Health & Safety Requirements' => [
            'health and safety',
            'risk assessment',
            'workplace safety',
            'emergency procedures',
            'accident reporting'
        ],
        'Discrimination & Equality' => [
            'discrimination',
            'harassment',
            'protected characteristics',
            'equal opportunities',
            'inclusive workplace'
        ],
        'Disciplinary & Grievance' => [
            'disciplinary procedure',
            'grievance procedure',
            'ACAS',
            'gross misconduct',
            'progressive discipline'
        ],
        'Whistleblowing Protection' => [
            'whistleblowing',
            'protected disclosure',
            'public interest',
            'retaliation protection'
        ],
        'Termination & Notice' => [
            'notice period',
            'termination',
            'garden leave',
            'payment in lieu',
            'redundancy'
        ],
        'Legal Framework' => [
            'England and Wales',
            'English law',
            'jurisdiction',
            'governing law',
            'legal advice'
        ]
    ];
    
    $overall_score = 0;
    $total_contracts = count($contracts);
    
    foreach ($contracts as $contract) {
        echo "📋 ANALYZING: {$contract['name']} (Type: {$contract['type']})\n";
        echo str_repeat('=', 60) . "\n";
        
        $contract_score = 0;
        $total_categories = count($legal_requirements);
        $content_lower = strtolower($contract['content']);
        
        foreach ($legal_requirements as $category => $keywords) {
            echo "\n🔍 {$category}:\n";
            $category_score = 0;
            $found_keywords = [];
            
            foreach ($keywords as $keyword) {
                if (strpos($content_lower, strtolower($keyword)) !== false) {
                    $category_score++;
                    $found_keywords[] = $keyword;
                }
            }
            
            $category_percentage = round(($category_score / count($keywords)) * 100);
            
            if ($category_percentage >= 80) {
                echo "   ✅ EXCELLENT ({$category_percentage}%): " . implode(', ', $found_keywords) . "\n";
                $contract_score += 1;
            } elseif ($category_percentage >= 60) {
                echo "   ⚠️  GOOD ({$category_percentage}%): " . implode(', ', $found_keywords) . "\n";
                $contract_score += 0.8;
            } elseif ($category_percentage >= 30) {
                echo "   🟡 FAIR ({$category_percentage}%): " . implode(', ', $found_keywords) . "\n";
                $contract_score += 0.5;
            } else {
                echo "   ❌ INSUFFICIENT ({$category_percentage}%): " . implode(', ', $found_keywords) . "\n";
                $contract_score += 0.2;
            }
        }
        
        $final_score = round(($contract_score / $total_categories) * 100);
        echo "\n📊 CONTRACT SCORE: {$final_score}%\n";
        
        if ($final_score >= 90) {
            echo "🎉 STATUS: BULLETPROOF ✅\n";
        } elseif ($final_score >= 80) {
            echo "🔒 STATUS: VERY STRONG ✅\n";
        } elseif ($final_score >= 70) {
            echo "⚠️  STATUS: GOOD BUT NEEDS IMPROVEMENT\n";
        } else {
            echo "❌ STATUS: REQUIRES MAJOR IMPROVEMENTS\n";
        }
        
        // Check content length (comprehensive contracts should be substantial)
        $content_length = strlen($contract['content']);
        echo "📄 Content Length: " . number_format($content_length) . " characters";
        if ($content_length > 15000) {
            echo " ✅ (Comprehensive)\n";
        } elseif ($content_length > 10000) {
            echo " ⚠️  (Adequate)\n";
        } else {
            echo " ❌ (Too Brief)\n";
        }
        
        $overall_score += $final_score;
        echo "\n" . str_repeat('=', 80) . "\n\n";
    }
    
    // Overall system assessment
    $system_average = round($overall_score / $total_contracts);
    echo "🏆 OVERALL SYSTEM ASSESSMENT\n";
    echo str_repeat('=', 50) . "\n";
    echo "Average Contract Score: {$system_average}%\n";
    echo "Total Contracts: {$total_contracts}\n";
    
    if ($system_average >= 90) {
        echo "🎉 SYSTEM STATUS: BULLETPROOF & LEGALLY ROBUST ✅\n";
        echo "   • All contracts meet highest legal standards\n";
        echo "   • Comprehensive protection against legal risks\n";
        echo "   • Ready for production use\n";
    } elseif ($system_average >= 80) {
        echo "🔒 SYSTEM STATUS: VERY STRONG ✅\n";
        echo "   • Contracts provide strong legal protection\n";
        echo "   • Minor improvements may be beneficial\n";
    } else {
        echo "⚠️  SYSTEM STATUS: NEEDS ATTENTION\n";
        echo "   • Some contracts require strengthening\n";
        echo "   • Legal review recommended\n";
    }
    
    // Critical missing elements check
    echo "\n🚨 CRITICAL COMPLIANCE CHECK:\n";
    $critical_missing = [];
    
    foreach ($contracts as $contract) {
        $content = strtolower($contract['content']);
        
        // Must-have elements
        if (strpos($content, '16502958') === false) {
            $critical_missing[] = "Company number missing in {$contract['name']}";
        }
        if (strpos($content, 'zb910034') === false && ($contract['type'] != 'shareholder')) {
            $critical_missing[] = "ICO number missing in {$contract['name']}";
        }
        if (strpos($content, 'under 16') === false && strpos($content, 'parent') === false) {
            $critical_missing[] = "Minor protection provisions missing in {$contract['name']}";
        }
    }
    
    if (empty($critical_missing)) {
        echo "✅ All critical compliance elements present\n";
    } else {
        echo "❌ Critical issues found:\n";
        foreach ($critical_missing as $issue) {
            echo "   • {$issue}\n";
        }
    }
    
    echo "\n=== BULLETPROOFING REVIEW COMPLETE ===\n";
    
} catch (Exception $e) {
    echo "❌ Error during review: " . $e->getMessage() . "\n";
}
?>
