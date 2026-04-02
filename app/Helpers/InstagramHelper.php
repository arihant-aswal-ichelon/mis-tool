<?php
namespace App\Helpers;
use Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramHelper{

    public static function getInstagramBusinessAccount($fbVersion, $FbPageId, $FbPageToken){
        $result = array();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/'.$fbVersion.'/'.$FbPageId.'?fields=instagram_business_account&access_token='.$FbPageToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        
        if(isset($error_msg)){
            $result = array('status' => false, 'message' => $error_msg);
            return (object) $result;
        }
        $response = json_decode($response);
        if(isset($response->error)){
            $result = array('status' => false, 'message' => $response->error->message);
            return (object) $result;
        }
        $result = array('status' => true, 'message' => $response);

        return (object) $result;
    }

    public static function getInstagramAccountDetails($fbVersion, $FbPageId, $FbPageToken, $instagramAccountId){
        $result = array();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/'.$fbVersion.'/'.$instagramAccountId.'?fields=id,name,followers_count,username,follows_count,media_count,biography,website,profile_picture_url&access_token='.$FbPageToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if(isset($error_msg)){
            $result = array('status' => false, 'message' => $error_msg);
            return (object) $result;
        }
        $response = json_decode($response);
        if(isset($response->error)){
            $result = array('status' => false, 'message' => $response->error->message);
            return (object) $result;
        }
        $result = array('status' => true, 'message' => $response);

        return (object) $result;
    }

    public static function getInstagramAccountContents($fbVersion, $FbPageId, $FbPageToken, $instagramAccountId, $limit, $since, $until){
        $result = array();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/'.$fbVersion.'/'.$instagramAccountId.'/media?fields=comments_count,alt_text,caption,like_count,media_type,media_url,media_product_type,thumbnail_url,timestamp,permalink,id&limit='.$limit.'&since='.$since.'&until='.$until.'&access_token='.$FbPageToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if(isset($error_msg)){
            $result = array('status' => false, 'message' => $error_msg);
            return (object) $result;
        }
        $response = json_decode($response);
        if(isset($response->error)){
            $result = array('status' => false, 'message' => $response->error->message);
            return (object) $result;
        }
        
        $result = array('status' => true, 'message' => $response);
        
        return (object) $result;
    }

    public static function getInstagramMediaInsight($fbVersion, $FbPageId, $FbPageToken, $igaccountid, $mediaid){
        $result = array();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/'.$fbVersion.'/'.$mediaid.'?fields=media_url,caption,alt_text,like_count,thumbnail_url,comments_count,media_type,media_product_type,timestamp,comments{id,like_count,media,text,timestamp,from,user,replies{like_count,text,timestamp,parent_id,user,replies}},shortcode,permalink&access_token='.$FbPageToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if(isset($error_msg)){
            $result = array('status' => false, 'message' => $error_msg);
            return (object) $result;
        }
        $response = json_decode($response);
        if(isset($response->error)){
            $result = array('status' => false, 'message' => $response->error->message);
            return (object) $result;
        }
        $result = array('status' => true, 'message' => $response);
        return (object) $result;
    }

    public static function getInstagramHashtagContents($fbVersion, $FbPageId, $FbPageToken, $instagramAccountId, $limit, $since, $until, $hashtag){
        $result = $InstagramHashtagTopMediaList = $InstagramHashtagRecentMediaList = array();
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/'.$fbVersion.'/ig_hashtag_search?user_id='.$instagramAccountId.'&q='.$hashtag.'&access_token='.$FbPageToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if(isset($error_msg)){
            $result = array('status' => false, 'message' => $error_msg);
            return (object) $result;
        }
        $response = json_decode($response);
        if(isset($response->error)){
            $result = array('status' => false, 'message' => $response->error->message);
            return (object) $result;
        }

        $hashtagId = (isset($response->data[0]->id) && !empty($response->data[0]->id))?$response->data[0]->id:0;
        if($hashtagId > 0){
            $InstagramHashtagRecentMediaList = self::getInstagramHashtagRecentMedia($fbVersion, $FbPageId, $FbPageToken, $instagramAccountId, $limit, $since, $until, $hashtagId);

            $InstagramHashtagTopMediaList = self::getInstagramHashtagTopMedia($fbVersion, $FbPageId, $FbPageToken, $instagramAccountId, $limit, $since, $until, $hashtagId);
        }

        $result = array('status' => true, 'message' => array('InstagramHashtagRecentMediaList' =>(object) $InstagramHashtagRecentMediaList, 'InstagramHashtagTopMediaList' => (object) $InstagramHashtagTopMediaList));
        return (object) $result;
    }

    public static function getInstagramHashtagRecentMedia($fbVersion, $FbPageId, $FbPageToken, $instagramAccountId, $limit, $since, $until, $hashtagId){
        $result = array();
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/'.$fbVersion.'/'.$hashtagId.'/recent_media?fields=caption,comments_count,like_count,media_type,media_url,permalink,timestamp,media_product_type&user_id='.$instagramAccountId.'&access_token='.$FbPageToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if(isset($error_msg)){
            $result = array('status' => false, 'message' => $error_msg);
            return (object) $result;
        }
        $response = json_decode($response);
        if(isset($response->error)){
            $result = array('status' => false, 'message' => $response->error->message);
            return (object) $result;
        }

        $result = array('status' => true, 'message' => $response);

        return (object) $result;
    }

    public static function getInstagramHashtagTopMedia($fbVersion, $FbPageId, $FbPageToken, $instagramAccountId, $limit, $since, $until, $hashtagId){
        $result = array();
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/'.$fbVersion.'/'.$hashtagId.'/top_media?fields=caption,comments_count,like_count,media_type,media_url,permalink,timestamp,media_product_type&user_id='.$instagramAccountId.'&access_token='.$FbPageToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if(isset($error_msg)){
            $result = array('status' => false, 'message' => $error_msg);
            return (object) $result;
        }
        $response = json_decode($response);

        if(isset($response->error)){
            $result = array('status' => false, 'message' => $response->error->message);
            return (object) $result;
        }
        
        $result = array('status' => true, 'message' => $response);

        return (object) $result;
    }

    public static function getInstagramReporting($fbVersion, $FbPageId, $FbPageToken, $igUserId, $limit, $since, $until){

        $graphUrl = 'https://graph.facebook.com/'.$fbVersion;
        $insightsData = [];
        // Batch Request Payload
        $batchPayload = [
            [
                'method' => 'GET',
                'name' => 'reach',
                'relative_url' => "{$igUserId}/insights?metric=reach&period=day&since={$since}&until={$until}&limit=1000"
            ],
            [
                'method' => 'GET',
                'name' => 'website_clicks',
                'relative_url' => "{$igUserId}/insights?metric=website_clicks&period=day&metric_type=total_value&since={$since}&until={$until}&limit=1000"
            ],
            [
                'method' => 'GET',
                'name' => 'profile_views',
                'relative_url' => "{$igUserId}/insights?metric=profile_views&period=day&metric_type=total_value&since={$since}&until={$until}&limit=1000"
            ],
            [
                'method' => 'GET',
                'name' => 'accounts_engaged',
                'relative_url' => "{$igUserId}/insights?metric=accounts_engaged&period=day&metric_type=total_value&since={$since}&until={$until}&limit=1000"
            ],
            [
                'method' => 'GET',
                'name' => 'total_interactions',
                'relative_url' => "{$igUserId}/insights?metric=total_interactions&period=day&metric_type=total_value&since={$since}&until={$until}&limit=1000"
            ],
            [
                'method' => 'GET',
                'name' => 'action_breakdown',
                'relative_url' => "{$igUserId}/insights?metric=likes,comments,shares,saves,replies&period=day&metric_type=total_value&since={$since}&until={$until}&limit=1000"
            ],
            [
                'method' => 'GET',
                'name' => 'follows_unfollows',
                'relative_url' => "{$igUserId}/insights?metric=follows_and_unfollows&period=day&metric_type=total_value&since={$since}&until={$until}&limit=1000"
            ],
            [
                'method' => 'GET',
                'name' => 'profile_links_taps',
                'relative_url' => "{$igUserId}/insights?metric=profile_links_taps&period=day&metric_type=total_value&since={$since}&until={$until}&limit=1000"
            ],
            [
                'method' => 'GET',
                'name' => 'video_views',
                'relative_url' => "{$igUserId}/insights?metric=views&period=day&metric_type=total_value&since={$since}&until={$until}&limit=1000"
            ],
            [
                'method' => 'GET',
                'name' => 'organic_views',
                'relative_url' => "{$igUserId}/insights?metric=views&period=day&metric_type=total_value&since={$since}&until={$until}&limit=1000"
            ],
        ];

        // Execute in batches (Facebook limits to 50 requests per batch)
        $batchSize = 50;
        $totalBatches = ceil(count($batchPayload) / $batchSize);

        for ($i = 0; $i < $totalBatches; $i++) {
            $currentBatch = array_slice($batchPayload, $i * $batchSize, $batchSize);
            
            $data = [
                'access_token' => $FbPageToken,
                'batch' => json_encode($currentBatch)
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $graphUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if (curl_errno($ch)) {
                echo "cURL Error (Batch {$i}): " . curl_error($ch) . "\n";
            } else {
                $decodedResponse = json_decode($response, true);
                foreach ($decodedResponse as $item) {
                    if(isset($item['body'])) {
                        $insightsData[] = json_decode($item['body'], true);
                    }
                }
            }
            curl_close($ch);
            
            // Respect rate limits (200 calls/hour)
            if ($i < $totalBatches - 1) {
                sleep(18); // ~200 calls/hour = ~1 call every 18 seconds
            }
        }
        // Process the insights data
        if (!isset($insightsData) || !is_array($insightsData)) {
            echo "No insights data received or data format is incorrect.";
            exit;
        }

        $metricSums = [];
        foreach ($insightsData as $dataArray) {
            if (isset($dataArray['data']) && is_array($dataArray['data'])) {
                foreach ($dataArray['data'] as $metric) {
                    $metricName = $metric['name'];
                    
                    // Handle metrics with values array (like reach)
                    if (isset($metric['values']) && is_array($metric['values'])) {
                        foreach ($metric['values'] as $valueEntry) {
                            if (isset($valueEntry['value'])) {
                                if (!isset($metricSums[$metricName])) {
                                    $metricSums[$metricName] = 0;
                                }
                                $metricSums[$metricName] += $valueEntry['value'];
                            }
                        }
                    }
                    // Handle metrics with total_value
                    elseif (isset($metric['total_value']['value'])) {
                        if (!isset($metricSums[$metricName])) {
                            $metricSums[$metricName] = 0;
                        }
                        $metricSums[$metricName] += $metric['total_value']['value'];
                    }
                }
            }
        }

        // Calculate Content Interactions (Sum of Likes, Shares, Comments, Saves, Replies)
        $contentInteractions = 0;
        $interactionMetrics = ['likes', 'comments', 'shares', 'saves', 'replies'];

        foreach ($interactionMetrics as $metric) {
            if (isset($metricSums[$metric])) {
                $contentInteractions += $metricSums[$metric];
            }
        }

        // For posts data, we need to make additional API calls
        // Let's fetch media data to count posts
        $postsData = [];
        $mediaTypes = [];

        $mediaUrl = "{$graphUrl}/{$igUserId}/media?fields=media_type,media_product_type&limit=100&since={$since}&until={$until}&access_token={$FbPageToken}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $mediaUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $mediaResponse = curl_exec($ch);
        curl_close($ch);

        $mediaData = json_decode($mediaResponse, true);
        
        // Prepare the final performance metrics array
        $contentInteractions = 0;
        $interactionMetrics = ['likes', 'comments', 'shares', 'saves', 'replies'];
        foreach ($interactionMetrics as $metric) {
            if (isset($metricSums[$metric])) {
                $contentInteractions += $metricSums[$metric];
            }
        }

        $totalPosts = $staticPosts = $reelPosts = 0;

        if (isset($mediaData['data']) && is_array($mediaData['data'])) {
            $totalPosts = count($mediaData['data']);
            
            foreach ($mediaData['data'] as $post) {
                if ($post['media_type'] == 'CAROUSEL_ALBUM' || $post['media_type'] == 'IMAGE') {
                    $staticPosts++;
                } elseif ($post['media_type'] == 'VIDEO' && isset($post['media_product_type']) && $post['media_product_type'] == 'REELS') {
                    $reelPosts++;
                }
            }
        }

        // Calculate engagement rate if we have reach and interactions
        if (isset($metricSums['reach']) && $metricSums['reach'] > 0) {
            $engagementRate = ($contentInteractions / $metricSums['reach']) * 100;
        }

        
        $organicViews = 0;
        $organicViews = self::InstagramOrganicViews($igUserId, $since, $until, $FbPageToken);
        
        $PerformanceMetrics = [
            'reach' => $metricSums['reach'] ?? 0,
            'website_clicks' => $metricSums['website_clicks'] ?? 0,
            'profile_views' => $metricSums['profile_views'] ?? 0,
            'accounts_engaged' => $metricSums['accounts_engaged'] ?? 0,
            'content_interactions' => $contentInteractions,
            'total_interactions' => $metricSums['total_interactions'] ?? 0,
            'likes' => $metricSums['likes'] ?? 0,
            'comments' => $metricSums['comments'] ?? 0,
            'shares' => $metricSums['shares'] ?? 0,
            'saves' => $metricSums['saves'] ?? 0,
            'replies' => $metricSums['replies'] ?? 0,
            'follows_unfollows' => $metricSums['follows_and_unfollows'] ?? 0,
            'profile_links_taps' => $metricSums['profile_links_taps'] ?? 0,
            'video_views' => $metricSums['views'] ?? 0,
            'static_posts' => $staticPosts,
            'reel_posts' => $reelPosts,
            'total_posts' => $totalPosts,
            'engagement_rate' => isset($metricSums['reach']) ? number_format($engagementRate, 2) : 0,
            'organic_views' => $organicViews,
        ];
        
        return (object) $PerformanceMetrics;
    }

    public static function getInstagramProgramming($fbVersion, $FbPageId, $FbPageToken, $instagramAccountId, $limit, $since, $until){

        $result = array();

        $allPosts = self::fetchInstagramPosts($fbVersion, $instagramAccountId, $FbPageToken, $since, $until);

        if (empty($allPosts)) {
            return (object) ['status' => false, 'message' => 'No posts found for the given date range.'];
        }
        
        $result = array('status' => true, 'message' => $allPosts);
        return (object) $result;
    }

    public static function fetchInstagramPosts($fbVersion, $instagramAccountId, $FbPageToken, $startDate, $endDate) {
        $url = 'https://graph.facebook.com/'.$fbVersion.'/'.$instagramAccountId . '/media';
        
        $params = [
            'fields' => 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp,username',
            'since' => $startDate,
            'until' => $endDate,
            'access_token' => $FbPageToken,
            'limit' => 100 // Maximum allowed by API
        ];
        
        $allPosts = [];
        $nextUrl = $url . '?' . http_build_query($params);
        
        do {
            $response = self::makeApiRequest($nextUrl);
            if (isset($response['data'])) {
                $allPosts = array_merge($allPosts, $response['data']);
            }
            
            $nextUrl = $response['paging']['next'] ?? null;
        } while ($nextUrl);

        $results = [];
        foreach ($allPosts as $post) {
            $isVideo = ($post['media_type'] === 'VIDEO' || $post['media_type'] === 'REELS');
            $insights = self::fetchPostInsights($fbVersion, $instagramAccountId, $FbPageToken, $post['id'], $isVideo);
            
            $results[] = [
                'post' => $post,
                'insights' => $insights
            ];
        }
        
        return $results;
    }

    public static function fetchPostInsights($fbVersion, $instagramAccountId, $FbPageToken, $postId, $isVideo = false) {

        $metrics = [
            'views', // includes likes and comments
            'likes',
            'comments',
            'reach',
            'saved',
            'shares'
        ];
        
        // if ($isVideo) {
        //     $metrics[] = 'video_views';
        // }

        $url = 'https://graph.facebook.com/'.$fbVersion.'/'.$postId . '/insights';
        $params = [
            'metric' => implode(',', $metrics),
            'access_token' => $FbPageToken,
        ];
        
        $response = self::makeApiRequest($url . '?metric_type=total_value&period=day&' . http_build_query($params));
        
        $insights = [];
        if (isset($response['data'])) {
            foreach ($response['data'] as $metric) {
                $insights[$metric['name']] = $metric['values'][0]['value'] ?? 0;
            }
        }
        
        return $insights;
    }
    
    private static function makeApiRequest($url) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }
        
        return json_decode($response, true);
    }

    public static function processreportingData($reportingData){

        // Sample input (can be replaced with POST data)
        $inputData = json_decode($reportingData, true);

        // Process each post and assign category
        $processedPosts = [];
        foreach ($inputData as $igpost) {
            $igpostArr = $igpost['data']['message'];
            if(is_array($igpostArr)) {
                foreach ($igpostArr as $key => $post) {
                    $caption = $post['post']['caption'] ?? '';
                    
                    $category = self::detectPostCategory($caption);
                    
                    $processedPosts[] = [
                        'id' => $post['post']['id'],
                        'caption' => $caption,
                        'category' => $category,
                        'views' => $post['insights']['views'],
                        'likes' => $post['insights']['likes'],
                        'comments' => $post['insights']['comments'],
                        'reach' => $post['insights']['reach'],
                        'saved' => $post['insights']['saved'],
                        'shares' => $post['insights']['shares'],
                    ];
                }
            }
        }

        return $processedPosts;
    }

    public static function detectPostCategory($caption) {
        // If caption is empty, return uncategorized
        if (empty(trim($caption))) {
            return "Uncategorized";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer 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',
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a content classification expert. Analyze the given text and classify it into exactly one of these categories: informational, results/case study, branding, moment marketing, topical, patient testimonial. Return only the category name without any explanations.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Classify this content: \"{$caption}\"\n\nPlease classify the content either as informational, results/case study, branding, moment marketing, topical or patient testimonial based on most dominant content coverage percentage where branding content is when its talking about unique aspects of the brand it covers, where information content is when it provides detail information about concern , utility, technology or services, where results/case study content is when it specifically talks in details about fine construct of a service outcomes or treatment impact on a patient/client where moment marketing is when it focusses especially on some trending content or situational information) where patient/client testimonial is when its referring about success of usage of the services or the outcomes achieved by the patient/client) where topical is when its a celebration/festive days\n\nReturn only the category name:"
                    ]
                ],
                'max_tokens' => 20,
                'temperature' => 0.1,
            ]);

            if ($response->successful()) {
                $category = trim($response->json('choices.0.message.content'));
                
                // Validate the returned category
                $validCategories = [
                    'informational', 'results/case study', 'branding', 
                    'moment marketing', 'topical', 'patient testimonial'
                ];
                
                if (in_array(strtolower($category), $validCategories)) {
                    return $category;
                }
            }
            
            // Fallback to regex if API fails or returns invalid category
            // return self::fallbackCategoryDetection($caption);
            
        } catch (\Exception $e) {
            // Log the error and fallback to regex
            \Log::error('OpenAI API error: ' . $e->getMessage());
            // return self::fallbackCategoryDetection($caption);
        }
    }

    // Keep the original regex method as fallback
    private static function fallbackCategoryDetection($caption) {
        $category = "Uncategorized";
        
        $patterns = [
            'branding' => '/\b(clinic|trusted|centre|expert)\b/i',
            'informational' => '/\b(how long|process|myth|fact|step|guide|explained)\b/i',
            'moment marketing' => '/\b(eid|father\'s day|shivratri|doctors day|festival|jayanti)\b/i',
            'topical' => '/\b(news|trend|awareness|infertility|ivf india|fertility)\b/i',
            'patient testimonial' => '/\b(journey|story|struggle|experience|testimonial|emotional)\b/i',
        ];

        foreach ($patterns as $cat => $pattern) {
            if (preg_match($pattern, $caption)) {
                $category = $cat;
                break;
            }
        }
        return $category;
    }
    
    public static function InstagramOrganicViews($igUserId, $since, $until, $accessToken) {

        // Step 1: Get media IDs within date range
        $mediaUrl = "https://graph.facebook.com/$igUserId/media?" . http_build_query([
            'fields' => 'id,timestamp',
            'since' => strtotime($since),
            'until' => strtotime($until),
            'access_token' => $accessToken
        ]);

        $mediaResponse = json_decode(file_get_contents($mediaUrl), true);

        // Step 2: Prepare batch request for insights
        $batchPayload = [];
        foreach ($mediaResponse['data'] as $post) {
            $batchPayload[] = [
                'method' => 'GET',
                'name' => $post['id'],
                'relative_url' => "{$post['id']}/insights?metric=views"
            ];
        }

        // Step 3: Execute batch request
        $data = [
            'access_token' => $accessToken,
            'batch' => json_encode($batchPayload)
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://graph.facebook.com/",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            die('cURL error: ' . curl_error($ch));
        }

        curl_close($ch);

        // Step 4: Process results
        $organicViews = [];
        $responseData = json_decode($response, true);
        
        foreach ($responseData as $item) {
            if ($item['code'] == 200) {
                $insight = json_decode($item['body'], true);
                $views = $insight['data'][0]['values'][0]['value'] ?? 0;
                
                $organicViews[] = $views;
            }
        }
        
        return array_sum($organicViews);
    }

}
?>