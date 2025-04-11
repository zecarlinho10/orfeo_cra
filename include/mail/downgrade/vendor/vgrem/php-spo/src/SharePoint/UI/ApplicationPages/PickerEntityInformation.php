<?php

/**
 * Generated 2019-11-17T18:33:00+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint\UI\ApplicationPages;

use Office365\SharePoint\BaseEntity;


class PickerEntityInformation extends BaseEntity
{
    /**
     * @return PickerEntityInformationRequest
     */
    public function getEntity()
    {
        return $this->getProperty("Entity", new PickerEntityInformationRequest());
    }

    /**
     *
     * @return self
     * @var PickerEntityInformationRequest
     */
    public function setEntity($value)
    {
        return $this->setProperty("Entity", $value, true);
    }
    /**
     * @return integer
     */
    public function getTotalMemberCount()
    {
        return $this->getProperty("TotalMemberCount");
    }
    /**
     * @var integer
     */
    public function setTotalMemberCount($value)
    {
        $this->setProperty("TotalMemberCount", $value, true);
    }
}