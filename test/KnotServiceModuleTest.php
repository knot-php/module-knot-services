<?php
declare(strict_types=1);

namespace KnotModule\KnotService\Test;

use PHPUnit\Framework\TestCase;
use KnotLib\Kernel\Module\Components;
use KnotModule\KnotService\KnotServiceModule;
use KnotModule\KnotDi\KnotDiModule;
use KnotLib\Service\DI;
use KnotLib\Service\FileSystemService;
use KnotLib\Service\LoggerService;
use KnotLib\Service\ValidationService;
use KnotLib\Service\RequestService;

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

        $this->assertInstanceOf(FileSystemService::class, $di[DI::SERVICE_FILESYSTEM]);
        $this->assertInstanceOf(LoggerService::class, $di[DI::SERVICE_LOGGER]);
        $this->assertInstanceOf(ValidationService::class, $di[DI::SERVICE_VALIDATION]);
        $this->assertInstanceOf(RequestService::class, $di[DI::SERVICE_REQUEST]);
    }
}