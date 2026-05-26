<?php

test('admin login page returns successful response', function () {
    $this->withoutVite();
    $response = $this->get('/admin/login');

    $response->assertStatus(200);
});
