<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotService\Test;

use PHPUnit\Framework\TestCase;

use KnotLib\Kernel\Module\Components;
use KnotLib\Service\DI;
use KnotLib\Service\FileSystemService;
use KnotLib\Service\LoggerService;
use KnotLib\Service\ValidationService;

use KnotPhp\Module\KnotDi\KnotDiModule;

use KnotPhp\Module\KnotService\KnotServiceModule;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class KnotServiceModuleTest extends TestCase
{
    public function testRequiredComponents()
    {
        $this->assertEquals([
            Components::DI,
            Components::LOGGER,
            Components::EVENTSTREAM,
        ],
            KnotServiceModule::requiredComponents());
    }
    public function testDeclareComponentType()
    {
        $this->assertEquals(Components::MODULE, KnotServiceModule::declareComponentType());
    }

    /**
     * @throws
     */
    public function testInstall()
    {
        $app = new TestApplication();

        (new KnotDiModule())->install($app);
        (new KnotServiceModule())->install($app);

        $di = $app->di();

        $this->assertNotNull($di);

        $this->assertInstanceOf(ServerRequestInterface::class, $di[DI::URI_COMPONENT_REQUEST]);
        $this->assertInstanceOf(ResponseInterface::class, $di[DI::URI_COMPONENT_RESPONSE]);

        $this->assertInstanceOf(FileSystemService::class, $di[DI::URI_SERVICE_FILESYSTEM]);
        $this->assertInstanceOf(LoggerService::class, $di[DI::URI_SERVICE_LOGGER]);
        $this->assertInstanceOf(ValidationService::class, $di[DI::URI_SERVICE_VALIDATION]);
    }
}