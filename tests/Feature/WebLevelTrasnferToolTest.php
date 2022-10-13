<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('关卡转入', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});
