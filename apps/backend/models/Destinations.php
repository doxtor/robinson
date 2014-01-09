<?php
namespace Robinson\Backend\Models;
class Destinations extends \Phalcon\Mvc\Model
{
    const STATUS_INVISIBLE = 0;
    const STATUS_VISIBLE = 1;
    
    protected static $statusMessages = array
    (
        self::STATUS_INVISIBLE => 'nevidljiv',
        self::STATUS_VISIBLE => 'vidljiv',
    );
    
    protected $destinationId;
    
    protected $destination;
    
    protected $description;
    
    protected $status;
    
    protected $createdAt;
    
    protected $updatedAt;
    
    /**
     * Initialization method.
     * 
     * @return void
     */
    public function initialize()
    {
        $this->setSource('Destinations');
        $this->hasMany('destinationId', 'Robinson\Backend\Models\DestinationImages', 
            'destinationId');
        $this->belongsTo('categoryId', 'Robinson\Backend\Models\Category', 'categoryId');
    }
    
    /**
     * Getter method for destination name.
     *  
     * @param bool $escapeHtml flag
     * 
     * @return string
     */
    public function getDestination($escapeHtml = true)
    {
        return $this->getDI()->getShared('escaper')->escapeHtml($this->destination);
    }
    
    /**
     * Sets destination name.
     * 
     * @param string $destination destination name
     * 
     * @return \Robinson\Backend\Models\Destinations
     */
    public function setCategory($destination)
    {
        $this->destination = $destination;
        return $this;
    }
    
    /**
     * Sets destination description.
     * 
     * @param string $description description
     * 
     * @return \Robinson\Backend\Models\Destinations
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * Gets destination description.
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Sets destination status.
     * 
     * @param int $status category status
     * 
     * @return \Robinson\Backend\Models\Destinations
     */
    public function setStatus($status)
    {
        $this->status = (int) $status;
        return $this;
    }
    
    /**
     * Gets destination status.
     * 
     * @return int
     */
    public function getStatus()
    {
        return (int) $this->status;
    }
    
    /**
     * Is destination visible?
     * 
     * @return bool
     */
    public function isVisible()
    {
        return ($this->getStatus() === self::STATUS_VISIBLE);
    }
   
    /**
     * Gets destinationId.
     * 
     * @return int
     */
    public function getDestinationId()
    {
        return $this->destinationId;
    }
    
    /**
     * Sets destination id.
     * 
     * @param int $id id
     * 
     * @return \Robinson\Backend\Models\Destinations
     */
    public function setDestinationId($id)
    {
        $this->destinationId = (int) $id;
        return $this;
    }
    
    /**
     * Sets datetime when destination was created.
     * 
     * @param \DateTime $datetime datetime object whcih will be used for setting datetime
     * 
     * @return \Robinson\Backend\Models\Destination
     */
    public function setCreatedAt(\DateTime $datetime)
    {
        $this->createdAt = $datetime->format('Y-m-d H:i:s');
        return $this;
    }
    
    /**
     * Sets datetime when destination was last updated.
     * 
     * @param \DateTime $datetime datetime object which will be used for setting datetime
     * 
     * @return \Robinson\Backend\Models\Destinations
     */
    public function setUpdatedAt(\DateTime $datetime)
    {
        $this->updatedAt = $datetime->format('Y-m-d H:i:s');
        return $this;
    }
    
    /**
     * Gets createdAt destination datetime.
     * 
     * @param string $format date format
     * 
     * @return string
     */
    public function getCreatedAt($format = 'd.m.Y. H:i:s')
    {
        return (new \DateTime($this->createdAt, new \DateTimeZone('Europe/Belgrade')))->format($format);
    }
    
    /**
     * Gets last updated destination datetime.
     * 
     * @param string $format date format
     * 
     * @return string
     */
    public function getUpdatedAt($format = 'd.m.Y. H:i:s')
    {
        return (new \DateTime($this->updatedAt, new \DateTimeZone('Europe/Belgrade')))->format($format);
    }
    
    /**
     * Returns human readable status text.
     * 
     * @return string
     */
    public static function getStatusMessages()
    {
        return self::$statusMessages;
    }
    
    /**
     * Assembles and returns url for destination update page.
     * 
     * @param bool $asArray flag, if set to true, url params will be returned as array
     * 
     * @return string|array
     */
    public function getUpdateUrl($asArray = false)
    {
        $fragments = array
        (
            'for' => 'admin-update', 
            'controller' => 'destination', 
            'action' => 'update', 
            'id' => $this->getDestinationId(),
        );
        if ($asArray)
        {
          return $fragments;  
        }
        
        return $this->getDI()->get('url')->get($fragments);
    }
    
    /**
     * Fetches related images.
     * 
     * @return \Phalcon\Mvc\Model\Resultset\Simple
     */
    public function getImages()
    {
        return $this->getRelated('Robinson\Backend\Models\DestinationImages', array
        (
            'order' => 'sort ASC',
        ));
    }
    
    /**
     * Fetches destination category.
     * 
     * @return \Phalcon\Mvc\Model\Resultset\Simple
     */
    public function getCategory()
    {
        return $this->getRelated('Robinson\Backend\Models\Category');
    }
}