<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\Image")
 * @Table(name="images")
 */
class Image
{
    /**
     * Define const for image categories / folder names 
     */
    const PROFILE = "profiles";
    const GROUP = "groups";
    
    /**
     * Define const for image types 
     */
    
    const THUMBNAIL = "thumbnail";
    const SMALL = "small";
    const LARGE = "large";
    
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $_id;
    
    /**
     *  Base image path
     */
    private $_base;
    
    /**
     * Path to thumbnail image
     * @Column(type="text", name="thumbnail", nullable="true") 
     */
    private $_thumbnail;
    
    /**
     * Path to small image
     * @Column(type="text", name="small", nullable="true") 
     */
    private $_small;
    
    /**
     * Path to large image
     * @Column(type="text", name="large", nullable="true") 
     */
    private $_large;
    
    /**
     * Image category
     * @Column(type="text", name="category") 
     */
    private $_category;
    
    /**
     * Raw image type
     */
    protected $_type;
    
    protected $_ext;
    
    /**
     *  
     */
    public function __construct($category, $tmp_name)
    {
        $this->_base = APPLICATION_PATH."/../public/img/";
        $this->_category = $category;
        
        // Load image into memory for editing
        $image = $this->load($tmp_name);
        
        // Create thumbnail image
        $thumbnail = $this->resize(self::THUMBNAIL, $image);
        
        // Save thumbnail image
        $this->_thumbnail = $this->save($thumbnail, self::THUMBNAIL);
        
        // Create small image
        $small = $this->resize(self::SMALL, $image);
        
        // Save small image
        $this->_small = $this->save($small, self::SMALL);
        
        // Create large image
        $large = $this->resize(self::LARGE, $image);
        
        // Save large image
        $this->_large = $this->save($large, self::LARGE);
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getBasePath()
    {
        return $this->_base;
    }
    
    public function setBasePath($base)
    {
        $this->_base = $base;
    }
    
    public function getThumbnail()
    {
        return $this->_thumbnail;
    }
    
    public function setThumbnail($thumb)
    {
        $this->_thumbnail = $thumb;
    }
    
    public function getSmall()
    {
        return $this->_small;
    }
    
    public function setSmall($small)
    {
        $this->_small = $small;
        return $this;
    }
    
    public function getLarge()
    {
        return $this->_large;
    }
    
    public function setLarge($large)
    {
        $this->_large = $large;
        return $this;
    }
    
    public function getType()
    {
        return $this->_type;
    }
    
    public function setType($type)
    {
        $this->_type = $type;
        return $this;
    }
    
    protected function load($filename)
    {
        $image_info = getimagesize($filename);
        $this->_type = $image_info[2];
        
        switch($this->_type)
        {
            case IMAGETYPE_JPEG:
                $this->_image = imagecreatefromjpeg($filename);
                $this->_ext = "jpg";
                break;
            case IMAGETYPE_GIF:
                $this->_image = imagecreatefromgif($filename);
                $this->_ext = "gif";
                break;
            case IMAGETYPE_PNG:
                $this->_image = imagecreatefrompng($filename);
                $this->_ext = "png";
                break;
        }
    }
    
    protected function save($image, $type, $compression = 75, $permission = null)
    {
        $filename = $type."_".md5(date('H:i:s d-m-Y')).".".$this->_ext;
        
        switch($this->_type)
        {
            case IMAGETYPE_JPEG:
                imagejpeg($image, $this->_base.$this->_category."/".$filename, $compression);
                break;
            case IMAGETYPE_GIF:
                imagegif($image, $this->_base.$this->_category."/".$filename);
                break;
            case IMAGETYPE_PNG:
                imagepng($image, $this->_base.$this->_category."/".$filename, $compression);
                break;
        }
        
        if($permission != null)
            chmod($filename, $permission);
        
        return $filename;
    }
    
    protected function resize($type)
    {
        switch($type)
        {
            case self::THUMBNAIL:
                $width = 90;
                $height = 90;
                
                $new_image = imagecreatetruecolor($width, $height);
                imagecopyresampled($new_image, $this->_image, 0, 0, 0, $height, $width, $height, $this->getWidth(), $this->getHeight()-$height);
                break;
            case self::SMALL:
                $width = 138;
                $height = 138;
                
                $new_image = imagecreatetruecolor($width, $height);
                imagecopyresampled($new_image, $this->_image, 0, 0, 0, $height, $width, $height, $this->getWidth(), $this->getHeight()-$height);
                break;
            case self::LARGE:
                $width = 160;
                
                $ratio = $width / $this->getWidth();
                $height = $this->getheight() * $ratio;
                
                $new_image = imagecreatetruecolor($width, $height);
                imagecopyresampled($new_image, $this->_image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
                break;
            default:
                break;
        }
        
        return $new_image;
    }
    
    function getWidth()
    {
 
      return imagesx($this->_image);
   }
   
   function getHeight()
   {
 
      return imagesy($this->_image);
   }
}