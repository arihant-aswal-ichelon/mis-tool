@extends('layouts.page-app')
@section("content")

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Instagram Programming</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo route('home'); ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo url('/view-client/'.$client_id); ?>">Properties</a></li>
                            <li class="breadcrumb-item active">Instagram Programming</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <style>
            .chart-container { width: 600px; height: 400px; margin: 20px auto; }
            button { 
                padding: 10px 20px; 
                margin: 5px;
                color: white; 
                border: none; 
                cursor: pointer; 
            }
            #analyze-btn { background: #4CAF50; }
            #export-posts { background: #2196F3; }
            #export-analysis { background: #9C27B0; }
            button:hover { opacity: 0.9; }
            
            /* Table Styles */
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
                font-size: 0.9em;
            }
            th, td {
                padding: 12px 15px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            th {
                background-color: #f2f2f2;
                position: sticky;
                top: 0;
            }
            tr:hover {
                background-color: #f5f5f5;
            }
            .top-category { font-weight: bold; color: #2E7D32; }
            .second-category { color: #0288D1; }
            .button-group { margin: 20px 0; }
            
            /* Scrollable table container */
            .table-container {
                max-height: 500px;
                overflow-y: auto;
                margin: 20px 0;
                border: 1px solid #ddd;
            }
        </style>

        <!-- Main content -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @include('analysis.instagram.filters.common')
                        <div class="row">
                            <h4 class="mb-sm-0">Last 3 months Posts Analysis</h4>
                            <div class="table-container">
                                <table id="posts-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Caption</th>
                                            <th>Category</th>
                                            <th>Views</th>
                                            <th>Likes</th>
                                            <th>Comments</th>
                                            <th>Reach</th>
                                            <th>Shares</th>
                                            <th>Saved</th>
                                        </tr>
                                    </thead>
                                    <tbody id="posts-body"></tbody>
                                </table>
                            </div>
                            <div class="chart-container"><canvas id="analyticsChart"></canvas></div>
                            <div class="col-md-12 mx-auto">
                                <button id="analyze-btn" >Analyze Performance</button>
                                <button id="export-posts">Export Post Data (CSV)</button>
                                <button id="export-analysis">Export Analysis (CSV)</button>
                            </div>
                            <h2>Performance Analysis</h2>
                            <div class="table-container">
                                <table id="analysis-results">
                                    <thead>
                                        <tr>
                                            <th>Goal</th>
                                            <th>Top Category</th>
                                            <th>Total</th>
                                            <th>Second Best Category</th>
                                            <th>Total</th>
                                            <th>Post Count</th>
                                        </tr>
                                    </thead>
                                    <tbody id="analysis-body"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sample data - replace with your actual mockData
    const mockData = <?php echo (isset($data['igData']) && !empty($data['igData']))?json_encode($data['igData']):""; ?>;
    
    let currentAnalytics = {};
    let currentAnalysisData = [];
    let analyticsChart = null;

    // Helper function to sort categories by metric and get top 2
    function getTopTwoCategories(analytics, metric) {
        return Object.keys(analytics)
            .sort((a, b) => analytics[b][metric] - analytics[a][metric])
            .slice(0, 2);
    }

    // Generate analytics from posts
    function generateAnalytics(posts) {
        const analytics = {};
        posts.forEach(post => {
            if (!analytics[post.category]) {
                analytics[post.category] = {
                    total_posts: 0,
                    total_views: 0,
                    total_reach: 0,
                    total_likes: 0,
                    total_comments: 0,
                    total_shares: post.shares || 0,
                    total_saved: post.saved || 0
                };
            }
            analytics[post.category].total_posts++;
            analytics[post.category].total_views += post.views;
            analytics[post.category].total_reach += post.reach;
            analytics[post.category].total_likes += post.likes;
            analytics[post.category].total_comments += post.comments;
            analytics[post.category].total_shares += post.shares || 0;
            analytics[post.category].total_saved += post.saved || 0;
        });

        // Calculate averages
        for (const category in analytics) {
            analytics[category].avg_views = Math.round(analytics[category].total_views / analytics[category].total_posts);
            analytics[category].avg_likes = Math.round(analytics[category].total_likes / analytics[category].total_posts);
        }

        return analytics;
    }

    // Render posts in tabular format
    function renderPosts(posts) {
        const container = document.getElementById('posts-body');
        container.innerHTML = '';

        posts.forEach(post => {
            const row = document.createElement('tr');
            
            // ID column
            const idCell = document.createElement('td');
            idCell.textContent = post.id; // Truncate long IDs
            row.appendChild(idCell);
            
            // Caption column
            const captionCell = document.createElement('td');
            captionCell.textContent = post.caption;
            row.appendChild(captionCell);
            
            // Category column with dropdown
            const categoryCell = document.createElement('td');
            const select = document.createElement('select');
            select.className = 'category-select';
            select.setAttribute('data-id', post.id);
            
            const categories = ['Branding', 'Informative', 'Moment Marketing', 'Topical', 'Patient Testimonial'];
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                if (post.category === category) option.selected = true;
                select.appendChild(option);
            });
            
            select.addEventListener('change', (e) => {
                const postId = e.target.getAttribute('data-id');
                const newCategory = e.target.value;
                const postIndex = mockData.findIndex(p => p.id === postId);
                if (postIndex !== -1) {
                    mockData[postIndex].category = newCategory;
                }
            });
            
            categoryCell.appendChild(select);
            row.appendChild(categoryCell);
            
            // Metrics columns
            const metrics = ['views', 'likes', 'comments', 'reach', 'shares', 'saved'];
            metrics.forEach(metric => {
                const cell = document.createElement('td');
                cell.textContent = post[metric] || 0;
                row.appendChild(cell);
            });
            
            container.appendChild(row);
        });
    }

    // Function to convert array data to CSV
    function arrayToCSV(data, headers) {
        const csvRows = [];
        
        // Add headers
        csvRows.push(headers.join(','));
        
        // Add rows with proper zero handling
        data.forEach(item => {
            const values = headers.map(header => {
                const key = header.toLowerCase().replace(/ /g, '_');
                // Handle zero values explicitly
                const value = (item[key] === 0) ? 0 : (item[key] || '');
                return `"${String(value).replace(/"/g, '""')}"`;
            });
            csvRows.push(values.join(','));
        });
        
        return csvRows.join('\n');
    }

    // Function to trigger CSV download
    function downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Export post data
    function exportPostData() {
        const postHeaders = ['ID', 'Caption', 'Category', 'Views', 'Likes', 'Comments', 'Reach', 'Shares', 'Saved'];
        const postData = mockData.map(post => ({
            id: post.id,
            caption: post.caption,
            category: post.category,
            views: post.views || 0,
            likes: post.likes || 0,
            comments: post.comments || 0,
            reach: post.reach || 0,
            shares: post.shares !== undefined ? post.shares : 0,
            saved: post.saved !== undefined ? post.saved : 0
        }));
        
        const csv = arrayToCSV(postData, postHeaders);
        downloadCSV(csv, 'instagram_posts.csv');
    }

    // Export analysis data
    function exportAnalysisData() {
        if (currentAnalysisData.length === 0) {
            alert('Please analyze the data first before exporting.');
            return;
        }
        
        const analysisHeaders = ['Goal', 'Top Category', 'Top Total', 'Second Category', 'Second Total', 'Post Count'];
        const csv = arrayToCSV(currentAnalysisData, analysisHeaders);
        downloadCSV(csv, 'instagram_analysis.csv');
    }

    // Render analytics chart
    function renderAnalytics(analytics) {
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        
        // Destroy previous chart if exists
        if (analyticsChart) {
            analyticsChart.destroy();
        }

        analyticsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(analytics),
                datasets: [
                    {
                        label: 'Avg Views',
                        data: Object.keys(analytics).map(cat => analytics[cat].avg_views),
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    },
                    {
                        label: 'Avg Likes',
                        data: Object.keys(analytics).map(cat => analytics[cat].avg_likes),
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Analyze button click handler
    document.getElementById('analyze-btn').addEventListener('click', function() {
        currentAnalytics = generateAnalytics(mockData);
        const analysisBody = document.getElementById('analysis-body');
        analysisBody.innerHTML = '';

        currentAnalysisData = [];

        // 1. Highest Views
        const [topViewsCat, secondViewsCat] = getTopTwoCategories(currentAnalytics, 'total_views');
        analysisBody.innerHTML += `
            <tr>
                <td>Increase Views</td>
                <td class="top-category">${topViewsCat}</td>
                <td>${currentAnalytics[topViewsCat].total_views}</td>
                <td class="second-category">${secondViewsCat}</td>
                <td>${currentAnalytics[secondViewsCat].total_views}</td>
                <td>${currentAnalytics[topViewsCat].total_posts} | ${currentAnalytics[secondViewsCat].total_posts}</td>
            </tr>
        `;
        currentAnalysisData.push({
            goal: "Increase Views",
            top_category: topViewsCat,
            top_total: currentAnalytics[topViewsCat].total_views,
            second_category: secondViewsCat,
            second_total: currentAnalytics[secondViewsCat].total_views,
            post_count: `${currentAnalytics[topViewsCat].total_posts} | ${currentAnalytics[secondViewsCat].total_posts}`
        });

        // 2. Highest Reach
        const [topReachCat, secondReachCat] = getTopTwoCategories(currentAnalytics, 'total_reach');
        analysisBody.innerHTML += `
            <tr>
                <td>Increase Reach</td>
                <td class="top-category">${topReachCat}</td>
                <td>${currentAnalytics[topReachCat].total_reach}</td>
                <td class="second-category">${secondReachCat}</td>
                <td>${currentAnalytics[secondReachCat].total_reach}</td>
                <td>${currentAnalytics[topReachCat].total_posts} | ${currentAnalytics[secondReachCat].total_posts}</td>
            </tr>
        `;
        currentAnalysisData.push({
            goal: "Increase Reach",
            top_category: topReachCat,
            top_total: currentAnalytics[topReachCat].total_reach,
            second_category: secondReachCat,
            second_total: currentAnalytics[secondReachCat].total_reach,
            post_count: `${currentAnalytics[topReachCat].total_posts} | ${currentAnalytics[secondReachCat].total_posts}`
        });

        // 3. Highest Reactions (Likes + Saves)
        const [topReactionsCat, secondReactionsCat] = getTopTwoCategories(currentAnalytics, 'total_likes');
        analysisBody.innerHTML += `
            <tr>
                <td>Increase Reactions</td>
                <td class="top-category">${topReactionsCat}</td>
                <td>${currentAnalytics[topReactionsCat].total_likes + currentAnalytics[topReactionsCat].total_saved}</td>
                <td class="second-category">${secondReactionsCat}</td>
                <td>${currentAnalytics[secondReactionsCat].total_likes + currentAnalytics[secondReactionsCat].total_saved}</td>
                <td>${currentAnalytics[topReactionsCat].total_posts} | ${currentAnalytics[secondReactionsCat].total_posts}</td>
            </tr>
        `;
        currentAnalysisData.push({
            goal: "Increase Reactions",
            top_category: topReactionsCat,
            top_total: currentAnalytics[topReactionsCat].total_likes + currentAnalytics[topReactionsCat].total_saved,
            second_category: secondReactionsCat,
            second_total: currentAnalytics[secondReactionsCat].total_likes + currentAnalytics[secondReactionsCat].total_saved,
            post_count: `${currentAnalytics[topReactionsCat].total_posts} | ${currentAnalytics[secondReactionsCat].total_posts}`
        });

        // 4. Highest Comments
        const [topCommentsCat, secondCommentsCat] = getTopTwoCategories(currentAnalytics, 'total_comments');
        analysisBody.innerHTML += `
            <tr>
                <td>Increase Comments</td>
                <td class="top-category">${topCommentsCat}</td>
                <td>${currentAnalytics[topCommentsCat].total_comments}</td>
                <td class="second-category">${secondCommentsCat}</td>
                <td>${currentAnalytics[secondCommentsCat].total_comments}</td>
                <td>${currentAnalytics[topCommentsCat].total_posts} | ${currentAnalytics[secondCommentsCat].total_posts}</td>
            </tr>
        `;
        currentAnalysisData.push({
            goal: "Increase Comments",
            top_category: topCommentsCat,
            top_total: currentAnalytics[topCommentsCat].total_comments,
            second_category: secondCommentsCat,
            second_total: currentAnalytics[secondCommentsCat].total_comments,
            post_count: `${currentAnalytics[topCommentsCat].total_posts} | ${currentAnalytics[secondCommentsCat].total_posts}`
        });

        // 5. Highest Shares
        const [topSharesCat, secondSharesCat] = getTopTwoCategories(currentAnalytics, 'total_shares');
        analysisBody.innerHTML += `
            <tr>
                <td>Increase Shares</td>
                <td class="top-category">${topSharesCat}</td>
                <td>${currentAnalytics[topSharesCat].total_shares}</td>
                <td class="second-category">${secondSharesCat}</td>
                <td>${currentAnalytics[secondSharesCat].total_shares}</td>
                <td>${currentAnalytics[topSharesCat].total_posts} | ${currentAnalytics[secondSharesCat].total_posts}</td>
            </tr>
        `;
        currentAnalysisData.push({
            goal: "Increase Shares",
            top_category: topSharesCat,
            top_total: currentAnalytics[topSharesCat].total_shares,
            second_category: secondSharesCat,
            second_total: currentAnalytics[secondSharesCat].total_shares,
            post_count: `${currentAnalytics[topSharesCat].total_posts} | ${currentAnalytics[secondSharesCat].total_posts}`
        });

        // Render analytics chart
        renderAnalytics(currentAnalytics);
    });

    // Set up event listeners
    document.getElementById('export-posts').addEventListener('click', exportPostData);
    document.getElementById('export-analysis').addEventListener('click', exportAnalysisData);

    // Initial render
    document.addEventListener('DOMContentLoaded', function() {
        renderPosts(mockData);
    });
</script>
@endsection
