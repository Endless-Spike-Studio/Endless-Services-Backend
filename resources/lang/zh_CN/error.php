<?php

return [
    'invalid_config' => '无效配置',
    'game' => [
        'account' => [
            'block' => [
                'already_exists' => '目标已经被拉黑过了',
                'not_found' => '目标没有被拉黑'
            ],
            'comment' => [
                'empty' => '账号评论为空',
                'not_found' => '目标账号评论未找到(或不存在)',
                'invalid_mode' => '账号评论获取失败, 无效模式',
                'history' => [
                    'empty' => '账号评论历史为空'
                ]
            ],
            'not_found' => '账号不存在',
            'blocked_by_target' => '玩家被目标拉黑',
            'mod_permission_not_found' => '没有找到对应玩家的 Mod 记录',
            'login' => [
                'need_verify_email' => '玩家登录失败, 需要验证邮箱'
            ],
            'banned' => '操作失败, 玩家已被封禁',
            'save' => [
                'not_found' => '存档不存在(或未找到)'
            ]
        ]
    ]
];
