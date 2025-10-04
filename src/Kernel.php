<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * The application kernel.
 *
 * This class is the heart of the Symfony application. It's responsible for
 * loading bundles, configuring the application, and handling requests.
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
