<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotService;

use Throwable;

use KnotLib\Service\RequestService;
use KnotLib\Kernel\Module\Components;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ComponentModule;
use KnotLib\Kernel\EventStream\Events;
use KnotLib\Kernel\EventStream\Channels as EventChannels;
use KnotLib\Service\DI;
use KnotLib\Service\FileSystemService;
use KnotLib\Service\LoggerService;
use KnotLib\Service\ValidationService;

final class KnotServiceModule extends ComponentModule
{
    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponents() : array
    {
        return [
            Components::DI,
            Components::LOGGER,
            Components::EVENTSTREAM,
        ];
    }

    /**
     * Declare component type of this module
     *
     * @return string
     */
    public static function declareComponentType() : string
    {
        return Components::MODULE;
    }

    /**
     * Install module
     *
     * @param ApplicationInterface $app
     *
     * @throws
     */
    public function install(ApplicationInterface $app)
    {
        try{
            $fs = $app->filesystem();
            $logger = $app->logger();
            $request = $app->request();

            $c = $app->di();

            // services.filesystem factory
            $c[DI::SERVICE_FILESYSTEM] = function() use($fs){
                return new FileSystemService($fs);
            };

            // services.logger factory
            $c[DI::SERVICE_LOGGER] = function() use($logger){
                return new LoggerService($logger);
            };

            // services.validation factory
            $c[DI::SERVICE_VALIDATION] = function(){
                return new ValidationService();
            };

            // services.request factory
            $c[DI::SERVICE_REQUEST] = function() use($request){
                return new RequestService($request);
            };

            // fire event
            $app->eventstream()->channel(EventChannels::SYSTEM)->push(Events::MODULE_INSTALLED, $this);
        }
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
        }
    }
}