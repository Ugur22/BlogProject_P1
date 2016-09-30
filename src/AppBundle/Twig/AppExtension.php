<?php
/**
 * Created by PhpStorm.
 * User: uertas
 * Date: 9/28/16
 * Time: 9:55 PM
 */

namespace AppBundle\Twig;

use Symfony\Component\HttpFoundation\Session\Session;

class AppExtension extends \Twig_Extension

{

    public function getGlobals()
    {
        $session = new Session();
        return array(
            'session' => $session->all(),
        );
    }

    public function getName()
    {
        return 'app_extension';
    }
}