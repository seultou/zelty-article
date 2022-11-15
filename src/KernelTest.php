<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class KernelTest extends BaseKernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', false);
    }
}
