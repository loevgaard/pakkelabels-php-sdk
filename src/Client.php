<?php
namespace Loevgaard\Pakkelabels;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Client
{
    /**
     * @var GuzzleClientInterface
     */
    protected $httpClient;

    /**
     * This is the API username which you find/generate under Settings > API
     *
     * @var string
     */
    private $username;

    /**
     * This is the API password which you find/generate under Settings > API
     *
     * @var string
     */
    private $password;

    /**
     * This is the base url for the API
     *
     * @var string
     */
    private $baseUrl = 'https://app.pakkelabels.dk/api/public/v3';

    /**
     * @var OptionsResolver
     */
    private $defaultOptions;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array|null $options
     * @return ResponseInterface
     */
    public function doRequest($method, $uri, array $options = []) : ResponseInterface
    {
        $url = $this->baseUrl . $uri;
        $options = $this->getDefaultOptions()->resolve($options);
        return $this->getHttpClient()->request($method, $url, $options);
    }

    /**
     * @return GuzzleClientInterface
     */
    public function getHttpClient() : GuzzleClientInterface
    {
        if (!$this->httpClient) {
            $this->httpClient = new GuzzleClient();
        }
        return $this->httpClient;
    }

    /**
     * @param GuzzleClientInterface $httpClient
     * @return Client
     */
    public function setHttpClient(GuzzleClientInterface $httpClient) : self
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return Client
     */
    public function setUsername(string $username) : self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Client
     */
    public function setPassword(string $password) : self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl() : string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     * @return Client
     */
    public function setBaseUrl(string $baseUrl) : self
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function getDefaultOptions()
    {
        if(!$this->defaultOptions) {
            $options = new OptionsResolver();
            $options->setDefaults([
                'allow_redirects' => false,
                'cookies' => false,
                'timeout' => 60,
                'http_errors' => false,
                'auth' => [
                    $this->username,
                    $this->password
                ]
            ]);
        }

        return $this->defaultOptions;
    }

    public function setDefaultOptions(OptionsResolver $optionsResolver) : self
    {
        $this->defaultOptions = $optionsResolver;
        return $this;
    }
}
