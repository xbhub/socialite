<?php

/*
 * This file is part of the overtrue/socialite.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Packages\Socialite;

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
     * @return \Packages\Socialite\ProviderInterface
     */
    public function driver($driver);
}
