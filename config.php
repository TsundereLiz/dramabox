<?php
// API Configuration
define('API_BASE_URL', 'https://dramaboxapi.web.id/api/');
define('API_KEY', 'FREE_DEMO_2DEE80DCCB473C58');

// Site Configuration
define('SITE_NAME', 'DramaBox');
define('SITE_DESCRIPTION', 'Watch Latest Drama Series Online');

// API Helper Function
function callAPI($endpoint, $params = []) {
    // Add API key to parameters
    $params['api_key'] = API_KEY;
    
    // Build URL
    $url = API_BASE_URL . $endpoint . '?' . http_build_query($params);
    
    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-API-Key: ' . API_KEY,
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ]);
    
    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Check for errors
    if ($httpCode !== 200 || !$response) {
        // Redirect to API expired page
        header('Location: api-expired.php');
        exit;
    }
    
    // Decode JSON response
    $data = json_decode($response, true);
    
    // Check if API returned error (license expired, invalid, etc)
    if (isset($data['status']) && $data['status'] === false) {
        // Check for license-related errors
        if (isset($data['error_code']) && in_array($data['error_code'], [
            'MISSING_LICENSE', 
            'INVALID_LICENSE', 
            'EXPIRED_LICENSE', 
            'INACTIVE_LICENSE',
            'DOMAIN_MISMATCH'
        ])) {
            header('Location: api-expired.php');
            exit;
        }
    }
    
    return $data;
}

// Get browse/list drama
function getBrowseDrama($page = 1, $lang = 'in') {
    return callAPI('index.php', [
        'page' => $page,
        'lang' => $lang
    ]);
}

// Get drama detail
function getDramaDetail($bookId, $language = 'id') {
    return callAPI('drama.php', [
        'bookId' => $bookId,
        'language' => $language
    ]);
}

// Search drama
function searchDrama($query, $lang = 'in', $page = 1) {
    return callAPI('cari.php', [
        'q' => $query,
        'lang' => $lang,
        'p' => $page
    ]);
}
