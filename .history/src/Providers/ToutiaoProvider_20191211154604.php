<?php

namespace Xbhub\Socialite\Providers;

use Xbhub\Socialite\AccessToken;
use Xbhub\Socialite\AccessTokenInterface;
use Xbhub\Socialite\ProviderInterface;
use Xbhub\Socialite\User;

/**
 * Class DouYinProvider.
 *
 * @author jorycn@163.com
 *
 * @see https://developer.toutiao.com/docs/server/auth/jscode2session.html
 */
class ToutiaoProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * 抖音接口域名.
     *
     * @var string
     */
    protected $baseUrl = 'https://developer.toutiao.com';


    /**
     * 获取登录页面地址.
     *
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->baseUrl.'/platform/oauth/connect', $state);
    }

    /**
     * 获取授权码接口参数.
     *
     * @param string|null $state
     *
     * @return array
     */
    public function getCodeFields($state = null)
    {
        $fields = [
            'client_key' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'scope' => $this->formatScopes($this->scopes, $this->scopeSeparator),
            'response_type' => 'code',
        ];

        if ($this->usesState()) {
            $fields['state'] = $state;
        }

        return $fields;
    }

    /**
     * 获取access_token地址.
     *
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->baseUrl.'/api/apps/token';
    }

    /**
     * 通过code获取access_token.
     *
     * @param string $code
     *
     * @return \Xbhub\Socialite\AccessToken
     */
    public function getAccessToken($code = '')
    {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'query' => $this->getTokenFields($code),
        ]);

        return $this->parseAccessToken($response->getBody()->getContents());
    }

    /**
     * 获取access_token接口参数.
     *
     * @param string $code
     *
     * @return array
     */
    protected function getTokenFields($code = '')
    {
        return [
            'appid' => $this->clientId,
            'secret' => $this->clientSecret,
            'grant_type' => 'client_credential',
        ];
    }

    /**
     * 格式化token.
     *
     * @param \Psr\Http\Message\StreamInterface|array $body
     *
     * @return \Xbhub\Socialite\AccessTokenInterface
     */
    protected function parseAccessToken($body)
    {
        if (!is_array($body)) {
            $body = json_decode($body, true);
        }

        if (empty($body['data']['access_token'])) {
            throw new AuthorizeFailedException('Authorize Failed: '.json_encode($body, JSON_UNESCAPED_UNICODE), $body);
        }

        return new AccessToken($body['data']);
    }

    /**
     * 通过token 获取用户信息.
     *
     * @param AccessTokenInterface $token
     *
     * @return array|mixed
     */
    protected function getUserByToken(AccessTokenInterface $token)
    {

    }

    /**
     * 格式化用户信息.
     *
     * @param array $user
     *
     * @return User
     */
    protected function mapUserToObject(array $user)
    {
        return new User([
            'id' => $this->arrayItem($user, 'openid'),
            'anonymous_openid' => $this->arrayItem($user, 'anonymous_openid'),
            'username' => $this->arrayItem($user, 'nickName'),
            'nickname' => $this->arrayItem($user, 'nickName'),
            'avatar' => $this->arrayItem($user, 'avatarUrl'),
        ]);
    }

    /**
     * @param string $code
     * @param array $userinfo
     * @param array $options
     * @return mixed
     */
    public function getUserByCode(string $code, array $userinfo, array $options)
    {
        $userUrl = $this->baseUrl.'/api/apps/jscode2session';
        $response = $this->getHttpClient()->get($userUrl, [
            'query' => array_merge($this->getTokenFields(), [
                'code' => $code,
                'anonymous_code' => $options['anonymousCode']
            ]),
        ]);
                
        return $this->mapUserToObject(array_merge(
            json_decode($response->getBody()->getContents(), true),
            $userinfo)
        );
    }
}
