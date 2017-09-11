<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    protected $linkClass;
    protected $target;

    public function setLinkClass($class)
    {
        $this->linkClass = $class;
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

    // @codeCoverageIgnoreEnd
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'converturls',
                array($this, 'autoConvertUrls'),
                array(
                    'pre_escape' => 'html',
                    'is_safe' => array('html'),
                )
            ),
        );
    }

    public function autoConvertUrls($string)
    {
        $pattern = '/(href="|src=")?([-a-zA-Zа-яёА-ЯЁ0-9@:%_\+.~#?&\*\/\/=]{2,256}\.[a-zа-яё]{2,4}\b(\/?[-\p{L}0-9@:%_\+.~#?&\*\/\/=\(\),;]*)?)/u';
        $stringFiltered = preg_replace_callback($pattern, array($this, 'callbackReplace'), $string);
        return $stringFiltered;
    }

    public function callbackReplace($matches)
    {
        if ($matches[1] !== '') {
            return $matches[0];
        }
        $url = $matches[2];
        $urlWithPrefix = $matches[2];
        if (strpos($url, '@') !== false) {
            $urlWithPrefix = 'mailto:' . $url;
        } elseif (strpos($url, 'https://') === 0) {
            $urlWithPrefix = $url;
        } elseif (strpos($url, 'http://') !== 0) {
            $urlWithPrefix = 'http://' . $url;
        }
        if (preg_match("/^(.*)(\.|\,|\?)$/", $urlWithPrefix, $matches)) {
            $urlWithPrefix = $matches[1];
            $url = substr($url, 0, -1);
            $punctuation = $matches[2];
        } else {
            $punctuation = '';
        }
        return '<a href="' . $urlWithPrefix
            . '" class="' . $this->linkClass
            . '" target="' . $this->target . '">'
            . $url . '</a>' . $punctuation;
    }
}