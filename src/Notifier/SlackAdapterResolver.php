<?php
namespace Navy\SlackPlugin\Notifier;

use Navy\Notifier\AdapterResolverInterface;
use Navy\SlackPlugin\Slack\ClientInterface;
use Navy\SlackPlugin\Slack\SlackUtils;

class SlackAdapterResolver implements AdapterResolverInterface
{
    public function __construct(ClientInterface $client, SlackUtils $utils)
    {
        $this->client = $client;
        $this->utils = $utils;
    }

    public function getSupportType()
    {
        return 'slack';
    }

    public function resolveAdapter(array $config)
    {
        $config = array_merge([
            'token'      => null,
            'channel'    => null,
            'username'   => null,
            'icon_emoji' => null,
        ], $config);

        return new SlackAdapter($this->client, $this->utils, $config['token'], $config['channel'], $config['username'], $config['icon_emoji']);
    }
}
