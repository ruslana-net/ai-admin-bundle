<?php

namespace Ai\AdminBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\ClassUtils;

class ModelToIdPropertyTransformer implements DataTransformerInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $om;

    protected $className;

    protected $property;

    protected $multiple;

    protected $toStringCallback;

    protected $create;


    /**
     * @param ObjectManager $om
     * @param $className
     * @param $property
     * @param bool $multiple
     * @param null $toStringCallback
     * @param bool $create
     */
    public function __construct(ObjectManager $om, $className, $property, $multiple=false, $toStringCallback=null, $create=false)
    {
        $this->om               = $om;
        $this->className        = $className;
        $this->property         = $property;
        $this->multiple         = $multiple;
        $this->toStringCallback = $toStringCallback;
        $this->create           = $create;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($entityOrCollection)
    {
        $result = array('identifiers' => array(), 'labels' => array());

        if (!$entityOrCollection) {
            return $result;
        }
        if ($this->multiple) {
            if (substr(get_class($entityOrCollection), -1 * strlen($this->className)) == $this->className) {
                throw new \InvalidArgumentException('A multiple selection must be passed a collection not a single value. Make sure that form option "multiple=false" is set for many-to-one relation and "multiple=true" is set for many-to-many or one-to-many relations.');
            } elseif ($entityOrCollection instanceof \ArrayAccess) {
                $collection = $entityOrCollection;
            } else {
                throw new \InvalidArgumentException('A multiple selection must be passed a collection not a single value. Make sure that form option "multiple=false" is set for many-to-one relation and "multiple=true" is set for many-to-many or one-to-many relations.');
            }
        } else {
            if (substr(get_class($entityOrCollection), -1 * strlen($this->className)) == $this->className) {
                $collection = array($entityOrCollection);
            } elseif ($entityOrCollection instanceof \ArrayAccess) {
                throw new \InvalidArgumentException('A single selection must be passed a single value not a collection. Make sure that form option "multiple=false" is set for many-to-one relation and "multiple=true" is set for many-to-many or one-to-many relations.');
            } else {
                $collection = array($entityOrCollection);
            }
        }

        if (empty($this->property)) {
            throw new RuntimeException('Please define "property" parameter.');
        }

        foreach ($collection as $entity) {
            $id  = current($this->getIdentifierValues($entity));

            if ($this->toStringCallback !== null) {
                if (!is_callable($this->toStringCallback)) {
                    throw new RuntimeException('Callback in "to_string_callback" option doesn`t contain callable function.');
                }

                $label = call_user_func($this->toStringCallback, $entity, $this->property);
            } else {
                try {
                    $label = (string) $entity;
                } catch (\Exception $e) {
                    throw new RuntimeException(sprintf("Unable to convert the entity %s to String, entity must have a '__toString()' method defined", ClassUtils::getClass($entity)), 0, $e);
                }
            }

            $result['identifiers'][] = $id;
            $result['labels'][] = $label;
        }

        return $result;
    }

    /**
     * @param mixed $value
     * @return ArrayCollection|mixed|null|object
     */
    public function reverseTransform($value)
    {
        $collection = new ArrayCollection();

        if (empty($value) || empty($value['identifiers'])) {
            if (!$this->multiple) {
                return null;
            } else {
                return $collection;
            }
        }

        if (!$this->multiple) {
            $value = current($value['identifiers']);

            return $this->find($value);
        }

        foreach ($value['identifiers'] as $id) {
            $collection->add($this->find($id));
        }

        return $collection;
    }

    /**
     * @param $value
     * @return object
     */
    protected function find($value){
        if ( !$entity = $this->om
                ->getRepository($this->className)
                ->findOneBy(array($this->getSingleIdentifierFieldName() => $value))
            )
        {
            if ( $this->create ) {
                $propertySetter = 'set'.self::camelize($this->property);
                $entity = new $this->className;
                $entity->$propertySetter($value);
            }
        }

        return $entity;
    }

    /**
     * @param $entity
     * @return array
     */
    public function getIdentifierValues($entity)
    {
        $class = $this->getMetadata(ClassUtils::getClass($entity));

        $identifiers = array();

        foreach ($class->getIdentifierValues($entity) as $value) {
            if (!is_object($value)) {
                $identifiers[] = $value;
                continue;
            }

            $class = $this->getMetadata(ClassUtils::getClass($value));

            foreach ($class->getIdentifierValues($value) as $value) {
                $identifiers[] = $value;
            }
        }

        return $identifiers;
    }

    /**
     * @return mixed
     */
    protected function getSingleIdentifierFieldName(){
        return $this->getMetadata($this->className)->getSingleIdentifierFieldName();
    }

    /**
     * @param $className
     * @return \Doctrine\Common\Persistence\Mapping\ClassMetadata
     */
    protected function getMetadata($className){
        return $this->om->getClassMetadata($className);
    }

    /**
     * Camelize a string
     *
     * @static
     *
     * @param string $property
     *
     * @return string
     */
    public static function camelize($property)
    {
        return preg_replace_callback('/(^|[_. ])+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
        }, $property);
    }
}