<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DomainManagementModel;
use Illuminate\Http\Request;
use App\Models\FbSubscription;
use App\Helpers\InstagramHelper;

class InstaApiController extends Controller
{
    protected string $fbVersion = 'v22.0';

    public function client_api()
    {
        $clients = DomainManagementModel::where('status', 'active')->orderBy('id', 'desc')->get()->toArray();
        return response()->json([
            'success' => true,
            'data'    => $clients,
        ]);
    }

    /**
     * GET /api/instagram-insight/{igaccountid}/{id}
     *
     * Query params:
     *   fields  – base64-encoded JSON, e.g. {"section":"reporting"}
     *   hashtag – required when section = hashtagSearch
     *   since   – Y-m-d override (optional)
     *   until   – Y-m-d override (optional)
     *
     * Sections: overview | content | hashtagSearch | reporting | programming
     */
    public function get_instagram_insight(Request $request)
    {
        $clientFbSubscription = FbSubscription::where('client_id', $request->route('id'))->first();
        $FbPageId = $clientFbSubscription->page_id;
        $FbPageToken = $clientFbSubscription->access_token;
        $InstagramBusinessAccountArr = InstagramHelper::getInstagramBusinessAccount($this->fbVersion, $FbPageId, $FbPageToken);
        $instagramAccountId = $InstagramBusinessAccountArr->message->instagram_business_account->id;
        $clientId          = $request->route('id');          // {id} in the route
        // $instagramAccountId = $request->route('igaccountid'); // {igaccountid} in the route
        $limit             = '1000';
        $defaultSince      = date('Y-m-01', strtotime('-3 Months'));
        $defaultUntil      = date('Y-m-d');

        // ── Resolve Facebook subscription ────────────────────────────────────
        $clientFbSubscription = FbSubscription::where('client_id', $clientId)->first();

        if (empty($clientFbSubscription)) {
            return response()->json([
                'success' => false,
                'message' => 'Facebook subscription not found for this client.',
            ], 404);
        }

        $FbPageId    = $clientFbSubscription->page_id;
        $FbPageToken = $clientFbSubscription->access_token;

        // ── Decode section ────────────────────────────────────────────────────
        $section = '';
        if ($request->filled('fields')) {
            $section = $request->query('fields');
        }

        try {
            switch ($section) {

                // ── Content ───────────────────────────────────────────────────
                case 'content':
                    $since  = $request->query('since', $defaultSince);
                    $until  = $request->query('until', $defaultUntil);
                    $result = InstagramHelper::getInstagramAccountContents(
                        $this->fbVersion, $FbPageId, $FbPageToken,
                        $instagramAccountId, $limit, $since, $until
                    );

                    if (!$result->status) {
                        return response()->json(['success' => false, 'message' => $result->message], 422);
                    }

                    return response()->json([
                        'success'     => true,
                        'section'     => 'content',
                        'client_id'   => $clientId,
                        'ig_account'  => $instagramAccountId,
                        'since'       => $since,
                        'until'       => $until,
                        'data'        => $result->message,
                    ]);

                // ── Hashtag Search ────────────────────────────────────────────
                case 'hashtagSearch':
                    $hashtag = $request->query('hashtag', '');

                    if (empty($hashtag)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'The hashtag query parameter is required for hashtagSearch.',
                        ], 422);
                    }

                    $since  = $request->query('since', $defaultSince);
                    $until  = $request->query('until', $defaultUntil);
                    $result = InstagramHelper::getInstagramHashtagContents(
                        $this->fbVersion, $FbPageId, $FbPageToken,
                        $instagramAccountId, $limit, $since, $until, $hashtag
                    );

                    if (!$result->status) {
                        return response()->json(['success' => false, 'message' => $result->message], 422);
                    }

                    return response()->json([
                        'success'    => true,
                        'section'    => 'hashtagSearch',
                        'client_id'  => $clientId,
                        'ig_account' => $instagramAccountId,
                        'hashtag'    => $hashtag,
                        'since'      => $since,
                        'until'      => $until,
                        'data'       => (object) $result->message,
                    ]);

                // ── Reporting ─────────────────────────────────────────────────
                case 'reporting':
                    $todayTimestamp     = time();
                    $yesterdayTimestamp = strtotime('-1 day', $todayTimestamp);
                    $dateRanges         = [];

                    for ($i = 0; $i < 6; $i++) {
                        $targetTimestamp = strtotime("-{$i} months", $todayTimestamp);
                        $sinceDate       = date('Y-m-01', $targetTimestamp);
                        $untilDate       = ($i === 0)
                            ? date('Y-m-d', $yesterdayTimestamp)
                            : date('Y-m-t', $targetTimestamp);

                        $dateRanges[] = ['sinceDate' => $sinceDate, 'untilDate' => $untilDate];
                    }

                    $reportingData = [];
                    foreach ($dateRanges as $range) {
                        
                        $result = InstagramHelper::getInstagramReporting(
                            $this->fbVersion, $FbPageId, $FbPageToken,
                            $instagramAccountId, $limit,
                            $range['sinceDate'], $range['untilDate']
                        );

                        if (!empty($result)) {
                            $reportingData[] = [
                                'sinceDate' => $range['sinceDate'],
                                'untilDate' => $range['untilDate'],
                                'data'      => $result,
                            ];
                        }
                    }

                    return response()->json([
                        'success'    => true,
                        'section'    => 'reporting',
                        'client_id'  => $clientId,
                        'ig_account' => $instagramAccountId,
                        'data'       => $reportingData,
                    ]);

                // ── Programming ───────────────────────────────────────────────
                case 'programming':
                    $todayTimestamp = time();
                    $dateRanges     = [];

                    for ($i = 1; $i <= 3; $i++) {
                        $targetTimestamp = strtotime("-{$i} months", $todayTimestamp);
                        $dateRanges[]    = [
                            'sinceDate' => date('Y-m-01', $targetTimestamp),
                            'untilDate' => date('Y-m-t', $targetTimestamp),
                        ];
                    }

                    $reportingData = [];
                    foreach ($dateRanges as $range) {
                        $result = InstagramHelper::getInstagramProgramming(
                            $this->fbVersion, $FbPageId, $FbPageToken,
                            $instagramAccountId, $limit,
                            $range['sinceDate'], $range['untilDate']
                        );

                        if (!empty($result)) {
                            $reportingData[] = [
                                'sinceDate' => $range['sinceDate'],
                                'untilDate' => $range['untilDate'],
                                'data'      => $result,
                            ];
                        }
                    }

                    $processedData = [];
                    if (!empty($reportingData)) {
                        $processedData = InstagramHelper::processreportingData(
                            json_encode($reportingData)
                        );
                    }

                    return response()->json([
                        'success'    => true,
                        'section'    => 'programming',
                        'client_id'  => $clientId,
                        'ig_account' => $instagramAccountId,
                        'data'       => $processedData,
                    ]);

                // ── Overview (default) ────────────────────────────────────────
                default:
                    $result = InstagramHelper::getInstagramAccountDetails(
                        $this->fbVersion, $FbPageId, $FbPageToken, $instagramAccountId
                    );

                    if (!$result->status) {
                        return response()->json(['success' => false, 'message' => $result->message], 422);
                    }

                    return response()->json([
                        'success'    => true,
                        'section'    => 'overview',
                        'client_id'  => $clientId,
                        'ig_account' => $instagramAccountId,
                        'data'       => $result->message,
                    ]);
            }
        } catch (\PDOException $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()], 500);
        } catch (\Throwable $ex) {
            return response()->json(['success' => false, 'message' => $ex->getMessage()], 500);
        }
    }
}