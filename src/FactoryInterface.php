<?php

/*
 * This file is part of the xbhub\socialite.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Xbhub\Socialite;

/**
 * Interface FactoryInterface.
 */
interface FactoryInterface
{
    /**
     * Get an OAuth provider implementation.
     *
     * @param string $driver
     *
     * @return \Xbhub\Socialite\ProviderInterface
     */
    public function driver($driver);
}
