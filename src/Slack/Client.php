<?php
namespace Navy\SlackPlugin\Slack;

use Psr\Log\LoggerInterface;

class Client implements ClientInterface
{
    protected $url = 'https://slack.com/api/';
    protected $token;
    protected $logger;
    protected $userAgent;

    public function __construct($token = null, LoggerInterface $logger = null, $userAgent = null)
    {
        $this->setToken($token);

        $this->logger = $logger;
        $this->userAgent = $userAgent;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function request($method, array $params = [])
    {
        if (!isset($params['token'])) {
            $params['token'] = $this->token;
        }

        $response = null;
        try {
            $ch = curl_init();

            $opts = [
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_HTTPHEADER     => [
                    'Expect:',
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 60,
                CURLOPT_POSTFIELDS     => http_build_query($params, null, '&'),
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_URL            => $this->makeApiUrl($method),
            ];
            if ($this->userAgent !== null) {
                $opts[CURLOPT_USERAGENT] = $this->userAgent;
            }

            curl_setopt_array($ch, $opts);

            if ($this->logger !== null) {
                $this->logger->info(sprintf('Slack Api Request: %s (%s)', $method, json_encode($params, JSON_UNESCAPED_UNICODE)));
            }

            $response = curl_exec($ch);

            if ($response === false) {
                throw new \RuntimeException(curl_error($ch), curl_errno($ch));
            }

            if ($this->logger !== null) {
                $this->logger->info(sprintf('Slack Api Response: %s', $response));
            }

            $response = json_decode($response);
        } catch (\Exception $e) {
            if ($this->logger !== null) {
                $this->logger->error(sprintf('Slack Api Error: %s', $e->getMessage()));
            }

            throw $e;
        } finally {
            curl_close($ch);
        }

        return $response;
    }

    protected function makeApiUrl($method)
    {
        return $this->url . $method;
    }
}
