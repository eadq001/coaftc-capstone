<?php

use App\Livewire\Login;

it('has email password and remember properties', function () {
    $reflection = new ReflectionClass(Login::class);

    expect($reflection->hasProperty('email'))->toBeTrue()
        ->and($reflection->hasProperty('password'))->toBeTrue()
        ->and($reflection->hasProperty('remember'))->toBeTrue();
});

it('has a login method', function () {
    $reflection = new ReflectionClass(Login::class);

    expect($reflection->hasMethod('login'))->toBeTrue();
});

it('has validation rules on email property', function () {
    $reflection = new ReflectionProperty(Login::class, 'email');
    $attributes = $reflection->getAttributes();

    expect($attributes)->not->toBeEmpty();
});

it('remember property defaults to false', function () {
    $login = new Login;

    expect($login->remember)->toBeFalse();
});

it('email property defaults to empty string', function () {
    $login = new Login;

    expect($login->email)->toBe('');
});

it('password property defaults to empty string', function () {
    $login = new Login;

    expect($login->password)->toBe('');
});

it('has a render method', function () {
    $reflection = new ReflectionClass(Login::class);

    expect($reflection->hasMethod('render'))->toBeTrue();
});
