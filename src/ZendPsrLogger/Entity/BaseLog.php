<?php

namespace ZendPsrLogger\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Description of BaseLog
 *
 * @author seyfer
 * @ORM\MappedSuperclass
 */
abstract class BaseLog
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $timestamp;

    /**
     *
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $priority;

    /**
     *
     * @var string
     * @ORM\Column(name="priority_name", type="string", nullable=false)
     */
    protected $priorityName;

    /**
     *
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    protected $message;

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function getPriorityName()
    {
        return $this->priorityName;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setTimestamp(\DateTime $timestamp)
    {
        $this->timestamp = $timestamp;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function setPriorityName($priorityName)
    {
        $this->priorityName = $priorityName;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray(array $array)
    {
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }

    private function set($key, $value)
    {
        $workKey = $key;

        //fix underscore
        if (strpos($workKey, "_") !== FALSE) {

            $workKey = preg_replace_callback('/_(.?)/', function($a) {
                return strtoupper($a[1]);
            }, $key);
        }

        if (property_exists($this, $workKey)) {
            $setter = "set" . ucfirst($workKey);

            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

}
