*To get Profile ID*
https://www.instagram.com/web/search/topsearch/?query=jindalivfchandigarh

*Account based FB Page ID Data*
curl -i -X GET \
"https://graph.facebook.com/v22.0/me/accounts?access_token={access-token}"

*IG Account ID Data*
curl -i -X GET \
"https://graph.facebook.com/v22.0/{fb-page-id}?fields=instagram_business_account&access_token={access-token}"

*Overall data*
curl -i -X GET \
"https://graph.facebook.com/v22.0/{ig-user-id}?fields=id%2Cusername%2Cname%2Cprofile_picture_url%2Cwebsite%2Cbiography%2Cfollowers_count%2Cfollows_count%2Cmedia_count&access_token={access-token}"

*IG Media list Data*
curl -i -X GET \
"https://graph.facebook.com/v22.0/{ig-user-id}/media?fields=comments_count,alt_text,caption,like_count,media_type,media_url,media_product_type,thumbnail_url,timestamp,permalink,id&limit=1000&since=2025-01-01&until=2025-06-30&access_token={access-token}"

*IG Media based Data*
curl -i -X GET \
"https://graph.facebook.com/v22.0/{ig-media-id}?fields=id%2Cmedia_url%2Cmedia_type%2Ccaption%2Calt_text%2Cig_id%2Ctimestamp%2Ccomments%2Clike_count%2Ccomments_count%2Cmedia_product_type%2Cis_comment_enabled%2Cthumbnail_url&access_token={access-token}"

*IG Media based Insights*
curl -i -X GET \
"https://graph.facebook.com/v23.0/{ig-media-id}/insights?metric=reach%2Csaved%2Ccomments%2Clikes%2Cshares%2Ctotal_interactions%2Cviews&access_token={access-token}"

*IG Media based on Media ID Insights*
curl -i -X GET \
"https://graph.facebook.com/v23.0/{ig-media-id}?fields=is_comment_enabled,media_url,caption,alt_text,like_count,thumbnail_url,comments_count,media_type,media_product_type,timestamp,comments{id,like_count,media,text,timestamp,from,user,replies{like_count,text,timestamp,parent_id,user,replies}},shortcode&access_token={access-token}"

*IG Account based Insights*
curl -i -X GET \
 "https://graph.facebook.com/v23.0/{ig-user-id}/insights?period=day&metric=reach%2Ccomments%2Clikes%2Cviews%2Cshares%2Creplies%2Cprofile_links_taps%2Cfollows_and_unfollows&metric_type=total_value&access_token={access-token}"

*Get HashTag ID*
 curl -i -X GET \
 "https://graph.facebook.com/v23.0/ig_hashtag_search?user_id=17841449228961578&q=informative&access_token={access-token}"

*Get HashTag ID Name*
 curl -i -X GET \
 "https://graph.facebook.com/v23.0/17843727169026935?fields=name&access_token={access-token}"

*Get Recent Media by HashTagID*
 curl -i -X GET \
 "https://graph.facebook.com/v23.0/17843727169026935/recent_media?fields=caption%2Ccomments_count%2Clike_count%2Cmedia_type%2Cmedia_url%2Cpermalink%2Ctimestamp%2Cmedia_product_type&user_id=17841449228961578&access_token={access-token}"

*Get Top Media by HashTagID*
 curl -i -X GET \
 "https://graph.facebook.com/v23.0/17843727169026935/top_media?fields=caption%2Ccomments_count%2Clike_count%2Cmedia_type%2Cmedia_url%2Cpermalink%2Ctimestamp%2Cmedia_product_type&user_id=17841449228961578&access_token={access-token}"