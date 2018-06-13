<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Text Plain OpenStreetMap Link Transformations plugin for phpMyAdmin
 *
 * @package    PhpMyAdmin-Transformations
 * @subpackage OpenStreetMap
 * @author     Romain de Bossoreille
 */
namespace PhpMyAdmin\Plugins\Transformations\Output;

use PhpMyAdmin\Plugins\TransformationsPlugin;

if (!defined('PHPMYADMIN')) {
    exit;
}

/**
 * Handles the OpenStreetMap link transformation for text plain
 *
 * @package    PhpMyAdmin-Transformations
 * @subpackage OpenStreetMap
 */
// @codingStandardsIgnoreLine
class Text_Plain_Openstreetmap extends TransformationsPlugin
{
    /**
     * Constructor of the class
     */
    public function __construct()
    {
        if(!array_key_exists('OpenStreetMapLink', $GLOBALS['cfg']['DefaultTransformations']))
        {
            $GLOBALS['cfg']['DefaultTransformations']['OpenStreetMapLink'] = array('');
        }
    }
    
    /**
     * Gets the transformation description of the specific plugin
     *
     * @return string
     */
    public static function getInfo()
    {
        return __(
            'Displays an OpenStreetMap link; the column contains the OpenStreetMap ID. Three'
            . ' patterns allowed:'
            . ' 1. \'w1234\' i.e. the first letter of the type of item (n for node, w for way, r for relation) followed by the id;'
            . ' 2. \'way1234\' i.e. the type of item (\'node\', \'way\' or \'relation\') followed by the id;'
            . ' 3. \'1234\' with the optional option set to either \'node\', \'way\' or \'relation\'.'
        );
    }

    /**
     * Does the actual work of the transformation plugin.
     *
     * @param string $buffer  text to be transformed
     * @param array  $options transformation options
     * @param string $meta    meta information
     *
     * @return string
     */
    public function applyTransformation($buffer, array $options = array(), $meta = '')
    {
        $cfg = $GLOBALS['cfg'];
        $options = $this->getOptions($options, $cfg['DefaultTransformations']['OpenStreetMapLink']);
        $type = '';
        $id = 0;
        
        // Cases 'n1234' or 'w1234' or 'r1234'
        $matches = null;
        $matchPattern = preg_match('/^(n|node|w|way|r|relation)([0-9]*)$/', $buffer, $matches, PREG_OFFSET_CAPTURE, 0);
        if($matchPattern == 1)
        {
            if($matches[1][0] == 'n' || $matches[1][0] == 'node')
            {
                $type = 'node';
                $id = intval($matches[2][0]);
            }
            else if($matches[1][0] == 'w' || $matches[1][0] == 'way')
            {
                $type = 'way';
                $id = intval($matches[2][0]);
            }
            else if($matches[1][0] == 'r' || $matches[1][0] == 'relation')
            {
                $type = 'relation';
                $id = intval($matches[2][0]);
            }
        }
        // Cases '1234' with default 'node', 'way' or 'relation'
        else if(isset($options[0]) && ($options[0] == 'node' || $options[0] == 'way' || $options[0] == 'relation') && is_numeric($buffer))
        {
            $type = $options[0];
            $id = intval($buffer);
        }
        
        if($type != '' && $id != 0)
        {
            $url = 'https://www.openstreetmap.org/'.$type.'/' . $id;
            $text = $type.' #'.$id;
            
            return '<a href="'
                . htmlspecialchars($url)
                . '" title="Open on OpenStreetMap" target="_blank" rel="noopener noreferrer">'
                . $text
                . '</a>';
        }
        else
        {
            return $buffer;
        }
    }


    /* ~~~~~~~~~~~~~~~~~~~~ Getters and Setters ~~~~~~~~~~~~~~~~~~~~ */
    
    /**
     * Gets the transformation name of the specific plugin
     *
     * @return string
     */
    public static function getName()
    {
        return "OpenStreetMapLink";
    }
    
    /**
     * Gets the plugin`s MIME type
     *
     * @return string
     */
    public static function getMIMEType()
    {
        return "Text";
    }

    /**
     * Gets the plugin`s MIME subtype
     *
     * @return string
     */
    public static function getMIMESubtype()
    {
        return "Plain";
    }
}
