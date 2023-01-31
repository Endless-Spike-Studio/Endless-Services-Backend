<?php

return [
    'game' => [
        'command' => [
            'not_found' => 'Command not found',
            'unavailable' => 'Command not available',
            'worked' => 'worked!',
            'no_permission' => 'Permission denied',
            'level_rate_failed_invalid_stars_value' => 'Failed: ":value" is invalid stars value',
            'level_rate_failed_invalid_face_value' => 'Failed: ":value" is invalid face value',
            'level_rate_success' => 'rated!',
            'level_rate_delete_success' => 'unrated!',
            'level_delete_success' => 'deleted!',
            'level_update_success' => 'updated!',
            'level_audio_track_update_failed_invalid_value' => 'Failed: ":value" is invalid audio track',
            'level_song_update_failed_custom_not_found' => 'Failed: custom song with id ":value" is not found'
        ],
        'error' => [
            'request_authorization_failed' => '[请求] 授权失败',
            'request_validate_failed' => '[请求] 参数验证失败',
            'account_block_failed_already_exists' => '[账号黑名单] 目标账号已经被拉黑过了',
            'account_unblock_failed_not_found' => '[账号黑名单] 目标账号没有被拉黑',
            'account_comment_index_failed_target_not_found' => '[账号评论] 检索失败, 目标账号不存在',
            'account_comment_index_failed_empty' => '[账号评论] 检索失败, 目标账号没有发布过评论',
            'account_comment_delete_failed_not_found' => '[账号评论] 删除失败, 目标评论不存在',
            'account_comment_delete_failed_not_owner' => '[账号评论] 删除失败, 目标评论不属于当前操作者',
            'account_comment_history_index_failed_empty' => '[账号评论历史] 检索失败, 目标账号没有发布过评论',
            'account_comment_history_index_failed_invalid_mode' => '[账号评论历史] 检索失败, 无效的检索模式',
            'account_profile_fetch_failed_target_not_found' => '[账号] 获取资料失败, 目标账号不存在',
            'account_profile_fetch_failed_blocked_by_target' => '[账号] 获取资料失败, 访问者已被目标账号拉黑',
            'account_access_request_failed_not_found' => '[账号] 许可获取失败, 目标玩家没有获得 Mod 权限',
            'account_login_failed_not_verified_email' => '[账号] 登录失败, 未验证邮箱',
            'account_login_failed_banned' => '[账号] 登录失败, 已被封禁',
            'account_data_load_failed_not_found' => '[账号存档] 读取失败, 没有找到该账号的存档',
            'account_friend_delete_failed_target_not_friend' => '[账号好友] 删除失败, 目标账号不是操作者的好友',
            'account_friend_request_send_failed_target_not_found' => '[账号好友请求] 发送失败, 目标账号不存在',
            'account_friend_request_send_failed_blocked_by_target' => '[账号好友请求] 发送失败, 操作者已被目标账号拉黑',
            'account_friend_request_send_failed_blocked_by_target_setting' => '[账号好友请求] 发送失败, 目标账号设置不允许发送好友请求',
            'account_friend_request_index_failed_empty' => '[账号好友请求] 检索失败, 没有人发送过好友请求给当前操作者',
            'account_friend_request_index_failed_empty_sent' => '[账号好友请求] 检索失败, 当前操作者没有发送过好友请求',
            'account_friend_request_accept_failed_not_found' => '[账号好友请求] 无法同意, 没有找到对应的好友请求',
            'account_friend_request_accept_failed_target_account_not_match' => '[账号好友请求] 无法同意, 目标账号与发送的值不匹配',
            'account_friend_request_accept_failed_not_receiver' => '[账号好友请求] 无法同意, 当前操作者不是该好友请求的接收者',
            'account_friend_request_delete_failed_not_found' => '[账号好友请求] 删除失败, 没有找到对应的好友请求',
            'account_message_send_failed_target_not_found' => '[账号私信] 发送失败, 目标账号不存在',
            'account_message_send_failed_blocked_by_target' => '[账号私信] 发送失败, 操作者已被目标账号拉黑',
            'account_message_send_failed_blocked_by_target_setting' => '[账号私信] 发送失败, 目标账号设置不允许发送私信',
            'account_message_send_failed_blocked_by_target_setting_unless_friends' => '[账号私信] 发送失败, 目标账号设置不允许发送私信, 除非是好友',
            'account_message_index_failed_empty' => '[账号私信] 检索失败, 没有人发送过私信给当前操作者',
            'account_message_index_failed_empty_sent' => '[账号私信] 检索失败, 当前操作者没有发送过私信',
            'account_message_fetch_failed_not_found' => '[账号私信] 获取失败, 没有找到对应的私信',
            'account_message_fetch_failed_not_owner' => '[账号私信] 获取失败, 私信不属于当前操作者',
            'account_message_delete_failed_not_found' => '[账号私信] 删除失败, 没有找到对应的私信',
            'challenge_generate_failed_unexpected_exception' => '[挑战] 生成失败, 遇到了意料外的异常',
            'song_fetch_failed_upstream_exception' => '[歌曲] 获取失败, 上流异常',
            'song_fetch_failed_not_found_custom' => '[歌曲] 获取失败, 没有找到对应的自定义歌曲',
            'user_index_failed_invalid_mode' => '[用户] 检索失败, 无效的检索模式',
            'user_index_failed_empty' => '[用户] 检索失败, 结果为空',
            'user_search_failed_empty' => '[用户] 搜索失败, 结果为空',
            'item_like_failed_invalid_type' => '[项目] 点赞失败, 无效类型',
            'item_like_failed_already_exists' => '[项目] 点赞失败, 当前操作者已经点过赞了',
            'level_comment_index_failed_level_not_found' => '[关卡评论] 检索失败, 关卡不存在',
            'level_comment_index_failed_empty' => '[关卡评论] 检索失败, 目标关卡没人发过评论',
            'level_comment_index_failed_invalid_mode' => '[关卡评论] 检索失败, 无效模式',
            'level_comment_delete_failed_not_found' => '[关卡评论] 删除失败, 评论不存在',
            'level_comment_delete_failed_parameter_validate_failed' => '[关卡评论] 删除失败, 参数验证失败',
            'level_comment_delete_failed_not_owner' => '[关卡评论] 删除失败, 目标评论不属于当前操作者',
            'leaderboard_fetch_failed_invalid_type' => '[排行榜] 获取失败, 无效类型',
            'leaderboard_fetch_failed_need_login' => '[排行榜] 获取失败, 需要登录',
            'level_leaderboard_fetch_failed_need_login' => '[关卡排行榜] 获取失败, 需要登录',
            'level_leaderboard_fetch_failed_invalid_mode' => '[关卡排行榜] 获取失败, 无效模式',
            'level_rating_stars_rate_failed_suggestion_not_enabled' => '[关卡评分] 星星评分失败, 未开启建议收集',
            'level_rating_stars_rate_failed_level_not_found' => '[关卡评分] 星星评分失败, 关卡不存在',
            'level_rating_stars_rate_failed_overwrite_disabled' => '[关卡评分] 星星评分失败, 禁止覆盖已有的评分',
            'level_rating_stars_rate_failed_already_exists' => '[关卡评分] 星星评分失败, 当前操作者已经评分过了',
            'level_rating_demon_difficulty_rate_failed_suggestion_not_enabled' => '[关卡评分] 恶魔难度评分失败, 未开启建议收集',
            'level_rating_demon_difficulty_rate_failed_level_not_found' => '[关卡评分] 恶魔难度评分失败, 关卡不存在',
            'level_rating_demon_difficulty_rate_failed_overwrite_disabled' => '[关卡评分] 恶魔难度评分失败, 禁止覆盖已有的评分',
            'level_rating_demon_difficulty_rate_failed_already_exists' => '[关卡评分] 恶魔难度评分失败, 当前操作者已经评分过了',
            'level_rating_suggest_failed_suggestion_not_enabled' => '[关卡评分] 恶魔难度评分失败, 未开启建议收集',
            'level_rating_suggest_failed_level_not_found' => '[关卡评分] 评分建议失败, 关卡不存在',
            'level_rating_suggest_failed_overwrite_disabled' => '[关卡评分] 评分建议失败, 禁止覆盖已有的评分',
            'level_rating_suggest_failed_already_exists' => '[关卡评分] 评分建议失败, 当前操作者已经评分过了',
            'level_search_failed_empty' => '[关卡] 搜索失败, 结果为空',
            'level_search_failed_unsupported_type' => '[关卡] 搜索失败, 不支持的搜索类型',
            'level_search_failed_gauntlet_not_found' => '[关卡] 搜索失败, 指定的挑战不存在',
            'level_search_failed_authorization_exception' => '[关卡] 搜索失败, 授权异常',
            'level_download_failed_daily_not_found' => '[关卡] 下载失败, 每日关卡不存在',
            'level_download_failed_weekly_not_found' => '[关卡] 下载失败, 每周关卡不存在',
            'level_download_failed_not_found' => '[关卡] 下载失败, 关卡不存在',
            'level_delete_failed_not_found' => '[关卡] 删除失败, 关卡不存在',
            'level_delete_failed_with_reason' => '[关卡] 删除失败, :reason',
            'level_description_update_failed_not_found' => '[关卡] 简介更新失败, 关卡不存在',
            'level_description_update_failed_not_owner' => '[关卡] 简介更新失败, 目标关卡不属于当前操作者',
            'level_daily_fetch_failed_not_found' => '[关卡] 每日关卡获取失败, 数据不存在(或未找到)',
            'level_weekly_fetch_failed_not_found' => '[关卡] 每周关卡获取失败, 数据不存在(或未找到)'
        ],
        'action' => [
            'account_block_success' => '[账号黑名单] 拉黑成功',
            'account_unblock_success' => '[账号黑名单] 取消拉黑成功',
            'account_comment_command_execute_success' => '[账号评论] 命令执行成功',
            'account_comment_create_success' => '[账号评论] 发布成功',
            'account_comment_index_success' => '[账号评论] 检索成功',
            'account_comment_delete_success' => '[账号评论] 删除成功',
            'account_comment_history_index_success' => '[账号评论历史] 检索成功',
            'account_register_success' => '[账号] 注册成功',
            'account_profile_fetch_success' => '[账号] 获取资料成功',
            'account_access_request_success' => '[账号] 许可获取成功',
            'account_login_success' => '[账号] 登录成功',
            'account_data_server_url_fetch_success' => '[账号存档] 存档服务器地址获取成功',
            'account_data_save_success' => '[账号存档] 保存成功',
            'account_data_load_success' => '[账号存档] 读取成功',
            'account_friend_delete_success' => '[账号好友] 删除成功',
            'account_friend_request_send_success' => '[账号好友请求] 发送成功',
            'account_friend_request_index_success' => '[账号好友请求] 检索成功',
            'account_friend_request_accept_success' => '[账号好友请求] 同意成功',
            'account_friend_request_delete_success' => '[账号好友请求] 删除成功',
            'account_message_send_success' => '[账号私信] 发送成功',
            'account_message_index_success' => '[账号私信] 检索成功',
            'account_message_fetch_success' => '[账号私信] 获取成功',
            'account_message_delete_success' => '[账号私信] 删除成功',
            'account_setting_update_success' => '[账号设置] 更新成功',
            'challenge_fetch_success' => '[挑战] 获取成功',
            'reward_fetch_success' => '[奖励] 获取成功',
            'song_fetch_success' => '[歌曲] 获取成功',
            'featured_artists_fetch_success' => '[特色艺术家] 获取成功',
            'user_score_update_success' => '[用户分数] 更新成功',
            'user_index_success' => '[用户] 检索成功',
            'user_search_success' => '[用户] 搜索成功',
            'level_pack_index_success' => '[关卡包] 检索成功',
            'level_gauntlet_index_success' => '[关卡挑战] 检索成功',
            'item_restore_success' => '[项目] 恢复成功',
            'item_like_success' => '[项目] 点赞成功',
            'level_comment_command_execute_success' => '[关卡评论] 命令执行成功',
            'level_comment_create_success' => '[关卡评论] 发布成功',
            'level_comment_index_success' => '[关卡评论] 检索成功',
            'level_comment_delete_success' => '[关卡评论] 删除成功',
            'leaderboard_fetch_success' => '[排行榜] 获取成功',
            'level_rating_stars_rate_success' => '[关卡评分] 星星评分成功',
            'level_rating_demon_difficulty_rate_success' => '[关卡评分] 恶魔难度评分成功',
            'level_rating_suggest_success' => '[关卡评分] 评分建议成功',
            'level_upload_success' => '[关卡] 上传成功',
            'level_leaderboard_fetch_success' => '[关卡排行榜] 获取成功',
            'level_leaderboard_update_success' => '[关卡排行榜] 更新成功',
            'level_search_success' => '[关卡] 搜索成功',
            'level_download_success' => '[关卡] 下载成功',
            'level_delete_success' => '[关卡] 删除成功',
            'level_description_update_success' => '[关卡] 简介更新成功',
            'level_daily_fetch_success' => '[关卡] 每日关卡获取成功',
            'level_weekly_fetch_success' => '[关卡] 每周关卡获取成功'
        ]
    ],
    'response' => [
        'error' => [
            'invalid' => '[响应] 内容无效'
        ]
    ],
    'storage' => [
        'error' => [
            'invalid_config' => '[存储] 配置解析失败, 无效的配置',
            'fetch_failed_not_found' => '[存储] 内容获取失败, 文件不存在',
            'download_failed_not_found' => '[存储] 内容下载失败, 文件不存在',
            'url_get_failed_not_found' => '[存储] URL获取失败, 文件不存在'
        ]
    ],
    'song' => [
        'error' => [
            'fetch_failed' => '[歌曲] 获取失败',
            'process_failed' => '[歌曲] 处理失败',
            'process_failed_request_error' => '[歌曲] 处理失败, 请求错误: :reason',
            'fetch_failed_disabled' => '[歌曲] 获取失败, 已被禁用',
            'fetch_failed_wrong_song_object' => '[歌曲] 获取失败, 错误的歌曲对象'
        ]
    ],
    'web' => [
        'action' => [
            'login_success' => '[网页] 登录成功!',
            'logout_success' => '[网页] 登出成功!',
            'account_verify_success' => '[账号] 验证成功!',
            'account_verification_email_resend_success' => '[账号] 验证邮件重发成功!'
        ],
        'error' => [
            'need_login' => '[网页] 需要登录',
            'login_failed' => '[网页] 登录失败!',
            'already_logged' => '[网页] 您已经登录过了',
            'account_verification_email_resend_failed_already_verified' => '[账号] 验证邮件重发失败, 您已经验证过了'
        ]
    ],
    'dashboard' => [
        'action' => [
            'contest_submit_success' => '[比赛] 提交成功!',
            'level_edit_success' => '[关卡] 编辑成功!',
            'account_email_edit_success_please_re_verify_email' => '[账号] 邮箱更改成功! 请重新验证邮箱',
            'account_edit_success' => '[账号] 编辑成功!',
            'account_password_change_success' => '[账号] 密码修改成功!',
            'level_delete_success' => '[关卡] 删除成功!',
            'a_mail_with_name_has_been_send_to_your_email' => '一封包含用户名的邮件已发送到您的邮箱'
        ],
        'error' => [
            'invalid_arguments' => '[Dashboard] 参数无效',
            'level_edit_failed_permission_denied' => '[关卡] 编辑失败, 您没有权限编辑该关卡',
            'contest_submit_failed_not_level_owner' => '[比赛] 提交失败, 您不是该关卡的所有者',
            'contest_submit_failed_account_already_submitted' => '[比赛] 提交失败, 该账号已经参加过了',
            'contest_submit_failed_level_already_submitted' => '[比赛] 提交失败, 该关卡已经参加过了',
            'contest_submit_failed_level_already_submitted_in_other_contest' => '[比赛] 提交失败, 该关卡已经参加过其他比赛了',
            'level_delete_failed_with_reason' => '[关卡] 删除失败, :reason'
        ]
    ],
    'tools' => [
        'action' => [
            'account_link_delete_success' => '[账号链接] 解绑成功!',
            'account_link_create_success' => '[账号链接] 创建成功!',
            'level_temp_upload_access_create_success' => '[关卡临时上传许可] 创建成功!',
            'level_temp_upload_access_delete_success' => '[关卡临时上传许可] 销毁成功!',
            'level_transfer_success_with_id' => '[关卡转移] 操作成功! ID: :id',
            'custom_song_create_success_with_id' => '[自定义歌曲] 创建成功! ID: :id',
            'custom_song_delete_success' => '[自定义歌曲] 删除成功!'
        ],
        'error' => [
            'account_link_delete_failed_not_owner' => '[账号链接] 解绑失败, 您不是该链接的所有者',
            'account_link_create_failed_already_exists' => '[账号链接] 创建失败, 链接已存在',
            'account_link_create_failed_login_failed' => '[账号链接] 创建失败, 请检查用户名及密码是否正确',
            'level_temp_upload_access_delete_failed_not_owner' => '[关卡临时上传许可] 删除失败, 您不是该许可的所有者',
            'level_transfer_failed_not_link_owner' => '[关卡转移] 操作失败, 您不是该链接的所有者',
            'level_transfer_failed_level_load_failed' => '[关卡转移] 操作失败, 关卡加载失败, 请稍后再试',
            'level_transfer_failed_level_download_failed' => '[关卡转移] 操作失败, 关卡下载失败, 请稍后再试',
            'level_transfer_failed_level_already_transferred_with_id' => '[关卡转移] 操作失败, 该关卡已经被搬运过了, ID: :id',
            'level_transfer_failed_not_level_owner' => '[关卡转移] 操作失败, 您不是该关卡的所有者',
            'level_transfer_failed_level_upload_failed' => '[关卡转移] 操作失败, 关卡上传失败, 请稍后再试',
            'custom_song_create_failed_invalid_response_headers' => '[自定义歌曲] 创建失败, 响应头部无效',
            'custom_song_create_failed_invalid_content_type' => '[自定义歌曲] 创建失败, 无效的内容类型',
            'custom_song_create_failed_netease_detected' => '[自定义歌曲] 创建失败, 检测到网易云音乐链接, 您用错工具了',
            'custom_song_create_failed_already_exists_with_id' => '[自定义歌曲] 创建失败, 歌曲已存在, ID: :id',
            'custom_song_create_failed_music_info_fetch_failed' => '[自定义歌曲] 创建失败, 音乐信息获取失败',
            'custom_song_delete_failed_not_owner' => '[自定义歌曲] 删除失败, 您不是该歌曲的所有者',
            'custom_song_delete_failed_in_use' => '[自定义歌曲] 删除失败, 有关卡正在使用该歌曲'
        ]
    ],
    'policy' => [
        'error' => [
            'not_level_owner' => '您不是该关卡的所有者',
            'level_rated' => '关卡已被 Rated'
        ]
    ],
    'notification' => [
        'lines' => [
            'find_name' => '您正在找回用户名',
            'name_is_with_name' => '您的用户名是: :name',
            'if_you_did_not_forget_name_please_ignore_this' => '如果您未忘记用户名 请忽略这个'
        ]
    ]
];
