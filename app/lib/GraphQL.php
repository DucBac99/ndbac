<?php

use ProxyTurn\Shoplike;
use ServerApi\CustomerServer;
use ProxyStatic\ProxyFB;


class GraphQL
{
  public static function get_account_cloudsb()
  {
    try {
      $client = new \GuzzleHttp\Client();
      $resp = $client->request('GET', 'https://cloudsb.net/accounts/get_api', [
        'query' => [
          'used_at' => 0,
          'group_id' => '5711',
          'accessToken' => 'zDchb4ecKmL1wtggdIhxAkIVD7tu1Ed94Fb2lW00',
          'max_job' => 50000,
        ]
      ]);

      $account = @json_decode($resp->getBody());
    } catch (Exception $ex) {
      return null;
    }

    if (!empty($account->data->cookie)) {
      return null;
    }

    $cookie = $account->cookie;
    $cookies = [];
    if ($cookie) {
      $dump = explode(";", $cookie);
      foreach ($dump as $key) {
        $item = trim($key);
        $cookies[] = $item;
      }
    }
    return join("; ", $cookies);
  }

  public static function get_uid($uid, $idname, $proxy = null)
  {
    $output = new stdClass;
    $output->node_id = '';
    $output->url = '';
    $output->__typename = '';
    $output->result = 0;
    $output->share_type = 0;
    $is_url = true;
    $url = $uid;
    $typename = 'Unknown';
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
      $url = "https://www.facebook.com/" . $uid;
      $is_url = false;
    }

    try {
      $resp = graphqlWebApi([
        "av" => "0",
        "__user" => "0",
        "__a" => "1",
        "__dyn" => "7xeUmwlEnwn8K2WnFw9-2i5U4e0yoW3q322aew9G2S0zU20xi3y4o11U1lVE4W0om78-0BE662y11xmfz83WwgEcHzoaEd82lwv89k2C1FwIw9i1uwZwlo5qfK6E28xe2Gew9O222SUbEaU2eUlwhE2FBx_y8179obodEGdwda3e0Lo4qifxe3u362-0z8",
        "__csr" => "gkgwx5hdq9kGZ8xszLnnmQB8iuhaENXgyEhh9VbKt6-8GFQbja58zCK8AiCGqUmDy4p12eyEN7wPhV998zAy9ubDG5UKiq9xe4A8yQ4Voqx50XxebhHxe48Xxe22588FU1Ko0Czg0Gy039m0VQ047o6vg1X831wvU2Kw39O4Dmsi00OwU0sbxdy60mS0OC0G8qwBw1RC0p6jl0fu0Jo0hJg0Hmbwh80Lzwr47J0Nw5KCo1C80gS80bAw6fw3680kSw7dw17Jw9a2i0j609vweK0bHxi0lre0giq320km0y81CE3ow5Rxa58",
        "__req" => "l",
        "__hs" => "19330.HYP:comet_loggedout_pkg.2.1.0.0.0",
        "dpr" => "2",
        "__ccg" => "EXCELLENT",
        "__rev" => "1006681080",
        "__s" => "s5m71j:hwi41y:nkf7lc",
        "__hsi" => "7173108377253628569",
        "__comet_req" => "15",
        "lsd" => "AVrVDi3tsCc",
        "jazoest" => "2972",
        "__spin_r" => "1006681080",
        "__spin_b" => "trunk",
        "__spin_t" => "1670119440",
        "fb_api_caller_class" => "RelayModern",
        "server_timestamps" => false,
        "doc_id" => "8509361782415206",
        "variables" => json_encode([
          "feedLocation" => "FEED_COMPOSER",
          "focusCommentID" => null,
          "goodwillCampaignId" => "",
          "goodwillCampaignMediaIds" => [],
          "goodwillContentType" => null,
          "params" => [
            "url" => $url
          ],
          "privacySelectorRenderLocation" => "COMET_COMPOSER",
          "renderLocation" => "composer_preview",
          "parentStoryID" => null,
          "scale" => 2,
          "useDefaultActor" => false,
          "shouldIncludeStoryAttachment" => false
        ]),
        "fb_api_req_friendly_name" => "ComposerLinkAttachmentPreviewQuery",
      ], $proxy);
      if (!is_object($resp)) {
        $array = explode('{"label":"', $resp);
        if (count($array) > 1) {
          $resp = json_decode(trim($array[0]));
        }
      }

      $json = json_decode($resp->data->link_preview->share_scrape_data);
      if (!empty($resp->data->link_preview->story)) {

        if ($is_url || $uid == $json->share_params[0]) {
          $typename = $resp->data->link_preview->story->__typename;
          $output->url = $resp->data->link_preview->story->comet_sections->attached_story_layout->story->comet_sections->metadata[0]->story->url;
          $output->node_id = $resp->data->link_preview->story->id;
        } else if (!empty($resp->data->link_preview->story_attachment->styles->attachment->style_infos)) {
          $typename = $resp->data->link_preview->story_attachment->styles->attachment->style_infos[0]->__typename;
          $output->url = $resp->data->link_preview->story_attachment->styles->attachment->style_infos[0]->fb_shorts_story->short_form_video_context->playback_video->permalink_url;
          $output->node_id = $resp->data->link_preview->story_attachment->styles->attachment->style_infos[0]->fb_shorts_story->id;
        }
      } else if (!empty($resp->data->link_preview->story_attachment)) {
        $typename = $resp->data->link_preview->story_attachment->styles->__typename;
        if (!empty($resp->data->link_preview->story_attachment->styles->meta) && count($resp->data->link_preview->story_attachment->styles->meta) > 0) {
          $meta = $resp->data->link_preview->story_attachment->styles->meta;
          if (in_array($meta[0], ["Public group", "Private group"])) {
            $typename = 'StoryAttachmentGroupShareStyleRenderer';
          }
        } else if (!empty($resp->data->link_preview->story_attachment->styles->attachment->media)) {
          $typename = $resp->data->link_preview->story_attachment->styles->attachment->media->__typename;
          $output->url = $resp->data->link_preview->story_attachment->styles->attachment->media->url;
          $output->node_id = $resp->data->link_preview->story_attachment->styles->attachment->media->creation_story->id;
        } else if (!empty($resp->data->link_preview->story_attachment->styles->attachment->target)) {
          $typename = $resp->data->link_preview->story_attachment->styles->attachment->target->__typename;
          $output->url = $resp->data->link_preview->story_attachment->styles->attachment->target->url;
        } else if (!empty($resp->data->link_preview->story_attachment->styles->attachment->style_infos)) {
          $typename = $resp->data->link_preview->story_attachment->styles->attachment->style_infos[0]->__typename;
          $output->url = $resp->data->link_preview->story_attachment->styles->attachment->style_infos[0]->fb_shorts_story->short_form_video_context->playback_video->permalink_url;
          $output->node_id = $resp->data->link_preview->story_attachment->styles->attachment->style_infos[0]->fb_shorts_story->id;
        }
      }

      if (!empty($json->share_type)) {
        $output->share_type = $json->share_type;
      }

      switch ($typename) {
        case 'StoryAttachmentAvatarStyleRenderer':
          $output->__typename = "User";
          break;
        case 'StoryAttachmentPageShareStyleRenderer':
          $output->__typename = "Page";
          break;
        case 'StoryAttachmentGroupShareStyleRenderer':
          $output->__typename = "Group";
          break;
        case 'StoryAttachmentShareSevereStyleRenderer':
          $output->__typename = "Unknown";
          break;
        case 'StoryAttachmentFBReelsStyleRenderer':
        case 'FBShortsShareAttachmentStyleInfo':
          $output->__typename = "Video";
          break;
        default:
          $output->__typename = $typename;
          break;
      }

      // print_r($resp->data->link_preview);


      if (in_array($output->__typename, ['Unknown', 'ExternalUrl'])) {
        $output->msg = "Facebook ID không tồn tại";
        return $output;
      }

      if (empty($output->url)) {
        if (!empty($resp->data->link_preview->story_attachment->styles->url)) {
          $output->url = $resp->data->link_preview->story_attachment->styles->url;
        } else if (!empty($resp->data->link_preview->url)) {
          $output->url = $resp->data->link_preview->url;
        } else if (strpos($json->share_params[0], '1000') !== false) {
          $output->url = "https://www.facebook.com/profile.php?id=" . $json->share_params[0];
        } else {
          $output->url = "https://www.facebook.com/profile.php?id=" . $json->share_params[0];
        }
      }

      $friends_likes = [];
      // $output->__typename = "PageProfile";
      if (strpos($json->share_params[0], '1000') !== false) {
        $pageReal = GraphQL::get_page_id_from_profile($json->share_params[0], $proxy);
        if (!empty($pageReal->profile_transparency_id)) {
          $output->__typename = "PageProfile";
        }

        $checkLikeBtn = GraphQL::ProfileCometTopAppSectionQuery($json->share_params[0], $proxy);

        if (!empty($checkLikeBtn->nav_collections)) {
          $friends_likes = array_filter($checkLikeBtn->nav_collections, function ($item) {
            return  strpos($item->url, 'friends_likes') !== false;
          });
        }
      }

      if (strpos($output->url, '/people/') !== false) {
        $output->url = "https://www.facebook.com/profile.php?id=" . $json->share_params[0];
      }

      if (!in_array($output->__typename, ["User", "Page", "PageProfile"]) && strpos($idname, 'vip') !== false) {
        $output->msg = "Tính năng chỉ áp dụng cho các ID thuộc nhóm User, Page, PageProfile!";
      } else if (!in_array($output->__typename, ["Story", "Photo", "Media", "Video"]) && in_array($idname, ["buff-likepost", "buff-share", "buff-comment"])) {
        $output->msg = "Facebook ID nhập vào phải là Story, Photo, Media, Video";
      } else if (!in_array($output->__typename, ["Group"]) && $idname == "buff-member") {
        $output->msg = "Facebook ID nhập vào phải là Group";
      } else if (!in_array($output->__typename, ["User", "PageProfile"]) && in_array($idname, ["buff-follow"])) {
        $output->msg = "Facebook ID nhập vào phải là PageProfile, User";
      } else if (!in_array($output->__typename, ["Page", "PageProfile"]) && in_array($idname, ["buff-likepage"])) {
        $output->msg = "Đơn chỉ áp dụng Page";
      } else if (!in_array($output->__typename, ["User"]) && in_array($idname, ["buff-friends"])) {
        $output->msg = "Facebook ID nhập vào phải là User";
      } else if (in_array($output->__typename, ["PageProfile"]) && in_array($idname, ["buff-likepage"])) {
        if (count($friends_likes) == 0) {
          $output->msg = "Facebook ID không có nút like";
        } else {
          $output->result = 1;
          $output->id = $pageReal->profile_transparency_id;
          $output->msg = "Tìm thấy Facebook ID";
        }
      } else if (!empty($json->share_params)) {
        $output->result = 1;
        if ($is_url) {
          $output->id = $json->share_params[0];
        } else {
          $output->id = $uid;
        }
        $output->msg = "Tìm thấy Facebook ID";
      }
    } catch (Exception $ex) {
      $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
    }
    // print_r($output);
    return $output;
  }

  public static function get_info_story($postid, $nodeid, $url, $proxy = null)
  {
    $output = new stdClass;
    $output->result = 0;

    try {
      $resp = graphqlWebApi([
        "av" => "0",
        "__user" => "0",
        "__a" => "1",
        "__dyn" => "7xeUmwlEnwn8yEqxenFw9-2i5U4e2O14xt3odEc88EW0CEboG0x8bo6u3y4o0B-q1ew65xO2OU7m1xwEwgolzUO0-E4a3aUS2G3i0Bo7O2l0Fwqo31wnEfo5m1mzXxG1Pxi4UaEW0D888brwKxm5oe85nxmu3W0GpovUy0hOm2S3qazo3iwPwbS16AzUjwTwNwLwFg66",
        "__csr" => "gpl5NQah6B9b6jsTlR8D-Fb-lbKqHQyp9mKYGRy9k_KXJlGHXRCHvW_GrARUymczKHZ5KUGQcABoK4Sqq4GACzfm8RVoK9h7yAqjBBgkKHy8iiF3olQbzVoCdGGppHDGDG9pV9kRhHQ9Kuh4pGAy8lCyEqG2mcw8xeh02vd0goy3-q08Yw3Yrw1B2U5W0aOweS2q0bLxa0X8C2S58GqEIPA9g2_w40w9W682wxN02LPat2x00n-w0nCo1d404480QC2a0eDw1oK1f-3OqrAsWPd5gkP4BQ5V8y2lovl4y3o-Q2SoEmwj0asyajvNIyYhEs5oixjxGt0t-26UiwfytuK1syo7K0gkqsP80hMODIGQ2-0D8hG4sla-LrG1kaG9k8Gy5dDQzwE5jyF86u3btc2s5pBpEze9AKU553849xtw-wwxetg1f0WA58mQLwm1s510I0GOG4k5e7qjJZ1Om19wOxO4oCdg4K6u2KVo1jHKt2lDwb8ECeg9oyqubwADg4m0Co1zFkkwKuFm9zqwkk1fxl2o4Jo2lwA848Oq2K66124jx6Wz-8wwi-mm9wADgG5E62682T8it2FE4y2aEC5rwYwdiJ2oap84928voOya4A1FwQCh8DGi6U5G2m8CkXDlXK8Bxa58hg4YuR2g692Fk2G0Noljiy89Lde5IxOzU2C47m9DI-tOqBg28wLwwxwG-p2kqQncAsiEbFEZ5nFN6UR4gKxhBmqQg8bjTaN4mdcEhgk5io8o3eg1Fo8Fk1t-owkx6EK1qG3jy82cw8O3-0mi1Uw890Dw5_g7e0iW2636220je0WU4q7Gx3xCq0zU1Rm1HAo6a1b8u1Sw2vUfU1mEeE11OwRxK3u19wukuvBPWhlAACgxpVAVAi2R2US0SE6Y1ewjMY8jCD2orUlBgnwkUgwoVBDw5VhFQaBg1764o6mE2Bxki5Ua60gam11y8G4QEc89o1qouyFE3bt2AaCG0Y8cm",
        "__req" => "j",
        "__hs" => "19330.HYP:comet_loggedout_pkg.2.1.0.0.0",
        "dpr" => "2",
        "__ccg" => "EXCELLENT",
        "__rev" => "1006681242",
        "__s" => "hc6zeh:2rwvgi:1cuwg8",
        "__hsi" => "7173251747463172791",
        "__comet_req" => "15",
        "lsd" => "AVrVDi3tRc0",
        "jazoest" => "2920",
        "__spin_r" => "1006681242",
        "__spin_b" => "trunk",
        "__spin_t" => "1670152821",
        "fb_api_caller_class" => "RelayModern",
        "doc_id" => "5888410751230945",
        "variables" => '{"action_location":"feed","enable_comment_voting":true,"image_medium_height":2048,"enable_download":true,"image_high_height":2048,"question_poll_count":100,"fetch_available_comment_orderings":true,"image_large_aspect_height":376,"angora_attachment_profile_image_size":80,"fetch_privacy_value_for_declined_comment":true,"enable_ranked_replies":"true","default_image_scale":"2","enable_user_signals_in_comments":true,"angora_attachment_cover_image_size":960,"image_large_aspect_width":720,"image_high_width":720,"include_restriction_notice":true,"scale":"2","poll_facepile_size":80,"profile_pic_media_type":"image/x-auto","enable_cix_screen_rollout":true,"skip_work_info_fields":true,"image_low_width":240,"fetch_facts":true,"image_medium_width":360,"reading_attachment_profile_image_width":180,"should_fetch_share_count":"false","node_id":"' . $nodeid . '","nt_context":{"styles_id":"d1048a687f0ef671925a8da3e5258167","using_white_navbar":true,"pixel_ratio":2,"bloks_version":"9086e727db53f3d49dc3cd697df3875895d5ff65623b3fe0f143ced440921ef8"},"profile_image_size":80,"enable_comment_shares":true,"include_open_message_in_ufi":true,"size_style":"contain-fit","enable_composer_hint_plugins":true,"reading_attachment_profile_image_height":270,"enable_comment_reactions":true,"automatic_photo_captioning_enabled":"false","include_comment_depth":true,"fetch_whatsapp_ad_context":true,"fetch_is_discussion":true,"disable_friend_deep_dive":true,"enable_comment_reactions_icons":true,"image_low_height":2048,"enable_comment_replies_most_recent":"true","poll_voters_count":5,"max_comment_replies":3,"max_comments":10,"enable_prompt_post_composer_hint_plugin":true,"fetch_fbc_header":true,"include_image_ranges":true,"in_channel_eligibility_experiment":false,"media_type":"image/jpeg","skip_actor_story_status_fields":true,"should_fetch_reshare_filter_metadata":true}',
        "fb_api_req_friendly_name" => "StaticGraphQlStoryFeedbackQuery",
      ], $proxy);

      if (is_object($resp) && empty($resp->data->node)) {
        $output->msg = 'Không lấy được dữ liệu ban đầu';
      } else if (!empty($resp->data->node->feedback)) {
        $output->result = 1;
        $output->reaction_count = $resp->data->node->feedback->reactors->count;
        $output->share_count = $resp->data->node->feedback->reshares->count;
        $output->comment_count = $resp->data->node->feedback->top_level_comments->total_count;
        return $output;
      }

      $resp = curlWebApi($url, array(), 'GET', $proxy);
      preg_match_all('/{__bbox:{complete:true,(.*?),extra_context:null}}/i', $resp, $bbox);
      if (count($bbox) > 0) {
        $count = count($bbox[0]);
        for ($i = 0; $i < $count; $i++) {
          if (strpos($bbox[0][$i], strval($postid)) !== false) {
            preg_match('/reaction_count:{count:(.*?)}/i', $bbox[0][$i], $reaction);
            $output->reaction_count = intval($reaction[1]);

            preg_match('/comment_count:{total_count:(.*?)}/i', $bbox[0][$i], $comment);
            $output->comment_count = intval($comment[1]);

            preg_match('/share_count:{count:(.*?)}/i', $bbox[0][$i], $share);
            $output->share_count = intval($share[1]);
            $output->result = 1;
            return $output;
          }
        }
      }

      preg_match('/{"__bbox":{"complete":false,"result":{"label":"GroupsCometLoggedOutPermalinkFeed_pagination(.*?),"extra_context":null}}/i', $resp, $bbox2);
      if (count($bbox2) > 0) {
        $json = json_decode($bbox2[0]);
        if (!empty($json->__bbox->result->data->node->comet_sections->feedback->story->feedback_context->feedback_target_with_context->ufi_renderer->feedback->comet_ufi_summary_and_actions_renderer->feedback)) {
          $feedback = $json->__bbox->result->data->node->comet_sections->feedback->story->feedback_context->feedback_target_with_context->ufi_renderer->feedback->comet_ufi_summary_and_actions_renderer->feedback;
          $output->reaction_count = $feedback->reaction_count->count;
          $output->comment_count = $feedback->comment_count->total_count;
          $output->share_count = $feedback->share_count->count;
          $output->result = 1;
          return $output;
        }
      }

      preg_match('/{"__bbox":{"complete":false,"result":{"label":"CometVideoHomeHeroUnit_story\$defer\$CometVideoHomeHeroUnitLeftBottomSection_video(.*?),"extra_context":null}}/i', $resp, $bbox2);
      if (count($bbox2) > 0) {
        $json = json_decode($bbox2[0]);
        if (!empty($json->__bbox->result->data->feedback)) {
          $feedback = $json->__bbox->result->data->feedback;
          $output->reaction_count = $feedback->reaction_count->count;
          $output->comment_count = $feedback->comment_count->total_count;
          $output->result = 1;
          return $output;
        }
      }
    } catch (Exception $ex) {
      $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
    }
    return $output;
  }

  public static function get_info_group($uid, $proxy = null)
  {
    $output = new stdClass;
    $output->result = 0;

    try {
      $resp = graphqlWebApi([
        "av" => "0",
        "__user" => "0",
        "__a" => "1",
        "__dyn" => "7xeUmwlEnwn8K2WnFw9-2i5U4e0yoW3q322aew9G2S0zU20xi3y4o11U1lVE4W0om78-0BE662y11xmfz83WwgEcHzoaEd82lwv89k2C1FwIw9i1uwZwlo5qfK6E28xe2Gew9O222SUbEaU2eUlwhE2FBx_y8179obodEGdwda3e0Lo4qifxe3u362-0z8",
        "__csr" => "gkgwx5hdq9kGZ8xszLnnmQB8iuhaENXgyEhh9VbKt6-8GFQbja58zCK8AiCGqUmDy4p12eyEN7wPhV998zAy9ubDG5UKiq9xe4A8yQ4Voqx50XxebhHxe48Xxe22588FU1Ko0Czg0Gy039m0VQ047o6vg1X831wvU2Kw39O4Dmsi00OwU0sbxdy60mS0OC0G8qwBw1RC0p6jl0fu0Jo0hJg0Hmbwh80Lzwr47J0Nw5KCo1C80gS80bAw6fw3680kSw7dw17Jw9a2i0j609vweK0bHxi0lre0giq320km0y81CE3ow5Rxa58",
        "__req" => "l",
        "__hs" => "19330.HYP:comet_loggedout_pkg.2.1.0.0.0",
        "dpr" => "2",
        "__ccg" => "EXCELLENT",
        "__rev" => "1006681080",
        "__s" => "s5m71j:hwi41y:nkf7lc",
        "__hsi" => "7173108377253628569",
        "__comet_req" => "15",
        "lsd" => "AVrVDi3tsCc",
        "jazoest" => "2972",
        "__spin_r" => "1006681080",
        "__spin_b" => "trunk",
        "__spin_t" => "1670119440",
        "fb_api_caller_class" => "RelayModern",
        "server_timestamps" => false,
        "doc_id" => "4064456966986584",
        "variables" => '{"image_high_height":2048,"group_composer_render_location":"group_mall","image_low_height":2048,"cover_photo_width":720,"image_medium_width":360,"cover_image_navbar_size":64,"should_fetch_welcome_post_data":true,"media_type":"image/jpeg","should_use_new_pending_expert_field":true,"member_id":"' . $uid . '","image_medium_height":2048,"size_style":"contain-fit","image_high_width":720,"group_id":"' . $uid . '","default_image_scale":2,"nt_context":{"styles_id":"d1048a687f0ef671925a8da3e5258167","using_white_navbar":true,"pixel_ratio":2,"bloks_version":"9086e727db53f3d49dc3cd697df3875895d5ff65623b3fe0f143ced440921ef8"},"action_source":"GROUP_MALL","image_large_aspect_width":720,"should_show_pinned_topic_plink_nux":true,"image_large_aspect_height":376,"cover_photo_height":377,"profile_pic_media_type":"image/x-auto","scale":"2","should_defer_rooms_creation_nt_action":true,"image_low_width":240,"top_promo_nux_id":"7383","enable_expert_invite_recipient_flow":true,"should_fetch_action_intervention":true,"action_intervention_source":"GROUP_MALL","highlight_units_paginating_first":2}',
        "fb_api_req_friendly_name" => "FetchGroupInformation",
      ], $proxy);

      if (is_object($resp) && empty($resp->data->group_address)) {
        $output->msg = 'Không phải Group ID';
      } else {
        preg_match('/forum_member_profiles":{"count":(.*?)},/i', $resp, $total_count);
        if (isset($total_count[1])) {
          $output->result = 1;
          $output->member_count = intval($total_count[1]);
        } else {
          $output->msg = 'Không tìm thấy thông tin';
        }
      }
    } catch (Exception $ex) {
      $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
    }
    return $output;
  }

  public static function get_count_share_post($uid, $proxy = null)
  {

    $output = new stdClass;
    $output->result = 0;

    try {
      $resp = graphqlWebApi([
        "av" => "0",
        "__user" => "0",
        "__a" => "1",
        "__dyn" => "7xeUmwlEnwn8K2WnFw9-2i5U4e0yoW3q322aew9G2S0zU20xi3y4o11U1lVE4W0om78-0BE662y11xmfz83WwgEcHzoaEd82lwv89k2C1FwIw9i1uwZwlo5qfK6E28xe2Gew9O222SUbEaU2eUlwhE2FBx_y8179obodEGdwda3e0Lo4qifxe3u362-0z8",
        "__csr" => "gkgwx5hdq9kGZ8xszLnnmQB8iuhaENXgyEhh9VbKt6-8GFQbja58zCK8AiCGqUmDy4p12eyEN7wPhV998zAy9ubDG5UKiq9xe4A8yQ4Voqx50XxebhHxe48Xxe22588FU1Ko0Czg0Gy039m0VQ047o6vg1X831wvU2Kw39O4Dmsi00OwU0sbxdy60mS0OC0G8qwBw1RC0p6jl0fu0Jo0hJg0Hmbwh80Lzwr47J0Nw5KCo1C80gS80bAw6fw3680kSw7dw17Jw9a2i0j609vweK0bHxi0lre0giq320km0y81CE3ow5Rxa58",
        "__req" => "l",
        "__hs" => "19330.HYP:comet_loggedout_pkg.2.1.0.0.0",
        "dpr" => "2",
        "__ccg" => "EXCELLENT",
        "__rev" => "1006681080",
        "__s" => "s5m71j:hwi41y:nkf7lc",
        "__hsi" => "7173108377253628569",
        "__comet_req" => "15",
        "lsd" => "AVrVDi3tsCc",
        "jazoest" => "2972",
        "__spin_r" => "1006681080",
        "__spin_b" => "trunk",
        "__spin_t" => "1670119440",
        "fb_api_caller_class" => "RelayModern",
        "server_timestamps" => false,
        "doc_id" => "4810442689054977",
        "variables" => [
          "feedbackTargetID" => base64_encode('feedback:' . $uid)
        ],
        "fb_api_req_friendly_name" => "CometUFISharesCountTooltipContentQuery",
      ], $proxy);

      if (empty($resp->data->feedback)) {
        $output->msg = 'Không phải Story ID';
      } else {
        $output->result = 1;
        $output->reshares_count = intval($resp->data->feedback->reshares->count);
      }
    } catch (Exception $ex) {
      $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
    }
    return $output;
  }

  public static function get_count_comment_post($uid, $proxy = null)
  {
    $output = new stdClass;
    $output->result = 0;

    try {
      $resp = graphqlWebApi([
        "av" => "0",
        "__user" => "0",
        "__a" => "1",
        "__dyn" => "7xeUmwlEnwn8K2WnFw9-2i5U4e0yoW3q322aew9G2S0zU20xi3y4o11U1lVE4W0om78-0BE662y11xmfz83WwgEcHzoaEd82lwv89k2C1FwIw9i1uwZwlo5qfK6E28xe2Gew9O222SUbEaU2eUlwhE2FBx_y8179obodEGdwda3e0Lo4qifxe3u362-0z8",
        "__csr" => "gkgwx5hdq9kGZ8xszLnnmQB8iuhaENXgyEhh9VbKt6-8GFQbja58zCK8AiCGqUmDy4p12eyEN7wPhV998zAy9ubDG5UKiq9xe4A8yQ4Voqx50XxebhHxe48Xxe22588FU1Ko0Czg0Gy039m0VQ047o6vg1X831wvU2Kw39O4Dmsi00OwU0sbxdy60mS0OC0G8qwBw1RC0p6jl0fu0Jo0hJg0Hmbwh80Lzwr47J0Nw5KCo1C80gS80bAw6fw3680kSw7dw17Jw9a2i0j609vweK0bHxi0lre0giq320km0y81CE3ow5Rxa58",
        "__req" => "l",
        "__hs" => "19330.HYP:comet_loggedout_pkg.2.1.0.0.0",
        "dpr" => "2",
        "__ccg" => "EXCELLENT",
        "__rev" => "1006681080",
        "__s" => "s5m71j:hwi41y:nkf7lc",
        "__hsi" => "7173108377253628569",
        "__comet_req" => "15",
        "lsd" => "AVrVDi3tsCc",
        "jazoest" => "2972",
        "__spin_r" => "1006681080",
        "__spin_b" => "trunk",
        "__spin_t" => "1670119440",
        "fb_api_caller_class" => "RelayModern",
        "server_timestamps" => false,
        "doc_id" => "4934643733288410",
        "variables" => [
          "feedbackTargetID" => base64_encode('feedback:' . $uid)
        ],
        "fb_api_req_friendly_name" => "CometUFICommentsCountTooltipContentQuery",
      ], $proxy);

      if (empty($resp->data->feedback)) {
        $output->msg = 'Không phải Story ID';
      } else {
        $output->result = 1;
        $output->comments_count = intval($resp->data->feedback->top_level_comments->total_count);
      }
    } catch (Exception $ex) {
      $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
    }
    return $output;
  }

  public static function get_info_page($uid, $proxy = null)
  {
    $output = new stdClass;
    $output->result = 0;

    try {
      $resp = graphqlWebApi([
        "av" => "0",
        "__user" => "0",
        "__a" => "1",
        "__dyn" => "7xeUmwlEnwn8yEqxenFw9-2i5U4e2O14xt3odEc88EW0CEboG0x8bo6u3y4o0B-q1ew65xO2OU7m1xwEwgolzUO0-E4a3aUS2G3i0Bo7O2l0Fwqo31wnEfo5m1mzXxG1Pxi4UaEW0D888brwKxm5oe85nxmu3W0GpovUy0hOm2S3qazo3iwPwbS16AzUjwTwNwLwFg66",
        "__csr" => "gpl5NQah6B9b6jsTlR8D-Fb-lbKqHQyp9mKYGRy9k_KXJlGHXRCHvW_GrARUymczKHZ5KUGQcABoK4Sqq4GACzfm8RVoK9h7yAqjBBgkKHy8iiF3olQbzVoCdGGppHDGDG9pV9kRhHQ9Kuh4pGAy8lCyEqG2mcw8xeh02vd0goy3-q08Yw3Yrw1B2U5W0aOweS2q0bLxa0X8C2S58GqEIPA9g2_w40w9W682wxN02LPat2x00n-w0nCo1d404480QC2a0eDw1oK1f-3OqrAsWPd5gkP4BQ5V8y2lovl4y3o-Q2SoEmwj0asyajvNIyYhEs5oixjxGt0t-26UiwfytuK1syo7K0gkqsP80hMODIGQ2-0D8hG4sla-LrG1kaG9k8Gy5dDQzwE5jyF86u3btc2s5pBpEze9AKU553849xtw-wwxetg1f0WA58mQLwm1s510I0GOG4k5e7qjJZ1Om19wOxO4oCdg4K6u2KVo1jHKt2lDwb8ECeg9oyqubwADg4m0Co1zFkkwKuFm9zqwkk1fxl2o4Jo2lwA848Oq2K66124jx6Wz-8wwi-mm9wADgG5E62682T8it2FE4y2aEC5rwYwdiJ2oap84928voOya4A1FwQCh8DGi6U5G2m8CkXDlXK8Bxa58hg4YuR2g692Fk2G0Noljiy89Lde5IxOzU2C47m9DI-tOqBg28wLwwxwG-p2kqQncAsiEbFEZ5nFN6UR4gKxhBmqQg8bjTaN4mdcEhgk5io8o3eg1Fo8Fk1t-owkx6EK1qG3jy82cw8O3-0mi1Uw890Dw5_g7e0iW2636220je0WU4q7Gx3xCq0zU1Rm1HAo6a1b8u1Sw2vUfU1mEeE11OwRxK3u19wukuvBPWhlAACgxpVAVAi2R2US0SE6Y1ewjMY8jCD2orUlBgnwkUgwoVBDw5VhFQaBg1764o6mE2Bxki5Ua60gam11y8G4QEc89o1qouyFE3bt2AaCG0Y8cm",
        "__req" => "j",
        "__hs" => "19330.HYP:comet_loggedout_pkg.2.1.0.0.0",
        "dpr" => "2",
        "__ccg" => "EXCELLENT",
        "__rev" => "1006681242",
        "__s" => "hc6zeh:2rwvgi:1cuwg8",
        "__hsi" => "7173251747463172791",
        "__comet_req" => "15",
        "lsd" => "AVrVDi3tRc0",
        "jazoest" => "2920",
        "__spin_r" => "1006681242",
        "__spin_b" => "trunk",
        "__spin_t" => "1670152821",
        "fb_api_caller_class" => "RelayModern",
        "fb_api_req_friendly_name" => "PagesCometAboutRootQuery",
        "variables" => "{\"pageID\":\"" . $uid . "\",\"scale\":2}",
        "server_timestamps" => "true",
        "doc_id" => "5614157735330382"
      ], $proxy);

      if (is_object($resp) && empty($resp->data->page)) {
        $output->msg = 'Không lấy được dữ liệu ban đầu';
      } else {
        $output->result = 1;
        $output->follower_count = $resp->data->page->comet_page_about_tab->page->page_about_sections->page->follower_count;
        $output->liker_count = $resp->data->page->comet_page_about_tab->page->page_about_sections->page->page_likers->global_likers_count;
      }
    } catch (Exception $ex) {
      $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
    }
    return $output;
  }

  public static function get_info_user($uid, $typename, $proxy = null)
  {
    $output = new stdClass;
    $output->result = 0;


    if ($typename == "PageProfile") {
      try {
        $resp = GraphQL::get_page_id_from_profile($uid, $proxy);
        if (!empty($resp->profile_transparency_id)) {
          return GraphQL::get_info_page($resp->profile_transparency_id, $proxy);
        }
      } catch (Exception $ex) {
        $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
        return $output;
      }
    }

    try {
      $resp = GraphQL::get_info_node($uid, $proxy);
      if (!empty($resp->follower_count) && intval($resp->follower_count) > 0) {
        $output->result = 1;
        $output->follower_count = $resp->follower_count;
        return $output;
      }
    } catch (Exception $ex) {
      $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
    }

    $respCustomer = CustomerServer::get_start_num($uid, "buff-follow");
    if (!empty($respCustomer->start_num)) {
      $output->result = 1;
      $output->follower_count = $respCustomer->start_num;
      return $output;
    }

    $follower_count_text = -1;
    if (empty($output->follower_count) || intval($output->follower_count) == 0) {
      $cookie = GraphQL::get_account_cloudsb();
      try {
        $resp = graphqlWebApi([
          "av" => "0",
          "__user" => "0",
          "__a" => "1",
          "__dyn" => "7xeUmwlEnwn8yEqxenFw9-2i5U4e2O14xt3odEc88EW0CEboG0x8bo6u3y4o0B-q1ew65xO2OU7m1xwEwgolzUO0-E4a3aUS2G3i0Bo7O2l0Fwqo31wnEfo5m1mzXxG1Pxi4UaEW0D888brwKxm5oe85nxmu3W0GpovUy0hOm2S3qazo3iwPwbS16AzUjwTwNwLwFg66",
          "__csr" => "gpl5NQah6B9b6jsTlR8D-Fb-lbKqHQyp9mKYGRy9k_KXJlGHXRCHvW_GrARUymczKHZ5KUGQcABoK4Sqq4GACzfm8RVoK9h7yAqjBBgkKHy8iiF3olQbzVoCdGGppHDGDG9pV9kRhHQ9Kuh4pGAy8lCyEqG2mcw8xeh02vd0goy3-q08Yw3Yrw1B2U5W0aOweS2q0bLxa0X8C2S58GqEIPA9g2_w40w9W682wxN02LPat2x00n-w0nCo1d404480QC2a0eDw1oK1f-3OqrAsWPd5gkP4BQ5V8y2lovl4y3o-Q2SoEmwj0asyajvNIyYhEs5oixjxGt0t-26UiwfytuK1syo7K0gkqsP80hMODIGQ2-0D8hG4sla-LrG1kaG9k8Gy5dDQzwE5jyF86u3btc2s5pBpEze9AKU553849xtw-wwxetg1f0WA58mQLwm1s510I0GOG4k5e7qjJZ1Om19wOxO4oCdg4K6u2KVo1jHKt2lDwb8ECeg9oyqubwADg4m0Co1zFkkwKuFm9zqwkk1fxl2o4Jo2lwA848Oq2K66124jx6Wz-8wwi-mm9wADgG5E62682T8it2FE4y2aEC5rwYwdiJ2oap84928voOya4A1FwQCh8DGi6U5G2m8CkXDlXK8Bxa58hg4YuR2g692Fk2G0Noljiy89Lde5IxOzU2C47m9DI-tOqBg28wLwwxwG-p2kqQncAsiEbFEZ5nFN6UR4gKxhBmqQg8bjTaN4mdcEhgk5io8o3eg1Fo8Fk1t-owkx6EK1qG3jy82cw8O3-0mi1Uw890Dw5_g7e0iW2636220je0WU4q7Gx3xCq0zU1Rm1HAo6a1b8u1Sw2vUfU1mEeE11OwRxK3u19wukuvBPWhlAACgxpVAVAi2R2US0SE6Y1ewjMY8jCD2orUlBgnwkUgwoVBDw5VhFQaBg1764o6mE2Bxki5Ua60gam11y8G4QEc89o1qouyFE3bt2AaCG0Y8cm",
          "__req" => "j",
          "__hs" => "19330.HYP:comet_loggedout_pkg.2.1.0.0.0",
          "dpr" => "2",
          "__ccg" => "EXCELLENT",
          "__rev" => "1006681242",
          "__s" => "hc6zeh:2rwvgi:1cuwg8",
          "__hsi" => "7173251747463172791",
          "__comet_req" => "15",
          "lsd" => "AVrVDi3tRc0",
          "jazoest" => "2920",
          "__spin_r" => "1006681242",
          "__spin_b" => "trunk",
          "__spin_t" => "1670152821",
          "fb_api_caller_class" => "RelayModern",
          "doc_id" => "4456182097776797",
          "variables" => '{"narrow_portrait_height":"708","image_high_height":2048,"image_landscape_height":"470","featurable_content_height":240,"featurable_type_icon_scale":2,"automatic_photo_captioning_enabled":"false","supported_model_compression_types":[],"image_thumbnail_width":"232","fetch_gender":false,"reading_attachment_profile_image_width":180,"narrow_landscape_height":"351","image_high_width":720,"frame_scale":"2","skip_spherical_cover_photo_encoding_fields":false,"image_large_thumbnail_width":"470","profile_image_small_size":80,"image_low_height":2048,"fetch_things_in_common_protile":false,"image_medium_width":360,"should_fetch_header_bio_in_tabs":false,"profile_id":"' . $uid . '","image_portrait_height":"708","goodwill_small_accent_image":72,"story_card_cover_height":238,"fetch_hobbies":false,"large_portrait_height":"705","image_landscape_width":"708","show_profile_switcher":false,"enable_snoozed_button":false,"image_medium_height":2048,"profile_image_big_size_relative":360,"profile_social_graph_facepile_count":6,"profile_image_big_size":188,"profile_pic_media_type":"image/x-auto","scale":"2","featurable_content_width":240,"angora_attachment_profile_image_size":80,"image_large_aspect_height":376,"migration_cover_height":180,"should_fetch_reshare_filter_metadata":false,"fetch_fb_stories":false,"profile_scoped_search_null_state_enabled":false,"story_card_cover_width":238,"media_type":"image/x-auto","should_fetch_wem_private_sharing_params":false,"profile_image_size":80,"num_migration_covers":5,"show_admin_prompt":false,"image_portrait_width":"470","angora_attachment_cover_image_size":960,"render_username_for_profile":false,"image_large_aspect_width":720,"inspiration_capabilities":[],"thumbnail_width":120,"msqrd_supported_capabilities":[],"image_low_width":240,"profile_social_graph_facepile_size":64,"reading_attachment_profile_image_height":270,"group_categories_paginating_first":5,"supported_compression_types":[],"nt_context":{"styles_id":"d1048a687f0ef671925a8da3e5258167","using_white_navbar":true,"pixel_ratio":2,"bloks_version":"9086e727db53f3d49dc3cd697df3875895d5ff65623b3fe0f143ced440921ef8"},"profile_gysj_category_cover_size":262,"migration_cover_width":100,"enable_comment_reactions":false,"show_pplus_megaphone_qp":false,"default_image_scale":"2","device_type":"SM-G965N","enable_comment_reactions_icons":false,"fetch_birthday_data":false,"fetch_community_introduction_nux":false,"enable_frxentrypoint":false,"profile_gysj_large_cover_width":538,"fetch_fbc_header":false,"include_image_ranges":false,"fetch_profile_pic_hscroll_protile":false,"profile_pic_protile_card_size":272,"msqrd_instruction_image_height":200,"featured_highlights_paginating_first":5,"action_bar_render_location":"ANDROID_PROFILE","profile_gysj_mini_cover_height":153,"msqrd_instruction_image_width":200,"groups_you_should_join_paginating_first":5,"profile_gysj_large_cover_height":282,"show_room_indicator":false,"profile_gysj_mini_cover_width":292}',
          "fb_api_req_friendly_name" => "AndroidProfile",
        ], $proxy, $cookie);

        if (!is_object($resp)) {
          $array = explode('{"label":"FetchPrompt', $resp);
          if (count($array) > 1) {
            $resp = json_decode(trim($array[0]));
          }
        }

        if (is_object($resp) && empty($resp->data->user)) {
          $output->msg = 'Không lấy được dữ liệu ban đầu';
        } else if (!empty($resp->data->user->userWithoutTabs->profile_intro_card->context_items->edges)) {
          $edges = $resp->data->user->userWithoutTabs->profile_intro_card->context_items->edges;
          if ($edges && is_array($edges)) {
            for ($i = 0; $i < count($edges); $i++) {
              $element = $edges[$i];
              if (
                $element->node->timeline_context_list_item_type == "INTRO_CARD_FOLLOWERS"
              ) {
                // nhớ repoalce lấy number từ text
                $follower_count_text = $element->node->title->text;
                preg_match('!\d+!', str_replace(",", "", $follower_count_text), $matches);
                if (count($matches) > 0) {
                  $follower_count_text = $matches[0];
                }
              }
            }
          }
        }
      } catch (Exception $ex) {
        $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
      }
    }

    if ($follower_count_text == -1 || intval($follower_count_text) == 0) {
      $output->msg = 'Không lấy được dữ liệu ban đầu';
    } else {
      $output->result = 1;
      $output->follower_count = $follower_count_text;
    }
    return $output;
  }

  public static function get_info_node($uid, $proxy = null)
  {
    $output = new stdClass;
    $output->result = 0;

    try {
      $resp = graphqlWebApi([
        "av" => "0",
        "__user" => "0",
        "__a" => "1",
        "__dyn" => "7xeUmwlEnwn8yEqxenFw9-2i5U4e2O14xt3odEc88EW0CEboG0x8bo6u3y4o0B-q1ew65xO2OU7m1xwEwgolzUO0-E4a3aUS2G3i0Bo7O2l0Fwqo31wnEfo5m1mzXxG1Pxi4UaEW0D888brwKxm5oe85nxmu3W0GpovUy0hOm2S3qazo3iwPwbS16AzUjwTwNwLwFg66",
        "__csr" => "gpl5NQah6B9b6jsTlR8D-Fb-lbKqHQyp9mKYGRy9k_KXJlGHXRCHvW_GrARUymczKHZ5KUGQcABoK4Sqq4GACzfm8RVoK9h7yAqjBBgkKHy8iiF3olQbzVoCdGGppHDGDG9pV9kRhHQ9Kuh4pGAy8lCyEqG2mcw8xeh02vd0goy3-q08Yw3Yrw1B2U5W0aOweS2q0bLxa0X8C2S58GqEIPA9g2_w40w9W682wxN02LPat2x00n-w0nCo1d404480QC2a0eDw1oK1f-3OqrAsWPd5gkP4BQ5V8y2lovl4y3o-Q2SoEmwj0asyajvNIyYhEs5oixjxGt0t-26UiwfytuK1syo7K0gkqsP80hMODIGQ2-0D8hG4sla-LrG1kaG9k8Gy5dDQzwE5jyF86u3btc2s5pBpEze9AKU553849xtw-wwxetg1f0WA58mQLwm1s510I0GOG4k5e7qjJZ1Om19wOxO4oCdg4K6u2KVo1jHKt2lDwb8ECeg9oyqubwADg4m0Co1zFkkwKuFm9zqwkk1fxl2o4Jo2lwA848Oq2K66124jx6Wz-8wwi-mm9wADgG5E62682T8it2FE4y2aEC5rwYwdiJ2oap84928voOya4A1FwQCh8DGi6U5G2m8CkXDlXK8Bxa58hg4YuR2g692Fk2G0Noljiy89Lde5IxOzU2C47m9DI-tOqBg28wLwwxwG-p2kqQncAsiEbFEZ5nFN6UR4gKxhBmqQg8bjTaN4mdcEhgk5io8o3eg1Fo8Fk1t-owkx6EK1qG3jy82cw8O3-0mi1Uw890Dw5_g7e0iW2636220je0WU4q7Gx3xCq0zU1Rm1HAo6a1b8u1Sw2vUfU1mEeE11OwRxK3u19wukuvBPWhlAACgxpVAVAi2R2US0SE6Y1ewjMY8jCD2orUlBgnwkUgwoVBDw5VhFQaBg1764o6mE2Bxki5Ua60gam11y8G4QEc89o1qouyFE3bt2AaCG0Y8cm",
        "__req" => "j",
        "__hs" => "19330.HYP:comet_loggedout_pkg.2.1.0.0.0",
        "dpr" => "2",
        "__ccg" => "EXCELLENT",
        "__rev" => "1006681242",
        "__s" => "hc6zeh:2rwvgi:1cuwg8",
        "__hsi" => "7173251747463172791",
        "__comet_req" => "15",
        "lsd" => "AVrVDi3tRc0",
        "jazoest" => "2920",
        "__spin_r" => "1006681242",
        "__spin_b" => "trunk",
        "__spin_t" => "1670152821",
        "fb_api_caller_class" => "RelayModern",
        "q" => 'node(' . $uid . '){subscribers{count}}',
        "fb_api_req_friendly_name" => "NodeFollow",
      ], $proxy);

      if (is_object($resp) && empty($resp->{"$uid"})) {
        $output->msg = 'Không lấy được dữ liệu ban đầu';
      } else {
        $output->result = 1;
        $output->follower_count = $resp->{"$uid"}->subscribers->count;
      }
    } catch (Exception $ex) {
      $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
    }
    return $output;
  }

  public static function get_page_id_from_profile($pageid, $proxy = null)
  {
    $output = new stdClass;
    $output->result = 0;

    try {
      $resp = graphqlWebApi([
        "av" => "0",
        "__user" => "0",
        "__a" => "1",
        "__dyn" => "7xeUmwlEnwn8yEqxenFw9-2i5U4e2O14xt3odEc88EW0CEboG0x8bo6u3y4o0B-q1ew65xO2OU7m1xwEwgolzUO0-E4a3aUS2G3i0Bo7O2l0Fwqo31wnEfo5m1mzXxG1Pxi4UaEW0D888brwKxm5oe85nxmu3W0GpovUy0hOm2S3qazo3iwPwbS16AzUjwTwNwLwFg66",
        "__csr" => "gpl5NQah6B9b6jsTlR8D-Fb-lbKqHQyp9mKYGRy9k_KXJlGHXRCHvW_GrARUymczKHZ5KUGQcABoK4Sqq4GACzfm8RVoK9h7yAqjBBgkKHy8iiF3olQbzVoCdGGppHDGDG9pV9kRhHQ9Kuh4pGAy8lCyEqG2mcw8xeh02vd0goy3-q08Yw3Yrw1B2U5W0aOweS2q0bLxa0X8C2S58GqEIPA9g2_w40w9W682wxN02LPat2x00n-w0nCo1d404480QC2a0eDw1oK1f-3OqrAsWPd5gkP4BQ5V8y2lovl4y3o-Q2SoEmwj0asyajvNIyYhEs5oixjxGt0t-26UiwfytuK1syo7K0gkqsP80hMODIGQ2-0D8hG4sla-LrG1kaG9k8Gy5dDQzwE5jyF86u3btc2s5pBpEze9AKU553849xtw-wwxetg1f0WA58mQLwm1s510I0GOG4k5e7qjJZ1Om19wOxO4oCdg4K6u2KVo1jHKt2lDwb8ECeg9oyqubwADg4m0Co1zFkkwKuFm9zqwkk1fxl2o4Jo2lwA848Oq2K66124jx6Wz-8wwi-mm9wADgG5E62682T8it2FE4y2aEC5rwYwdiJ2oap84928voOya4A1FwQCh8DGi6U5G2m8CkXDlXK8Bxa58hg4YuR2g692Fk2G0Noljiy89Lde5IxOzU2C47m9DI-tOqBg28wLwwxwG-p2kqQncAsiEbFEZ5nFN6UR4gKxhBmqQg8bjTaN4mdcEhgk5io8o3eg1Fo8Fk1t-owkx6EK1qG3jy82cw8O3-0mi1Uw890Dw5_g7e0iW2636220je0WU4q7Gx3xCq0zU1Rm1HAo6a1b8u1Sw2vUfU1mEeE11OwRxK3u19wukuvBPWhlAACgxpVAVAi2R2US0SE6Y1ewjMY8jCD2orUlBgnwkUgwoVBDw5VhFQaBg1764o6mE2Bxki5Ua60gam11y8G4QEc89o1qouyFE3bt2AaCG0Y8cm",
        "__req" => "j",
        "__hs" => "19330.HYP:comet_loggedout_pkg.2.1.0.0.0",
        "dpr" => "2",
        "__ccg" => "EXCELLENT",
        "__rev" => "1006681242",
        "__s" => "hc6zeh:2rwvgi:1cuwg8",
        "__hsi" => "7173251747463172791",
        "__comet_req" => "15",
        "lsd" => "AVrVDi3tRc0",
        "jazoest" => "2920",
        "__spin_r" => "1006681242",
        "__spin_b" => "trunk",
        "__spin_t" => "1670152821",
        "fb_api_caller_class" => "RelayModern",
        "doc_id" => "2925235520878557",
        "variables" => '{"userID":"' . $pageid . '"}',
        "fb_api_req_friendly_name" => "profile_transparency_id",
      ], $proxy);
      if (is_object($resp) && empty($resp->data->user)) {
        $output->msg = 'Không lấy được dữ liệu ban đầu';
      } else {
        $output->result = 1;
        $output->profile_transparency_id = $resp->data->user->profile_transparency_id;
      }
    } catch (Exception $ex) {
      $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
    }
    return $output;
  }

  public static function ProfileCometTopAppSectionQuery($pageid, $proxy = null)
  {
    $output = new stdClass;
    $output->result = 0;

    try {
      $resp = graphqlWebApi([
        "av" => "0",
        "__user" => "0",
        "__a" => "1",
        "__dyn" => "7xeUmwlEnwn8yEqxenFw9-2i5U4e2O14xt3odEc88EW0CEboG0x8bo6u3y4o0B-q1ew65xO2OU7m1xwEwgolzUO0-E4a3aUS2G3i0Bo7O2l0Fwqo31wnEfo5m1mzXxG1Pxi4UaEW0D888brwKxm5oe85nxmu3W0GpovUy0hOm2S3qazo3iwPwbS16AzUjwTwNwLwFg66",
        "__csr" => "gpl5NQah6B9b6jsTlR8D-Fb-lbKqHQyp9mKYGRy9k_KXJlGHXRCHvW_GrARUymczKHZ5KUGQcABoK4Sqq4GACzfm8RVoK9h7yAqjBBgkKHy8iiF3olQbzVoCdGGppHDGDG9pV9kRhHQ9Kuh4pGAy8lCyEqG2mcw8xeh02vd0goy3-q08Yw3Yrw1B2U5W0aOweS2q0bLxa0X8C2S58GqEIPA9g2_w40w9W682wxN02LPat2x00n-w0nCo1d404480QC2a0eDw1oK1f-3OqrAsWPd5gkP4BQ5V8y2lovl4y3o-Q2SoEmwj0asyajvNIyYhEs5oixjxGt0t-26UiwfytuK1syo7K0gkqsP80hMODIGQ2-0D8hG4sla-LrG1kaG9k8Gy5dDQzwE5jyF86u3btc2s5pBpEze9AKU553849xtw-wwxetg1f0WA58mQLwm1s510I0GOG4k5e7qjJZ1Om19wOxO4oCdg4K6u2KVo1jHKt2lDwb8ECeg9oyqubwADg4m0Co1zFkkwKuFm9zqwkk1fxl2o4Jo2lwA848Oq2K66124jx6Wz-8wwi-mm9wADgG5E62682T8it2FE4y2aEC5rwYwdiJ2oap84928voOya4A1FwQCh8DGi6U5G2m8CkXDlXK8Bxa58hg4YuR2g692Fk2G0Noljiy89Lde5IxOzU2C47m9DI-tOqBg28wLwwxwG-p2kqQncAsiEbFEZ5nFN6UR4gKxhBmqQg8bjTaN4mdcEhgk5io8o3eg1Fo8Fk1t-owkx6EK1qG3jy82cw8O3-0mi1Uw890Dw5_g7e0iW2636220je0WU4q7Gx3xCq0zU1Rm1HAo6a1b8u1Sw2vUfU1mEeE11OwRxK3u19wukuvBPWhlAACgxpVAVAi2R2US0SE6Y1ewjMY8jCD2orUlBgnwkUgwoVBDw5VhFQaBg1764o6mE2Bxki5Ua60gam11y8G4QEc89o1qouyFE3bt2AaCG0Y8cm",
        "__req" => "j",
        "__hs" => "19330.HYP:comet_loggedout_pkg.2.1.0.0.0",
        "dpr" => "2",
        "__ccg" => "EXCELLENT",
        "__rev" => "1006681242",
        "__s" => "hc6zeh:2rwvgi:1cuwg8",
        "__hsi" => "7173251747463172791",
        "__comet_req" => "15",
        "lsd" => "AVrVDi3tRc0",
        "jazoest" => "2920",
        "__spin_r" => "1006681242",
        "__spin_b" => "trunk",
        "__spin_t" => "1670152821",
        "fb_api_caller_class" => "RelayModern",
        "doc_id" => "5471945729591144",
        "variables" => '{"UFI2CommentsProvider_commentsKey":"ProfileCometCollectionRootQuery","collectionToken":"' . base64_encode('app_collection:' . $pageid . ':2356318349:32') . '","displayCommentsContextEnableComment":true,"displayCommentsContextIsAdPreview":false,"displayCommentsContextIsAggregatedShare":false,"displayCommentsContextIsStorySet":false,"displayCommentsFeedbackContext":null,"feedbackSource":65,"feedLocation":"COMET_MEDIA_VIEWER","scale":1.5,"sectionToken":"' . base64_encode('app_section:' . $pageid . ':2356318349') . '","userID":"' . $pageid . '","__relay_internal__pv__FBReelsDisableBackgroundrelayprovider":false}',
        "fb_api_req_friendly_name" => "ProfileCometTopAppSectionQuery",
      ], $proxy);
      if (is_object($resp) && empty($resp->data->node->nav_collections->nodes)) {
        $output->msg = 'Không lấy được dữ liệu ban đầu';
      } else {
        $output->result = 1;
        $output->nav_collections = $resp->data->node->nav_collections->nodes;
      }
    } catch (Exception $ex) {
      $output->msg = "Hệ thống quá tải. Hãy thử lại lần nữa";
    }
    return $output;
  }
}
