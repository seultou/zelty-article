<?php

declare(strict_types=1);

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Home extends AbstractFOSRestController
{
    public function __invoke(): Response
    {
        return new RedirectResponse('/api/login'); // @TODO: add a server-status page???
    }
}
