<?php

declare(strict_types=1);

namespace Larisch\MaintenanceBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
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
        private array $ipAddresses,
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

        $remoteIP = $this->getRemoteIp($request);

        if (in_array($remoteIP, $this->ipAddresses, true)) {
            return;
        }

        if ($request->cookies->get('maintenance_bypass') === $this->bypassToken) {
            $response = $event->getResponse() ?? new Response();
            $response->headers->set('Vary', 'Cookie');
            return;
        }

        $content = $this->twig->render($this->templatePath);

        $response = new Response($content, 503);
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');
        $event->setResponse($response);
    }

    private function getRemoteIp(Request $request): string
    {
        return $request->server->get('HTTP_X_FORWARDED_FOR')
            ?? $request->server->get('REMOTE_ADDR')
            ?? '0.0.0.0';
    }
}
