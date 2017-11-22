<?php

namespace whiteLabel\MainBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class whiteLabelMainBundle
 * @package whiteLabel\MainBundle
 */
class whiteLabelMainBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
