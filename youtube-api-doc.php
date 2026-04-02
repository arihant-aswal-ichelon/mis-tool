*Overall data*
curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?endDate=2024-01-09&ids=channel%3D%3DUCSp_lA2byOThn_Oql-KEMFA&metrics=views%2Ccomments%2Clikes%2Cdislikes%2CestimatedMinutesWatched%2CaverageViewDuration&sort=-estimatedMinutesWatched&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

*Most watched top 10*
curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=video&endDate=2024-01-09&ids=channel%3D%3DUCSp_lA2byOThn_Oql-KEMFA&maxResults=10&metrics=estimatedMinutesWatched%2Cviews%2Clikes%2CsubscribersGained&sort=-estimatedMinutesWatched&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

*Multiple Video Data*
curl \
  'https://youtube.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&id=RsoBdtfa-Zs%2ClCng_owEmLY%2Chb_GMWpuCLY&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

*Video Statistics By Video ID*  
curl \
'https://youtube.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&id=Ks-_Mh1QhMc&key=[YOUR_API_KEY]' \
--header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
--header 'Accept: application/json' \
--compressed

*Video By Video ID*  
curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?endDate=2024-01-10&filters=video%3D%3DlCng_owEmLY&ids=channel%3D%3DMINE&metrics=views%2Ccomments%2Clikes%2Cdislikes%2CestimatedMinutesWatched%2CaverageViewDuration&startDate=2024-01-01&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed


==============================
*Traffic Source*
==============================

*Overall*
curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceType&endDate=2024-01-20&ids=channel%3D%3DMINE&metrics=views%2CestimatedMinutesWatched%2CaverageViewDuration&startDate=2024-01-01&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

*External*
curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceDetail&endDate=2024-01-09&filters=insightTrafficSourceType%3D%3DEXT_URL&ids=channel%3D%3DUCSp_lA2byOThn_Oql-KEMFA&maxResults=10&metrics=views%2CestimatedMinutesWatched&sort=-views&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

*YouTube Search*
curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceDetail&endDate=2024-01-09&filters=insightTrafficSourceType%3D%3DYT_SEARCH&ids=channel%3D%3DUCSp_lA2byOThn_Oql-KEMFA&maxResults=10&metrics=views%2CestimatedMinutesWatched&sort=-views&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

*HashTag*
curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceDetail&endDate=2024-01-09&filters=insightTrafficSourceType%3D%3DHASHTAGS&ids=channel%3D%3DUCSp_lA2byOThn_Oql-KEMFA&maxResults=10&metrics=views%2CestimatedMinutesWatched&sort=-views&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

*Subscriber*
curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceDetail&endDate=2024-01-09&filters=insightTrafficSourceType%3D%3DSUBSCRIBER&ids=channel%3D%3DUCSp_lA2byOThn_Oql-KEMFA&maxResults=10&metrics=views%2CestimatedMinutesWatched%2CaverageViewDuration&sort=-estimatedMinutesWatched&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

*OTHER PAGE*
curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceDetail&endDate=2024-01-09&filters=insightTrafficSourceType%3D%3DYT_OTHER_PAGE&ids=channel%3D%3DUCSp_lA2byOThn_Oql-KEMFA&maxResults=10&metrics=views%2CestimatedMinutesWatched%2CaverageViewDuration&sort=-estimatedMinutesWatched&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

*Sound Page*
curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=insightTrafficSourceDetail&endDate=2024-01-09&filters=insightTrafficSourceType%3D%3DSOUND_PAGE&ids=channel%3D%3DUCSp_lA2byOThn_Oql-KEMFA&maxResults=10&metrics=views%2CestimatedMinutesWatched%2CaverageViewDuration&sort=-estimatedMinutesWatched&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

==============================
*Geography*
==============================

curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=country&endDate=2024-01-20&ids=channel%3D%3DMINE&metrics=views%2Ccomments%2Clikes%2Cdislikes%2Cshares%2CestimatedMinutesWatched%2CaverageViewDuration%2CsubscribersGained%2CsubscribersLost&sort=-views&startDate=2024-01-01&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed


==============================
*Gender*
==============================

curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=ageGroup&endDate=2024-01-09&ids=channel%3D%3DMINE&metrics=viewerPercentage&sort=ageGroup&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=gender&endDate=2024-01-09&ids=channel%3D%3DMINE&metrics=viewerPercentage&sort=gender&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

==============================
*Time Based*
==============================

curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=day&endDate=2024-01-10&ids=channel%3D%3DMINE&metrics=views%2CestimatedMinutesWatched%2CaverageViewDuration%2CaverageViewPercentage%2CsubscribersGained&sort=day&startDate=2024-01-01&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

==============================
*Social*
==============================

curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=sharingService&endDate=2024-01-09&ids=channel%3D%3DMINE&metrics=shares&sort=-shares&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

==============================
*Audience Retention*
==============================

curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=elapsedVideoTimeRatio&endDate=2024-01-09&filters=video%3D%3DRsoBdtfa-Zs%3BaudienceType%3D%3DORGANIC&ids=channel%3D%3DMINE&metrics=audienceWatchRatio%2CrelativeRetentionPerformance&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

==============================
*Basic playlist stats*
==============================
*Overall*
curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?endDate=2024-01-09&filters=isCurated%3D%3D1&ids=channel%3D%3DMINE&metrics=playlistStarts%2CestimatedMinutesWatched%2Cviews%2CviewsPerPlaylistStart&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

*Top 10*
curl \
'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=playlist&endDate=2024-01-10&ids=channel%3D%3DMINE&maxResults=10&metrics=views%2CestimatedMinutesWatched%2CplaylistStarts%2CaverageTimeInPlaylist&sort=-estimatedMinutesWatched&startDate=2024-01-01&key=[YOUR_API_KEY]' \
--header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
--header 'Accept: application/json' \
--compressed

*Playlist Data by ID*
curl \
  'https://youtube.googleapis.com/youtube/v3/playlists?part=snippet%2CcontentDetails&id=PLSWqmPVymYubzfOurAesRiGatL4R7OxQH&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed


==============================
*Channel playlist*
==============================

curl \
  'https://youtube.googleapis.com/youtube/v3/playlists?part=snippet%2CcontentDetails&channelId=UCSp_lA2byOThn_Oql-KEMFA&maxResults=25&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

==============================
*Channel Data*
==============================

curl \
  'https://youtube.googleapis.com/youtube/v3/channels?part=snippet%2CcontentDetails%2Cstatistics&id=UCSp_lA2byOThn_Oql-KEMFA&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

==============================
*Playlists Wise Data*
==============================

curl \
  'https://youtubeanalytics.googleapis.com/v2/reports?dimensions=playlist&endDate=2024-01-09&filters=isCurated%3D%3D1&ids=channel%3D%3DMINE&maxResults=10&metrics=views%2CestimatedMinutesWatched%2CaverageViewDuration&sort=-views&startDate=2023-12-13&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

==============================
*Activities with pagination*
==============================

curl \
  'https://youtube.googleapis.com/youtube/v3/activities?part=snippet&part=contentDetails&channelId=UCSp_lA2byOThn_Oql-KEMFA&maxResults=100&pageToken=CMgBEAA&key=[YOUR_API_KEY]' \
  --header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
  --header 'Accept: application/json' \
  --compressed

==============================
*Items by Playlist*
==============================

curl \
'https://youtube.googleapis.com/youtube/v3/playlistItems?part=snippet&part=contentDetails&part=status&playlistId=PLSWqmPVymYua9sFNG5b3nTCV614IcoOhO&key=[YOUR_API_KEY]' \
--header 'Authorization: Bearer [YOUR_ACCESS_TOKEN]' \
--header 'Accept: application/json' \
--compressed