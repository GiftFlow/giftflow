<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* Handle common image manipulation using the CI image_lib class.
*
* @package    Libraries
* @name Images.php
* @version 2.0
* @author Joost van Veen
* @copyright Accent Interactive
* @created: 7 dec 2009
*/
class Images
{
    
    /**
     * The CI super object
     * @var object
     */
    private $_ci;
    
    /**
     * Default image library. I highly recommend changing this to imagemagick,
     * because its performance is *way* better than GD2.
     *
     * If you set $config['image_library'] in a config file, it will override
     * the default value for this variable.
     * @var string
     */
    public $image_library = 'GD2';
    
    /**
     * Path for default image library.
     * If you use image magick on Linux, this is probabaly /usr/bin/
     * If you use XAMPP this is probably sth like c:\xampp\imagemagick-6.2.8-q16
     * Defaults to null, for GD2
     *
     * If you set $config['library_path'] in a config file, it will override
     * the default value for this variable.
     * @var mixed
     */
    public $library_path = null;

    public function __construct ()
    {
        // Instantiate the CI libraries so we can work with them
        $this->_ci = & get_instance();
        
        // Load image library if necessary
        if (! isset($this->_ci->image_lib)) {
            $this->_ci->load->library('image_lib');
        }

        // Set image library and path from settings in configuration file.
        // If you did not set these settings in a config file the settings remain default.
        $this->_ci->config->item('image_library') === false || $this->image_library = $this->_ci->config->item('image_library');
        $this->_ci->config->item('library_path') === false || $this->library_path = $this->_ci->config->item('library_path');
    }

    /**
     * Calls $this->square and $this->resize. The squared image is always a copy
     * of the original, since it is a thumbnail.
     *
     * @param $originalFile Full path and filename of original image
     * @param $newFile The full destination path and filename
     * @param $newSize (optional) The new size of the squared image
     * @param integer $offset (optional) Offset for either x or y axis
     * @return void
     */
    function squareThumb ($originalFile, $newFile, $newSize = 120, $offset = 0)
    {
        $this->square($originalFile, $newFile, $offset);
        $this->resize($newFile, $newSize, $newSize, $newFile, false);
    }

    /**
     *
     * @param $originalFile Full path and filename of original image
     * @param $newWidth (optional) The width of the resized image
     * @param $newHeight (optional) The height of the resized image
     * @param $newFile (optional) The full destination path and filename
     * @param $enlarge (optional) Whether a smaller original should be enlarged
     * @return mixed False if no action was performed, else void
     */
    function resize ($originalFile, $newWidth = 300, $newHeight = 400, $newFile = '', $enlarge = false)
    {
        
        // Abort if image does not exist
        if (! file_exists($originalFile) || ! is_file($originalFile)) {
            return false;
        }
        
        // If we should not enlarge we need to check the size of the original image
        if ($enlarge == false) {
            
            // Do not resize if the image is already smaller than $newWidth and $newHeight
            $imgData = $this->getSize($originalFile);
            if ($imgData['width'] <= $newWidth && $imgData['height'] <= $newHeight) {
                if ($newFile == '') {
                    return false;
                }
                else {
                    // We need to copy the image to the new path
                    $this->delete($newFile);
                    copy($originalFile, $newFile);
                    return false;
                }
            }
        }
        
        // Configure CI image_lib
        $config['image_library'] = $this->image_library;
        $config['library_path'] = $this->library_path;
        $config['maintain_ratio'] = true;
        $config['width'] = $newWidth;
        $config['height'] = $newHeight;
        $config['source_image'] = $originalFile;
        $config['quality'] = "20%";
        if ($newFile) {
            $config['new_image'] = $newFile;
        }
        $this->_ci->image_lib->initialize($config);
        
        // Resize the image
        if (! $this->_ci->image_lib->resize()) {
            show_error($this->_ci->image_lib->display_errors());
        }
        
        // Clear lib so we can perform another image action
        $this->_ci->image_lib->clear();
    }

    /**
     * Crop an image so that it becomes square. I fyou need a square thumbnail,
     * run this method first and resize afterwards.
     *
     * By default the original image is cropped and overwritten, but you can
     * supply a destination path if you wish to retain the original image and
     * create a new, cropped version of that image somewhere else.  
     *
     * By default the image is cropped from the center, but you can supply an
     * optional offset parameter.
     *
     * @param string $originalFile The full path and filename of the image to be cropped
     * @param string $newFile (optional) The full destination path and filename
     * @param integer $offset (optional) Offset for either x or y axis
     * @return mixed False if no action was performed, else void
     */
    function square ($originalFile, $newFile = '', $offset = 0)
    {
        
        // Abort if image does not exist
        if (! file_exists($originalFile) || ! is_file($originalFile)) {
            return false;
        }
        
        // Get original image data
        $imgData = $this->getSize($originalFile);
        
        // Set image lib config
        // Best cropping results on all three main image formats (png, gif, jpg)
        // are with GD2
        $config['image_library'] = 'GD2';
        $config['library_path'] = null;
        $config['source_image'] = $originalFile;
        $config['maintain_ratio'] = false;
        if ($newFile) {
            $config['new_image'] = $newFile;
        }
        
        // Crop only if image is not square yet
        if ($imgData['width'] != $imgData['height']) {
            
            // Set x and y axis for cropping. If x and y axis have not been
            // passed as parameter we will crop from the center of the image.
            if ($imgData['width'] > $imgData['height']) { // Landscape, crop left & right
                $config['width'] = $imgData['height'];
                $config['height'] = $imgData['height'];
                $config['x_axis'] = $offset > 0 ? $offset : ($imgData['width'] - $config['width']) / 2;
            }
            else { // Portrait, crop top & bottom
                $config['width'] = $imgData['width'];
                $config['height'] = $imgData['width'];
                $config['y_axis'] = $offset > 0 ? $offset : ($imgData['height'] - $config['height']) / 2;
            }
        }
        else {
            // Image is already square. No cropping required.
            if ($newFile == '') {
                return false;
            }
            else {
                // We need to copy the image to the new path
                $this->delete($newFile);
                copy($originalFile, $newFile);
                return false;
            }
        }
        
        // Crop image
        $this->_ci->image_lib->initialize($config);
        if (! $this->_ci->image_lib->crop()) {
            show_error($this->_ci->image_lib->display_errors());
        }
        
        // Clear lib so we can perform another image action
        $this->_ci->image_lib->clear();
    }

    /**
     * Return an array that contains size and mime of an image. Uses getimagesize().
     *
     * Return array indexes:
     * 'width'
     * 'height'
     * 'mime'
     * @param string $image Full path to image
     * @return array that contains size and mime of an image
     */
    public function getSize ($image)
    {
        $imgData = getimagesize($image);
        $retval['width'] = $imgData[0];
        $retval['height'] = $imgData[1];
        $retval['mime'] = $imgData['mime'];
        return $retval;
    }

    /**
     * Delete an image if it exists.
     * @param string $image Full path and filename
     */
    public function delete ($image)
    {
        if (file_exists($image) && is_file($image)) {
            unlink($image);
        }
    }
} 