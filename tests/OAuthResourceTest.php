<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Lemaur\Pinterest\Facades\Pinterest;
use Lemaur\Pinterest\Services\Resources\OAuthResource;

it('generates access code link', function (): void {
    Cache::spy();

    Pinterest::fake();

    $link = Pinterest::oauth()->getAccessCodeLink();

    expect($link)
        ->toBeString()
        ->toStartWith('https://www.pinterest.com/oauth/')
        ->toContain('client_id=')
        ->toContain('redirect_uri=')
        ->toContain('response_type=code')
        ->toContain('scope=')
        ->toContain('state=');

    Cache::shouldHaveReceived('put')->once();
});

it('requests access token', function (): void {
    Pinterest::fake();

    Pinterest::oauth()->requestAccessToken();

    Pinterest::assertSentCount(1);

    Pinterest::assertSent(function (Request $request) {
        return $request->url() === $this->baseUrl.OAuthResource::ENDPOINT
            && $request->method() === 'POST'
            && $request->header('Content-Type')[0] === 'application/x-www-form-urlencoded'
            && $request->header('Authorization')[0] === 'Basic Og=='
            && $request->data() === [
                'redirect_uri' => '/pinterest/callback',
                'code' => 'access-code',
                'grant_type' => 'authorization_code',
            ];
    });
});

it('refreshes access token', function (): void {
    Pinterest::fake();

    Pinterest::oauth()->refreshAccessToken();

    Pinterest::assertSentCount(1);

    Pinterest::assertSent(function (Request $request) {
        return $request->url() === $this->baseUrl.OAuthResource::ENDPOINT
            && $request->method() === 'POST'
            && $request->header('Content-Type')[0] === 'application/x-www-form-urlencoded'
            && $request->header('Authorization')[0] === 'Basic Og=='
            && $request->data() === [
                'refresh_token' => 'refresh-token',
                'grant_type' => 'refresh_token',
            ];
    });
});
