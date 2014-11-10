<?php
namespace Navy\SlackPlugin\Notifier;

use Navy\Notifier\AdapterInterface;
use Navy\SlackPlugin\Slack\ClientInterface;
use Navy\SlackPlugin\Slack\SlackUtils;

class SlackAdapter implements AdapterInterface
{
    protected $client;
    protected $utils;
    protected $token;
    protected $channel;
    protected $username;
    protected $iconEmoji;

    public function __construct(ClientInterface $client, SlackUtils $utils, $token, $channel, $username = null, $iconEmoji = null)
    {
        $this->client = $client;
        $this->utils = $utils;
        $this->token = $token;
        $this->channel = $channel;
        $this->username = $username;
        if ($iconEmoji) {
            $this->iconEmoji = ':' . trim($iconEmoji, ':') . ':';
        }
    }

    public function notify($message)
    {
        $this->notifyChannel($this->channel, $message);
    }

    public function notifyChannel($channel, $message)
    {
        $this->client->request('chat.postMessage', [
            'token'      => $this->token,
            'channel'    => $channel,
            'text'       => $this->utils->resolveMessageHighlights($message),
            'username'   => $this->username,
            'icon_emoji' => $this->iconEmoji,
        ]);
    }
}
