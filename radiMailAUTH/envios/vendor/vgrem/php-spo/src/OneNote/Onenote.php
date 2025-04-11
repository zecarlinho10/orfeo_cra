<?php

/**
 * Modified: 2020-05-27T08:42:11+00:00 
 */
namespace Office365\OneNote;


use Office365\Entity;
use Office365\EntityCollection;
use Office365\OneNote\Pages\OnenotePageCollection;
use Office365\OneNote\Sections\OnenoteSection;
use Office365\Runtime\ResourcePath;
/**
 *  "The entry point for OneNote resources."
 */
class Onenote extends Entity
{
    /**
     * The pages in all OneNote notebooks that are owned by the user or group.  Read-only. Nullable.
     * @return OnenotePageCollection
     */
    public function getPages()
    {
        return $this->getProperty("Pages",
            new OnenotePageCollection($this->getContext(),new ResourcePath("Pages", $this->getResourcePath())));
    }

    /**
     * @return EntityCollection
     */
    public function getSections()
    {
        return $this->getProperty("Sections",
            new EntityCollection($this->getContext(),
                new ResourcePath("Sections",
                $this->getResourcePath()), OnenoteSection::class));
    }
}