<?php
declare(strict_types=1);

namespace KnotModule\KnotService\Test;

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
        $this->installModules($this->getRequiredModules());
        return $this;
    }

    public function installModules(array $modules): ApplicationInterface
    {
        foreach($modules as $m){
            $this->addInstalledModule($m);
        }
        return $this;
    }
}