<?php
namespace Robinson\Backend\Models;
class Package extends \Phalcon\Mvc\Model
{
    const STATUS_INVISIBLE = 0;
    const STATUS_VISIBLE = 1;
    
    protected static $statusMessages = array
    (
        self::STATUS_INVISIBLE => 'nevidljiv',
        self::STATUS_VISIBLE => 'vidljiv',
    );
    
    protected $packageId;
    
    protected $package;
    
    protected $description;
    
    protected $tabs;
    
    protected $price;
    
    protected $pdf;
    
    protected $status;
    
    protected $createdAt;
    
    protected $updatedAt;
    
    protected $destinationId;
    
    /**
     *
     * @var \Phalcon\Http\Request\File  
     */
    protected $uploadedPdf;
    
    /**
     *
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;
    
    /**
     * Initialization.
     * 
     * @return void
     */
    public function initialize()
    {
        $this->setSource('packages');
        $this->belongsTo('destinationId', 'Robinson\Backend\Models\Destination', 'destinationId', array
        (
            'alias' => 'destination', 
        ));
        $this->hasMany('packageId', 'Robinson\Backend\Models\Images\Package', 'packageId', array
        (
            'alias' => 'images',
        ));
        
        $this->addBehavior(new \Phalcon\Mvc\Model\Behavior\Timestampable(array
        (
            'beforeValidationOnCreate' => array
            (
                'field' => 'createdAt',
                'format' => 'Y-m-d H:i:s',
            ),
        )));
            
        $this->addBehavior(new \Phalcon\Mvc\Model\Behavior\Timestampable(array
        (
            'beforeValidationOnCreate' => array
            (
                'field' => 'updatedAt',
                'format' => 'Y-m-d H:i:s',
            ),
        )));
            
        $this->addBehavior(new \Phalcon\Mvc\Model\Behavior\Timestampable(array
        (
            'beforeValidationOnUpdate' => array
            (
                'field' => 'updatedAt',
                'format' => 'Y-m-d H:i:s',
            ),
        )));
    }
    
    /**
     * Sets fs service on construct.
     * 
     * @return void
     */
    public function onConstruct()
    {
        if (!$this->filesystem)
        {
            $this->filesystem = $this->getDI()->getShared('fs');
        }
        
        $this->imagesContainer = new \SplObjectStorage();
    }
    
    /**
     * Gets packageId.
     * 
     * @return int
     */
    public function getPackageId()
    {
        return (int) $this->packageId;
    }

    /**
     * Sets package name.
     * 
     * @param string $package package name
     * 
     * @return \Robinson\Backend\Models\Package
     */
    public function setPackage($package)
    {
        $this->package = $package;
        return $this;
    }
    
    /**
     * Gets package name.
     * 
     * @return string package name
     */
    public function getPackage()
    {
        return $this->package;
    }
    
    /**
     * Sets package description.
     * 
     * @param string $description description
     * 
     * @return \Robinson\Backend\Models\Package
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * Gets package description.
     * 
     * @return string package description
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Sets tabs content.
     * 
     * @param string $tabs tabs
     * 
     * @return \Robinson\Backend\Models\Package
     */
    public function setTabs($tabs)
    {
        $this->tabs = $tabs;
        return $this;
    }
    
    /**
     * Gets tabs.
     * 
     * @todo split tabs in array and use "-----" delimiter
     * 
     * @return type
     */
    public function getTabs()
    {
        return $this->tabs;
    }
    
    /**
     * Sets package starting price.
     * 
     * @param int $price package starting price
     * 
     * @return \Robinson\Backend\Models\Package
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
    
    /**
     * Gets package lowest price.
     * 
     * @return int price lowest package price
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    /**
     * Sets pdf.
     * 
     * @param string $pdf pdf path
     * 
     * @return \Robinson\Backend\Models\Package
     */
    protected function setPdf($pdf)
    {
        $this->pdf = $pdf;
        return $this;
    }
    
    /**
     * Gets pdf base file name.
     * 
     * @return string pdf's base file name
     */
    public function getPdf()
    {
        return $this->pdf;
    }
    
    /**
     * Sets package status.
     * 
     * @param int $status status
     * 
     * @return \Robinson\Backend\Models\Package
     */
    public function setStatus($status)
    {
        $this->status = (int) $status;
        return $this;
    }
    
    /**
     * Gets package status.
     * 
     * @return int
     */
    public function getStatus()
    {
        return (int) $this->status;
    }

    /**
     * Sets uploaded pdf file.
     * 
     * @param \Phalcon\Http\Request\File $pdf uploaded pdf
     * 
     * @return \Robinson\Backend\Models\Package
     */
    public function setUploadedPdf(\Phalcon\Http\Request\File $pdf)
    {
        $this->uploadedPdf = $pdf;
        return $this;
    }
    
    /**
     * Called when new package is created.
     * 
     * @param array $data      data
     * @param array $whiteList whitelist
     * 
     * @return void
     */
    public function create($data = null, $whiteList = null)
    {
        return $this->parentCreate($data, $whiteList);
    }
    
    /**
     * Executed on update.
     * 
     * @param array $data      data
     * @param array $whiteList whitelist
     * 
     * @return bool
     */
    public function update($data = null, $whiteList = null)
    {
        return $this->parentUpdate($data, $whiteList);
    }
    
    /**
     * Sets package destination.
     * 
     * @param \Robinson\Backend\Models\Destination $destination destination model
     * 
     * @return \Robinson\Backend\Models\Package
     */
    public function setDestination(\Robinson\Backend\Models\Destination $destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * Event which is trigger before calling self::parentCreate.
     * 
     * @return void
     */
    public function beforeValidationOnCreate()
    {
        if ($this->uploadedPdf instanceof \Phalcon\Http\Request\File)
        {
            $this->setPdf($this->uploadedPdf->getName());
        }
        
        if (is_null($this->status))
        {
            $this->status = self::STATUS_INVISIBLE;
        }
    }
    
    /**
     * Event which is trigger before calling self::parentUpdate.
     * 
     * @return void
     */
    public function beforeValidationOnUpdate()
    {   
        if ($this->uploadedPdf instanceof \Phalcon\Http\Request\File)
        {
            $this->setPdf($this->uploadedPdf->getName());
        }
    }
    
    /**
     * Moves file to appropriate folder.
     * 
     * @return void
     */
    public function afterSave()
    {
        $destinationFolder = $this->getDI()->getShared('config')->application->packagePdfPath;
        $destinationPackageFolder = $destinationFolder . '/' . $this->packageId;
        
        if (!$this->filesystem->exists($destinationPackageFolder))
        {
            $this->filesystem->mkdir($destinationPackageFolder);
        }
        
        if (!$this->uploadedPdf)
        {
            return;
        }
        
        if (!$this->uploadedPdf->moveTo($destinationPackageFolder . '/' . $this->uploadedPdf->getName()))
        {
           throw new \Robinson\Backend\Models\Exception(sprintf('Unable to move pdf file "%s" to destination dir "%s"', 
               $this->uploadedPdf->getName(), $destinationPackageFolder));
        }
    }
    
    /**
     * Overriden create method.
     * 
     * @param array $data      data
     * @param array $whiteList data
     * 
     * @return bool
     */
    public function parentCreate($data = null, $whiteList = null)
    {
        return parent::create($data, $whiteList);
    }
    
    /**
     * Overriden update method.
     * 
     * @param array $data      data
     * @param array $whiteList data
     * 
     * @return bool
     */
    public function parentUpdate($data = null, $whiteList = null)
    {
        return parent::update($data, $whiteList);
    }
    
    /**
     * Retrieves destination to which package belongs to.
     * 
     * @return \Robinson\Backend\Models\Destination
     */
    public function getDestination()
    {
        return $this->getRelated('destination');
    }
    
    /**
     * Returns images sorted by sort.
     * 
     * @return \Phalcon\Mvc\Model\Resultset\Simple
     */
    public function getImages()
    {
        return $this->getRelated('images', array
        (
            'order' => 'sort ASC',
        ));
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
    
    
}