<?php
namespace Navy\SlackPlugin;

use Navy\Plugin\AbstractPlugin;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SlackPlugin extends AbstractPlugin
{
    public function loadConfig(ParameterBagInterface $parameters, array $config)
    {
        $params = [
            'slack.token'      => null,
            'slack.user_agent' => 'Navy/%kernel.version%',
        ];

        if (isset($config['token'])) {
            $params['slack.token'] = $config['token'];
        }

        if (isset($config['user_agent'])) {
            $params['slack.user_agent'] = $config['user_agent'];
        }

        $parameters->add($params);
    }

    public function getNotifiers()
    {
        return [
            'slack.slack_adapter_resolver',
        ];
    }
}
