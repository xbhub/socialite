<?php

/*
 * This file is part of the xbhub\socialite.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Xbhub\Socialite\AccessToken;
use Xbhub\Socialite\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testJsonserialize()
    {
        $this->assertSame('[]', json_encode(new User([])));

        $this->assertSame('{"token":"mock-token"}', json_encode(new User(['token' => new AccessToken(['access_token' => 'mock-token'])])));
    }
}
