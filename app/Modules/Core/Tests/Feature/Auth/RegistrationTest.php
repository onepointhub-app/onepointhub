<?php

it('can render registration screen', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
});

it('can register a new user', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'john@doe.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('home', absolute: false));

    $this->assertAuthenticated();
});
