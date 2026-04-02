<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        '/addtagname',
        '/gettagdetails',
        '/all_yt_channel_ajax',
        '/all_yt_kpi_playlist_ajax',
        '/all_yt_kpi_views_ajax',
        '/all_yt_last_video_ajax',
        '/all_yt_benchmark_ajax'
    ];
}
