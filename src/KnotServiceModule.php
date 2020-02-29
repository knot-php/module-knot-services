<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotService;

use Throwable;

use KnotLib\Kernel\Module\ComponentTypes;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ModuleInterface;
use KnotLib\Service\DI;
use KnotLib\Kernel\EventStream\Events;
use KnotLib\Kernel\EventStream\Channels as EventChannels;
use KnotLib\Service\FileSystemService;
use KnotLib\Service\LoggerService;
use KnotLib\Service\ValidationService;

final class KnotServiceModule implements ModuleInterface
{
    /**
     * Declare dependency on another modules
     *
     * @return array
     */
    public static function requiredModules() : array
    {
        return [];
    }

    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponentTypes() : array
    {
        return [
            ComponentTypes::DI,
            ComponentTypes::LOGGER,
            ComponentTypes::EVENTSTREAM,
        ];
    }

    /**
     * Declare component type of this module
     *
     * @return string
     */
    public static function declareComponentType() : string
    {
        return ComponentTypes::SERVICE;
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
            $response = $app->response();

            $c = $app->di();

            //====================================
            // ComponentTypes
            //====================================

            // component://request component
            $c[DI::URI_COMPONENT_REQUEST] = $request;

            // component://response component
            $c[DI::URI_COMPONENT_RESPONSE] = $response;

            //====================================
            // Services
            //====================================

            // service.filesystem factory
            $c[DI::URI_SERVICE_FILESYSTEM] = function() use($fs){
                return new FileSystemService($fs);
            };

            // service.logger factory
            $c[DI::URI_SERVICE_LOGGER] = function() use($logger){
                return new LoggerService($logger);
            };

            // service.validation factory
            $c[DI::URI_SERVICE_VALIDATION] = function(){
                return new ValidationService();
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