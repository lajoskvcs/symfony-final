<?php
namespace AppBundle\Twig;

class UrlExtension extends \Twig_Extension
{
    public function getName(){
        return 'url_extension';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('decorateUrl', array($this, 'decorateString')),
        );
    }
    public function decorateString($string)
    {
        return 'asd';
    }
}