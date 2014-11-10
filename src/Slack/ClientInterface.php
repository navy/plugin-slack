<?php
namespace Navy\SlackPlugin\Slack;

interface ClientInterface
{
    public function request($method, array $params = []);
    public function setToken($token);
}
