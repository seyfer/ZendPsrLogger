<?php

namespace ZendPsrLogger\Doctrine\ORM;

use Doctrine\Common\Persistence\Proxy;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\MappingException;

class EntityManagerHelper
{

    /**
     * @param EntityManager $em
     * @param $entityName
     * @return bool
     */
    public static function isEntity(EntityManager $em, $entityName)
    {
        try {
            $em->getRepository($entityName);

            return TRUE;
        } catch (MappingException $e) {
            return FALSE;
        }
    }

    /**
     * @param EntityManager $em
     * @param $object
     * @return bool
     */
    public static function isEntityValid(EntityManager $em, $object)
    {
        if (is_object($object)) {
            $class = ($object instanceof Proxy) ?
                get_parent_class($object) : get_class($object);
        } else {
            $class = $object;
        }

        return !$em->getMetadataFactory()->isTransient($class);
    }

}
