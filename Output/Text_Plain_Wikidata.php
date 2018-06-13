<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Text Plain Wikidata Link Transformations plugin for phpMyAdmin
 *
 * @package    PhpMyAdmin-Transformations
 * @subpackage Wikidata
 * @author     Romain de Bossoreille
 */
namespace PhpMyAdmin\Plugins\Transformations\Output;

use PhpMyAdmin\Plugins\TransformationsPlugin;

if (!defined('PHPMYADMIN')) {
    exit;
}

/**
 * Handles the Wikidata link transformation for text plain
 *
 * @package    PhpMyAdmin-Transformations
 * @subpackage Wikidata
 */
// @codingStandardsIgnoreLine
class Text_Plain_Wikidata extends TransformationsPlugin
{
    /**
     * Constructor of the class
     */
	public function __construct()
	{
		if(!array_key_exists('WikidataLink', $GLOBALS['cfg']['DefaultTransformations']))
		{
			$GLOBALS['cfg']['DefaultTransformations']['WikidataLink'] = array('');
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
            'Displays a Wikidata link; the column contains the Wikidata ID. The first'
            . ' and only option is a language like "fr" (optional).'
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
        $options = $this->getOptions($options, $cfg['DefaultTransformations']['WikidataLink']);
        $url = 'https://www.wikidata.org/wiki/' . $buffer . (isset($options[0]) && $options[0] != '' ? '?uselang='.$options[0] : '');
        
        return '<a href="'
            . htmlspecialchars($url)
            . '" title="Open on Wikidata" target="_blank" rel="noopener noreferrer">'
            . $buffer
            . '</a>';
    }


    /* ~~~~~~~~~~~~~~~~~~~~ Getters and Setters ~~~~~~~~~~~~~~~~~~~~ */
	
    /**
     * Gets the transformation name of the specific plugin
     *
     * @return string
     */
    public static function getName()
    {
        return "WikidataLink";
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
