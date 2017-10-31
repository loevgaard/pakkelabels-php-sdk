<?php
namespace Loevgaard\Pakkelabels;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\RequestOptions;
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
     * @var array
     */
    private $defaultOptions;

    /**
     * @var ResponseInterface
     */
    private $lastResponse;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->defaultOptions = [];
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return array
     */
    public function doRequest($method, $uri, array $options = []) : array
    {
        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);

        $url = $this->baseUrl . $uri;
        $options = $optionsResolver->resolve(array_replace($this->defaultOptions, $options));
        $this->lastResponse = $this->getHttpClient()->request($method, $url, $options);
        try {
            $res = \GuzzleHttp\json_decode((string)$this->lastResponse->getBody(), true);
        } catch (\InvalidArgumentException $e) {
            $res = ['error' => '['.$this->lastResponse->getStatusCode().'] The response body was not correctly formatted JSON. Inspect the last response to figure out the reason for this.'];
        }

        return $res;
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
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultOptions() : array
    {
        return $this->defaultOptions;
    }

    /**
     * @param array $options
     * @return Client
     */
    public function setDefaultOptions(array $options) : self
    {
        $this->defaultOptions = $options;
        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getLastResponse(): ResponseInterface
    {
        return $this->lastResponse;
    }

    /**
     * @param ResponseInterface $lastResponse
     * @return Client
     */
    public function setLastResponse(ResponseInterface $lastResponse) : self
    {
        $this->lastResponse = $lastResponse;
        return $this;
    }

    protected function configureOptions(OptionsResolver $optionsResolver)
    {
        // add request options from Guzzle
        $reflection = new \ReflectionClass(RequestOptions::class);
        $optionsResolver->setDefined($reflection->getConstants());

        // set defaults
        $optionsResolver->setDefaults([
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
}
