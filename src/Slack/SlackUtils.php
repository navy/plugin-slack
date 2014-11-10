<?php
namespace Navy\SlackPlugin\Slack;

class SlackUtils
{
    public function resolveMessageHighlights($message)
    {
        $message = str_replace(['@everyone', '@channel'], ['<!everyone>', '<!channel>'], $message);

        if (strpos($message, '@') !== false) {
            $message = preg_replace_callback('/@(\w+)/', function ($matches) {
                if (in_array($matches[1], ['everyone', 'channel', 'group'])) {
                    $rule = "!{$matches[1]}";
                } else {
                    $rule = $matches[0];
                }

                return "<{$rule}>";
            }, $message);
        }

        return $message;
    }
}
