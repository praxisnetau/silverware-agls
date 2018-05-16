<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\AGLS\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-agls
 */

namespace SilverWare\AGLS\Extensions;

use SilverStripe\CMS\Controllers\ModelAsController;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Extension;
use SilverStripe\i18n\i18n;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * An extension which adds AGLS metadata to pages.
 *
 * @package SilverWare\AGLS\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-agls
 */
class PageExtension extends Extension
{
    /**
     * Define constants.
     */
    const AGGREGATION_LEVEL_ITEM       = 'item';
    const AGGREGATION_LEVEL_COLLECTION = 'collection';
    
    /**
     * Answers the creator for the AGLS meta tag.
     *
     * @return string
     */
    public function getAGLSCreator()
    {
        return SiteConfig::current_site_config()->AGLSCreator;
    }
    
    /**
     * Answers the publisher for the AGLS meta tag.
     *
     * @return string
     */
    public function getAGLSPublisher()
    {
        return SiteConfig::current_site_config()->AGLSPublisher;
    }
    
    /**
     * Answers the title for the AGLS meta tag.
     *
     * @return string
     */
    public function getAGLSTitle()
    {
        return $this->owner->MetaTitle;
    }
    
    /**
     * Answers the subject for the AGLS meta tag.
     *
     * @return string
     */
    public function getAGLSSubject()
    {
        return $this->owner->AGLSTitle;
    }
    
    /**
     * Answers the created date for the AGLS meta tag.
     *
     * @return DBDatetime
     */
    public function getAGLSCreated()
    {
        return $this->owner->MetaCreated;
    }
    
    /**
     * Answers the format for the created date.
     *
     * @return string
     */
    public function getAGLSCreatedFormat()
    {
        if ($format = $this->owner->config()->agls_created_format) {
            return $format;
        }
    }
    
    /**
     * Formats the created date using the given format, or a default format.
     *
     * @param string $format
     *
     * @return string
     */
    public function getAGLSCreatedFormatted($format = null)
    {
        $date = $this->owner->getAGLSCreated();
        
        if ($date instanceof DBDatetime) {
            return $date->format($format ? $format : $this->owner->getAGLSCreatedFormat());
        }
    }
    
    /**
     * Answers the modified date for the AGLS meta tag.
     *
     * @return DBDatetime
     */
    public function getAGLSModified()
    {
        return $this->owner->MetaModified;
    }
    
    /**
     * Answers the format for the modified date.
     *
     * @return string
     */
    public function getAGLSModifiedFormat()
    {
        if ($format = $this->owner->config()->agls_modified_format) {
            return $format;
        }
    }
    
    /**
     * Formats the modified date using the given format, or a default format.
     *
     * @param string $format
     *
     * @return string
     */
    public function getAGLSModifiedFormatted($format = null)
    {
        $date = $this->owner->getAGLSModified();
        
        if ($date instanceof DBDatetime) {
            return $date->format($format ? $format : $this->owner->getAGLSModifiedFormat());
        }
    }
    
    /**
     * Answers the identifier for the AGLS meta tag.
     *
     * @return string
     */
    public function getAGLSIdentifier()
    {
        return $this->owner->MetaAbsoluteLink;
    }
    
    /**
     * Answers the description for the AGLS meta tag.
     *
     * @return string
     */
    public function getAGLSDescription()
    {
        if ($this->owner->MetaDescription) {
            return $this->owner->MetaDescription;
        }
        
        return $this->owner->MetaSummaryLimited;
    }
    
    /**
     * Answers the language for the AGLS meta tag.
     *
     * @return string
     */
    public function getAGLSLanguage()
    {
        return ModelAsController::controller_for($this->owner)->ContentLocale();
    }
    
    /**
     * Answers the language for the AGLS aggregation level.
     *
     * @return string
     */
    public function getAGLSAggregationLevel()
    {
        return self::AGGREGATION_LEVEL_COLLECTION;
    }
    
    /**
     * Appends the additional AGLS tags to the provided meta tags.
     *
     * @param string $tags
     *
     * @return void
     */
    public function MetaTags(&$tags)
    {
        // Add New Line (if does not exist):
        
        if (!preg_match('/[\n]$/', $tags)) {
            $tags .= "\n";
        }
        
        // Iterate AGLS Schemas:
        
        foreach ($this->owner->config()->agls_schemas as $key => $spec) {
            
            if (is_array($spec) && isset($spec['name']) && isset($spec['href'])) {
                
                $tags .= sprintf(
                    "<link rel=\"%s\" href=\"%s\" />\n",
                    Convert::raw2att($spec['name']),
                    Convert::raw2att($spec['href'])
                );
                
            }
            
        }
        
        // Iterate AGLS Metadata:
        
        foreach ($this->owner->config()->agls_metadata as $key => $spec) {
            
            if (is_array($spec) && isset($spec['name'])) {
                
                $content = isset($spec['property']) ? $this->owner->{$spec['property']} : null;
                $scheme  = isset($spec['scheme'])   ? $spec['scheme'] : null;
                
                $this->addMetaTag($tags, $spec['name'], $content, $scheme);
                
            }
            
        }
    }
    
    /**
     * Appends a meta tag with the given name, content and scheme values to the provided tags variable.
     *
     * @param string $tags
     * @param string $name
     * @param string $content
     * @param string $scheme
     *
     * @return void
     */
    public function addMetaTag(&$tags, $name, $content = null, $scheme = null)
    {
        $tags .= $this->getMetaTag($name, $content, $scheme);
    }
    
    /**
     * Answers a meta tag with the given name, content and scheme values.
     *
     * @param string $name
     * @param string $content
     * @param string $scheme
     *
     * @return string
     */
    public function getMetaTag($name, $content = null, $scheme = null)
    {
        $tag = sprintf(
            '<meta name="%s"',
            $name
        );
        
        if ($scheme) {
            $tag .= sprintf(' scheme="%s"', Convert::raw2att($scheme));
        }
        
        if ($content) {
            $tag .= sprintf(' content="%s"', Convert::raw2att($content));
        }
        
        $tag .= " />\n";
        
        return $tag;
    }
}
