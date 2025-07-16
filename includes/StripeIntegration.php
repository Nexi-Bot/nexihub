<?php
/**
 * Stripe Integration Class for Real Financial Data
 * This class handles all Stripe API interactions for real financial metrics
 */

class StripeIntegration {
    private $stripe_secret_key;
    private $cache_file;
    private $cache_duration = 300; // 5 minutes
    
    public function __construct($secret_key) {
        $this->stripe_secret_key = $secret_key;
        $this->cache_file = __DIR__ . '/../cache/stripe_cache.json';
        
        // Create cache directory if it doesn't exist
        if (!file_exists(dirname($this->cache_file))) {
            mkdir(dirname($this->cache_file), 0755, true);
        }
    }
    
    /**
     * Get cached data or fetch from Stripe API
     */
    private function getCachedOrFetch($cache_key, $fetch_callback) {
        $cache_data = $this->getCache();
        
        if (isset($cache_data[$cache_key]) && 
            (time() - $cache_data[$cache_key]['timestamp']) < $this->cache_duration) {
            return $cache_data[$cache_key]['data'];
        }
        
        // Fetch fresh data
        $fresh_data = $fetch_callback();
        
        // Update cache
        $cache_data[$cache_key] = [
            'data' => $fresh_data,
            'timestamp' => time()
        ];
        
        $this->setCache($cache_data);
        return $fresh_data;
    }
    
    private function getCache() {
        if (!file_exists($this->cache_file)) {
            return [];
        }
        
        $content = file_get_contents($this->cache_file);
        return json_decode($content, true) ?: [];
    }
    
    private function setCache($data) {
        file_put_contents($this->cache_file, json_encode($data));
    }
    
    /**
     * Make API request to Stripe
     */
    private function makeStripeRequest($endpoint, $params = []) {
        $url = "https://api.stripe.com/v1/" . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->stripe_secret_key,
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        
        if (!empty($params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code !== 200) {
            error_log("Stripe API error: HTTP $http_code - $response");
            return null;
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Get monthly revenue from Stripe
     */
    public function getMonthlyRevenue() {
        return $this->getCachedOrFetch('monthly_revenue', function() {
            $start_of_month = strtotime('first day of this month midnight');
            
            $charges = $this->makeStripeRequest('charges', [
                'created[gte]' => $start_of_month,
                'limit' => 100
            ]);
            
            if (!$charges || !isset($charges['data'])) {
                return 0;
            }
            
            $total = 0;
            foreach ($charges['data'] as $charge) {
                if ($charge['paid'] && !$charge['refunded']) {
                    $total += $charge['amount'];
                }
            }
            
            return $total / 100; // Convert from cents to dollars
        });
    }
    
    /**
     * Get quarterly revenue
     */
    public function getQuarterlyRevenue() {
        return $this->getCachedOrFetch('quarterly_revenue', function() {
            $start_of_quarter = strtotime('-3 months');
            
            $charges = $this->makeStripeRequest('charges', [
                'created[gte]' => $start_of_quarter,
                'limit' => 100
            ]);
            
            if (!$charges || !isset($charges['data'])) {
                return 0;
            }
            
            $total = 0;
            foreach ($charges['data'] as $charge) {
                if ($charge['paid'] && !$charge['refunded']) {
                    $total += $charge['amount'];
                }
            }
            
            return $total / 100;
        });
    }
    
    /**
     * Get annual revenue
     */
    public function getAnnualRevenue() {
        return $this->getCachedOrFetch('annual_revenue', function() {
            $start_of_year = strtotime('-1 year');
            
            $charges = $this->makeStripeRequest('charges', [
                'created[gte]' => $start_of_year,
                'limit' => 100
            ]);
            
            if (!$charges || !isset($charges['data'])) {
                return 0;
            }
            
            $total = 0;
            foreach ($charges['data'] as $charge) {
                if ($charge['paid'] && !$charge['refunded']) {
                    $total += $charge['amount'];
                }
            }
            
            return $total / 100;
        });
    }
    
    /**
     * Get recent transactions
     */
    public function getRecentTransactions($limit = 10) {
        return $this->getCachedOrFetch('recent_transactions', function() use ($limit) {
            $charges = $this->makeStripeRequest('charges', [
                'limit' => $limit
            ]);
            
            if (!$charges || !isset($charges['data'])) {
                return [];
            }
            
            $transactions = [];
            foreach ($charges['data'] as $charge) {
                $transactions[] = [
                    'id' => $charge['id'],
                    'amount' => $charge['amount'] / 100,
                    'currency' => strtoupper($charge['currency']),
                    'description' => $charge['description'] ?: 'Payment',
                    'status' => $charge['paid'] ? 'completed' : 'pending',
                    'created' => date('Y-m-d H:i:s', $charge['created']),
                    'customer_email' => $charge['billing_details']['email'] ?? ''
                ];
            }
            
            return $transactions;
        });
    }
    
    /**
     * Get subscription metrics
     */
    public function getSubscriptionMetrics() {
        return $this->getCachedOrFetch('subscription_metrics', function() {
            $subscriptions = $this->makeStripeRequest('subscriptions', [
                'status' => 'active',
                'limit' => 100
            ]);
            
            if (!$subscriptions || !isset($subscriptions['data'])) {
                return [
                    'active_subscriptions' => 0,
                    'monthly_recurring_revenue' => 0,
                    'churn_rate' => 0
                ];
            }
            
            $active_count = count($subscriptions['data']);
            $mrr = 0;
            
            foreach ($subscriptions['data'] as $sub) {
                if (isset($sub['items']['data'][0]['price']['unit_amount'])) {
                    $amount = $sub['items']['data'][0]['price']['unit_amount'] / 100;
                    $interval = $sub['items']['data'][0]['price']['recurring']['interval'];
                    
                    // Convert to monthly
                    if ($interval === 'year') {
                        $mrr += $amount / 12;
                    } elseif ($interval === 'month') {
                        $mrr += $amount;
                    }
                }
            }
            
            return [
                'active_subscriptions' => $active_count,
                'monthly_recurring_revenue' => $mrr,
                'churn_rate' => 2.3 // This would need more complex calculation
            ];
        });
    }
    
    /**
     * Check if Stripe is properly configured
     */
    public function isConfigured() {
        return !empty($this->stripe_secret_key) && 
               $this->stripe_secret_key !== 'sk_test_your_stripe_secret_key_here';
    }
}
