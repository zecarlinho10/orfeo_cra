<?php

/**
 * Generated 2021-03-12T16:14:09+00:00 16.0.21103.12002
 */
namespace Office365\SharePoint;

use Office365\Runtime\ResourcePath;
class EmployeeEngagement extends BaseEntity
{
    /**
     * @return AppConfiguration
     */
    public function getAppConfiguration()
    {
        if (!$this->isPropertyAvailable("AppConfiguration")) {
            $this->setProperty("AppConfiguration", new AppConfiguration($this->getContext(), new ResourcePath("AppConfiguration", $this->getResourcePath())));
        }
        return $this->getProperty("AppConfiguration");
    }
}