<?php

return [
    'error' => [
        'response_invalid' => '[响应] 内容无效'
    ],
    'game' => [
        'command' => [
            'not_found' => 'Command not found',
            'unavailable' => 'Command not available',
            'worked' => 'worked!'
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
            'account_friend_delete_failed_target_not_found' => '[账号好友] 删除失败, 目标账号不是操作者的好友',
            'account_friend_request_send_failed_target_not_found' => '[账号好友请求] 发送失败, 目标账号不存在',
            'account_friend_request_send_failed_blocked_by_target' => '[账号好友请求] 发送失败, 操作者已被目标账号拉黑',
            'account_friend_request_send_failed_blocked_by_target_setting' => '[账号好友请求] 发送失败, 目标账号设置不允许发送好友请求',
            'account_friend_request_send_failed_empty' => '[账号好友请求] 检索失败, 没有人发送过好友请求给当前操作者',
            'account_friend_request_send_failed_empty_sent' => '[账号好友请求] 检索失败, 当前操作者没有发送过好友请求',
            'account_friend_request_accept_failed_not_found' => '[账号好友请求] 无法同意, 没有找到对应的好友请求',
            'account_friend_request_accept_failed_target_account_not_match' => '[账号好友请求] 无法同意, 目标账号与发送的值不匹配',
            'account_friend_request_accept_failed_not_receiver' => '[账号好友请求] 无法同意, 当前操作者不是该好友请求的接收者',
            'account_friend_request_delete_failed_not_found' => '[账号好友请求] 删除失败, 没有找到对应的好友请求'
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
            'account_friend_request_delete_success' => '[账号好友请求] 删除成功'
        ]
    ],
    'storage' => [
        'error' => [
            'invalid_config' => '[存储] 配置解析失败, 无效的配置',
            'fetch_failed_not_found' => '[存储] 内容获取失败, 文件不存在',
            'download_failed_not_found' => '[存储] 内容下载失败, 文件不存在'
        ]
    ],
    'song' => [
        'error' => [
            'fetch_failed' => '[歌曲] 获取失败',
            'process_failed_request_error' => '[歌曲] 处理失败, 请求错误: :reason',
            'fetch_failed_disabled' => '[歌曲] 获取失败, 已被禁用',
            'fetch_failed_wrong_song_object' => '[歌曲] 获取失败, 错误的歌曲对象'
        ]
    ]
];
