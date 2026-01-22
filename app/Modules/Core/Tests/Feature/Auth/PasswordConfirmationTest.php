<?php

use App\Modules\Core\Models\User;

it('can render password confirmation screen', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('password.confirm'));

    $response->assertStatus(200);
});
