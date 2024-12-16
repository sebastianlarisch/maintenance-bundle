<?php

declare(strict_types=1);

namespace Larisch\MaintenanceBundle\Tests\EventListener;

use Larisch\MaintenanceBundle\EventListener\MaintenanceListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Twig\Environment;

class MaintenanceListenerTest extends TestCase
{
    private Environment $twigMock;

    protected function setUp(): void
    {
        $this->twigMock = $this->createMock(Environment::class);
        $this->twigMock
            ->method('render')
            ->with('maintenance.html.twig')
            ->willReturn('<html>Maintenance Mode</html>');
    }

    public function testOnKernelRequestMaintenanceEnabled(): void
    {
        // Arrange
        $listener = new MaintenanceListener(
            true,
            'test-token',
            'maintenance.html.twig',
            [],
            $this->twigMock
        );

        $request = new Request();
        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        // Act
        $listener->onKernelRequest($event);

        // Assert
        $response = $event->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(503, $response->getStatusCode());
        $this->assertEquals('<html>Maintenance Mode</html>', $response->getContent());
    }

    public function testOnKernelRequestMaintenanceDisabled(): void
    {
        // Arrange
        $listener = new MaintenanceListener(
            false,
            'test-token',
            'maintenance.html.twig',
            [],
            $this->twigMock
        );

        $request = new Request();
        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        // Act
        $listener->onKernelRequest($event);

        // Assert
        $this->assertNull($event->getResponse());
    }

    public function testOnKernelRequestWithBypassToken(): void
    {
        // Arrange
        $listener = new MaintenanceListener(
            true,
            'test-token',
            'maintenance.html.twig',
            [],
            $this->twigMock
        );

        $request = new Request();
        $request->cookies->add(['maintenance_bypass' => 'test-token']);

        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        // Act
        $listener->onKernelRequest($event);

        // Assert
        $this->assertNull($event->getResponse());
    }

    public function testOnKernelRequestForAdminRoute(): void
    {
        // Arrange
        $listener = new MaintenanceListener(
            true,
            'test-token',
            'maintenance.html.twig',
            [],
            $this->twigMock
        );

        $request = new Request([], [], [], [], [], ['REQUEST_URI' => '/admin']);
        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        // Act
        $listener->onKernelRequest($event);

        // Assert
        $this->assertNull($event->getResponse());
    }

    public function testOnKernelRequestWithAllowedIp(): void
    {
        // Arrange
        $listener = new MaintenanceListener(
            true,
            'test-token',
            'maintenance.html.twig',
            ["1.2.3.4"],
            $this->twigMock
        );

        $request = new Request([], [], [], [], [], ['REQUEST_URI' => '/admin', 'REMOTE_ADDR' => '1.2.3.4']);
        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        // Act
        $listener->onKernelRequest($event);

        // Assert
        $this->assertNull($event->getResponse());
    }

    public function testOnKernelRequestWithNotAllowedIp(): void
    {
        // Arrange
        $listener = new MaintenanceListener(
            true,
            '',
            'maintenance.html.twig',
            ["1.2.3.4"],
            $this->twigMock
        );

        $request = new Request([], [], [], [], [], ['REMOTE_ADDR' => '5.4.3.2']);
        $event = new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $request,
            HttpKernelInterface::MAIN_REQUEST
        );

        // Act
        $listener->onKernelRequest($event);

        // Assert
        $response = $event->getResponse();
        $this->assertEquals(503, $response->getStatusCode());
        $this->assertEquals('<html>Maintenance Mode</html>', $response->getContent());
    }
}
