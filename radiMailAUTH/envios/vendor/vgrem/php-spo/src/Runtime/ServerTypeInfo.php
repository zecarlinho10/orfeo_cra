<?php

namespace Office365\Runtime;

use Exception;
use Office365\GraphServiceClient;
use Office365\SharePoint\ClientContext;

class ServerTypeInfo
{

    /**
     * @param string $namespace
     * @param string $name
     * @param boolean $collection
     */
    public function __construct($namespace = null, $name = null, $collection=false)
    {
        $this->Namespace = $namespace;
        $this->Name = $name;
        $this->Id = null;
        $this->Collection = $collection;
    }

    /**
     * @param string $value
     * @return ServerTypeInfo
     */
    public static  function fromFullName($value){
        $parts = explode(".", $value);
        $typeName = end($parts);
        $namespace = implode(".", array_slice($parts, 0,-1));
        return new ServerTypeInfo($namespace, $typeName);
    }

    /**
     * @return ServerTypeInfo
     * @throws Exception
     */
    public static function primitive($typeName, $isCollection = false)
    {
        if (array_key_exists($typeName, self::$PrimitiveTypeMappings)) {
            $primitiveTypeName = self::$PrimitiveTypeMappings[$typeName];
            return new ServerTypeInfo("Edm", $primitiveTypeName, $isCollection);
        }
        throw new Exception("Unknown primitive type: $typeName");
    }

    /**
     * @param ClientValue|ClientObject $type
     * @return ServerTypeInfo
     */
    public static function resolve($type)
    {
        if($type instanceof ClientValueCollection || $type instanceof ClientObjectCollection){
            $itemTypeName = $type->getItemTypeName();
            $collection = true;
        }
        else {
            $itemTypeName = get_class($type);
            $collection = false;
        }
        $parts = explode("\\", $itemTypeName);
        $typeName = end($parts);
        //$namespace = implode(".", array_slice($parts, 1, count($parts) - 2));
        return new ServerTypeInfo(null, $typeName, $collection);
    }

    /**
     * @param ClientRuntimeContext $context
     */
    public function patch($context){
        if ($context instanceof ClientContext) {
            if(is_null($this->Namespace)) $this->Namespace = "SP";
        }
        else if ($context instanceof GraphServiceClient) {
            if(is_null($this->Namespace)) $this->Namespace = "microsoft.graph";
            $this->Name = lcfirst($this->Name);
        }
        return $this;
    }

    public function __toString()
    {
        $fullName = "$this->Namespace.$this->Name";
        return $this->Collection ? "Collection($fullName)" : $fullName;
    }


    /**
     * @var string
     */
    public $Id;

    /**
     * @var string
     */
    public $Namespace;

    /**
     * @var string
     */
    public $Name;


    /**
     * @var boolean
     */
    public $Collection;

    /**
     * @var string[]
     */
    static $PrimitiveTypeMappings = array(
        "string" => "String",
        "bool" => "Boolean",
        "integer" => "Int32",
        "double" => "Double"
    );

}