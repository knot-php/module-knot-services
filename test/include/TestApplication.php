<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotService\Test;

use KnotLib\Kernel\Kernel\AbstractApplication;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Kernel\ApplicationType;

final class TestApplication extends AbstractApplication
{
    public static function type(): ApplicationType
    {
        return ApplicationType::of(ApplicationType::CLI);
    }

    public function install(): ApplicationInterface
    {
        foreach($this->getRequiredModules() as $m){
            $this->installModule($m);
        }
        return $this;
    }

    public function installModule(string $module_class): ApplicationInterface
    {
        $this->addInstalledModule($module_class);
        return $this;
    }
}