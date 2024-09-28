<?php 

namespace App\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\ArgumentValueResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MaintenanceListener
{
    private $params;
    private $twig;

    public function __construct(ParameterBagInterface $params, \Twig\Environment $twig)
    {
        $this->params = $params;
        $this->twig = $twig;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $maintenanceMode = $this->params->get('MAINTENANCE_MODE') === 'true';

        if ($maintenanceMode && $request->getPathInfo() !== '/maintenance') {
            $response = new Response($this->twig->render('maintenance/maintenance.html.twig'), 503);
            $event->setResponse($response);
        }
    }
}
