<?php

namespace EtoA\Defense;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DefenseServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['etoa.defense.repository'] = function (Container $pimple) {
            return new DefenseRepository($pimple['db']);
        };
    }
}
