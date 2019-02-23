<?php

$pt->page        = 'home';
$pt->title       = $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pro_users       = array();
$pro_system      = ($pt->config->go_pro == 'on');

$pt->page_url_ = $pt->config->site_url;

$limit = ($pt->theme_using == 'default') ? 10 : 4;

$featured_list = '';

$featued_data = $db->where('featured', '1')->where('privacy', 0)->orderBy('RAND()')->get(T_VIDEOS, 3);

if (empty($featued_data)) {

    $db->where('converted', '2','<>');
    $featued_data = $db->where('privacy', 0)->orderBy('id', 'DESC')->get(T_VIDEOS, 3);
}

if (empty($featued_data)) {
    $pt->content = PT_LoadPage('home/no-content');
    return;
}

foreach ($featued_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $featured_list .= PT_LoadPage('home/featuredVideo', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'DESC' => $video->markup_description,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'SUBSCIBE_BUTTON' => PT_GetSubscribeButton($video->owner->id),
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration,
        'VIDEO_ID' => $video->video_id_,
        'VIDEO_ID_' => PT_Slug($video->title, $video->video_id)
    ));
}


$db->where('converted', '2','<>');
$video_obj = $db->where('featured', '1')->where('privacy', 0)->orderBy('RAND()')->getOne(T_VIDEOS);
$get_video = PT_GetVideoByID($video_obj, 0, 1, 0);

if (empty($get_video)) {

    $db->where('converted', '2','<>');
    $get_video = PT_GetVideoByID($db->where('privacy', 0)->orderBy('id', 'DESC')->getOne(T_VIDEOS), 0, 1, 0);
}

if (empty($get_video)) {
    $pt->content = PT_LoadPage('home/no-content');
    return;
}

$user_data   = $get_video->owner;
$save_button = '<i class="fa fa-floppy-o fa-fw"></i> ' . $lang->save;
$is_saved    = 0;


if (IS_LOGGED == true) {
    $db->where('video_id', $get_video->id);
    $db->where('user_id', $user->id);
    $is_saved = $db->getValue(T_SAVED, "count(*)");
}

if ($is_saved > 0) {
    $save_button = '<i class="fa fa-check fa-fw"></i> ' . $lang->saved;
}

$trending_list = '';

if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('time', time() - 172800, '>');
    $db->where('privacy', 0);
    $trending_data = $db->orderBy('views', 'DESC')->get(T_VIDEOS, $limit);
}

if (empty($trending_data)) {
    $db->where('time', time() - 172800, '>');
    $db->where('privacy', 0);
    $trending_data = $db->orderBy('views', 'DESC')->get(T_VIDEOS, $limit);
} 

foreach ($trending_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $trending_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration,
        'VIDEO_ID' => $video->video_id_,
        'VIDEO_ID_' => PT_Slug($video->title, $video->video_id)
    ));
}

$top_list = '';

if (!empty($pro_users)){
    $db->where('user_id', $pro_users, 'IN');
    $db->where('privacy', 0);
    $db->orderBy('views', 'DESC');
    $top_data = $db->get(T_VIDEOS, 4);
}

if (empty($top_data)) {
    $db->where('privacy', 0);
    $top_data = $db->orderBy('views', 'DESC')->get(T_VIDEOS, $limit);
}

foreach ($top_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $top_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration,
        'VIDEO_ID' => $video->video_id_,
        'VIDEO_ID_' => PT_Slug($video->title, $video->video_id)
    ));
}

$latest_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('privacy', 0);
    $db->orderBy('id', 'DESC');
    $latest_data = $db->get(T_VIDEOS, $limit);
}

if (empty($latest_data)) {
    $db->where('privacy', 0);
    $latest_data = $db->orderBy('id', 'DESC')->get(T_VIDEOS, $limit);
}

foreach ($latest_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $latest_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration,
        'VIDEO_ID' => $video->video_id_,
        'VIDEO_ID_' => PT_Slug($video->title, $video->video_id)
    ));
}

$video_categories_html = '';

foreach ($categories as $cat_key => $cat_name) {
    $db->where('category_id', "'".$cat_key."'");
    $db->where('privacy', 0);
    $db->orderBy('id', 'DESC');
    $pt->cat_videos = $db->get(T_VIDEOS, 8);
    if (!empty($pt->cat_videos)) {
        $video_categories_html .= PT_LoadPage('home/categories',array(
            'CATEGORY_ONE_NAME' => $cat_name,
            'CATEGORY_ONE_ID' => $cat_key
        ));
    }
}
$pt->video_240 = 0;
$pt->video_360 = 0;
$pt->video_480 = 0;
$pt->video_720 = 0;

if ($pt->config->ffmpeg_system == 'on') {
    $explode_video = explode('_video', $get_video->video_location);
    if ($get_video->{"240p"} == 1) {
        $pt->video_240 = $explode_video[0] . '_video_240p_converted.mp4';
    }
    if ($get_video->{"360p"} == 1) {
        $pt->video_360 = $explode_video[0] . '_video_360p_converted.mp4';
    }
    if ($get_video->{"480p"} == 1) {
        $pt->video_480 = $explode_video[0] . '_video_480p_converted.mp4';
    }
    if ($get_video->{"720p"} == 1) {
        $pt->video_720 = $explode_video[0] . '_video_720p_converted.mp4';
    }
}

$pt->subscriptions = false;
$get_subscriptions_videos_html = '';
if (IS_LOGGED == true) {
    $get = $db->where('subscriber_id', $user->id)->get(T_SUBSCRIPTIONS);
    $userids = array();
    foreach ($get as $key => $userdata) {
        $userids[] = $userdata->user_id;
    }
    $get_subscriptions_videos = false;
    $userids = implode(',', ToArray($userids));
    if (!empty($userids)) {
        $get_subscriptions_videos = $db->rawQuery("SELECT * FROM " . T_VIDEOS . " WHERE user_id IN ($userids) AND privacy = 0 ORDER BY `id` DESC LIMIT $limit");
    }
    if (!empty($get_subscriptions_videos)) {
        $pt->subscriptions = true;
        $pt->cat_videos = $get_subscriptions_videos;
        $get_subscriptions_videos_html = PT_LoadPage('home/categories',array(
            'CATEGORY_ONE_NAME' => $lang->subscriptions,
            'CATEGORY_ONE_ID' => 'subscriptions'
        ));
        
    }
}
$pt->content = PT_LoadPage('home/content', array(
    'FEATURED_LIST' => $featured_list,
    'TRENDING_LIST' => $trending_list,
    'TOP_LIST' => $top_list,
    'LATEST_LIST' => $latest_list,
    'HOME_PAGE_VIDEOS' => $video_categories_html,
    'SUBSC_HTML' => $get_subscriptions_videos_html,
    'VIDEO_ID_' => PT_Slug($get_video->title, $get_video->video_id)
));