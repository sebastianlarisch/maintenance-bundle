<?php

declare(strict_types=1);

namespace Larisch\MaintenanceBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

final readonly class MaintenanceListener implements EventSubscriberInterface
{
    public function __construct(
        private bool $enabled,
        private string $bypassToken,
        private string $templatePath,
        private Environment $twig
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => ['onKernelRequest', 24]];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$this->enabled) {
            return;
        }

        $request = $event->getRequest();

        if (str_starts_with($request->getRequestUri(), '/admin')) {
            return;
        }

        if ($request->cookies->get('maintenance_bypass') === $this->bypassToken) {
            return;
        }

        $content = $this->twig->render($this->templatePath);

        $response = new Response($content, 503);
        $event->setResponse($response);
    }
}
