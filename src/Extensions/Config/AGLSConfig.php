<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\AGLS\Extensions\Config
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-agls
 */

namespace SilverWare\AGLS\Extensions\Config;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverWare\Extensions\ConfigExtension;
use SilverWare\Forms\FieldSection;
use SilverWare\Forms\ToggleGroup;

/**
 * A config extension which adds AGLS settings to site configuration.
 *
 * @package SilverWare\AGLS\Extensions\Config
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-agls
 */
class AGLSConfig extends ConfigExtension
{
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'AGLSCreatorName' => 'Varchar(255)',
        'AGLSCreatorAddress' => 'Varchar(255)',
        'AGLSCreatorContact' => 'Varchar(255)',
        'AGLSPublisherName' => 'Varchar(255)',
        'AGLSPublisherAddress' => 'Varchar(255)',
        'AGLSPublisherContact' => 'Varchar(255)',
        'AGLSPublisherSame' => 'Boolean'
    ];
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'AGLSPublisherSame' => 1
    ];
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields List of CMS fields from the extended object.
     *
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Update Field Objects (from parent):
        
        parent::updateCMSFields($fields);
        
        // Create Icons Tab:
        
        $fields->findOrMakeTab('Root.SilverWare.AGLS', $this->owner->fieldLabel('AGLS'));
        
        // Create Icon Upload Fields:
        
        $fields->addFieldsToTab(
            'Root.SilverWare.AGLS',
            [
                FieldSection::create(
                    'AGLSCreatorSection',
                    $this->owner->fieldLabel('AGLSCreator'),
                    [
                        TextField::create(
                            'AGLSCreatorName',
                            $this->owner->fieldLabel('AGLSCreatorName')
                        ),
                        TextField::create(
                            'AGLSCreatorAddress',
                            $this->owner->fieldLabel('AGLSCreatorAddress')
                        ),
                        TextField::create(
                            'AGLSCreatorContact',
                            $this->owner->fieldLabel('AGLSCreatorContact')
                        )
                    ]
                ),
                FieldSection::create(
                    'AGLSPublisherSection',
                    $this->owner->fieldLabel('AGLSPublisher'),
                    [
                        ToggleGroup::create(
                            'AGLSPublisherSame',
                            $this->owner->fieldLabel('AGLSPublisherSame'),
                            [
                                TextField::create(
                                    'AGLSPublisherName',
                                    $this->owner->fieldLabel('AGLSPublisherName')
                                ),
                                TextField::create(
                                    'AGLSPublisherAddress',
                                    $this->owner->fieldLabel('AGLSPublisherAddress')
                                ),
                                TextField::create(
                                    'AGLSPublisherContact',
                                    $this->owner->fieldLabel('AGLSPublisherContact')
                                )
                            ]
                        )->setShowWhenChecked(false)
                    ]
                )
            ]
        );
    }
    
    /**
     * Updates the field labels of the extended object.
     *
     * @param array $labels Array of field labels from the extended object.
     *
     * @return void
     */
    public function updateFieldLabels(&$labels)
    {
        // Update Field Labels (from parent):
        
        parent::updateFieldLabels($labels);
        
        // Update Field Labels:
        
        $labels['AGLSCreator']   = _t(__CLASS__ . '.CREATOR', 'Creator');
        $labels['AGLSPublisher'] = _t(__CLASS__ . '.PUBLISHER', 'Publisher');
        
        $labels['AGLSCreatorName']    = _t(__CLASS__ . '.CORPORATENAME', 'Corporate name');
        $labels['AGLSCreatorAddress'] = _t(__CLASS__ . '.ADDRESS', 'Address');
        $labels['AGLSCreatorContact'] = _t(__CLASS__ . '.CONTACTNUMBER', 'Contact number');
        
        $labels['AGLSPublisherName']    = _t(__CLASS__ . '.CORPORATENAME', 'Corporate name');
        $labels['AGLSPublisherAddress'] = _t(__CLASS__ . '.ADDRESS', 'Address');
        $labels['AGLSPublisherContact'] = _t(__CLASS__ . '.CONTACTNUMBER', 'Contact number');
        
        $labels['AGLSPublisherSame'] = _t(__CLASS__ . '.SAMEASCREATOR', 'Same as creator');
    }
    
    /**
     * Event method called before the extended object is written to the database.
     *
     * @return void
     */
    public function onBeforeWrite()
    {
        if ($this->owner->AGLSPublisherSame) {
            $this->owner->AGLSPublisherName    = '';
            $this->owner->AGLSPublisherAddress = '';
            $this->owner->AGLSPublisherContact = '';
        }
    }
    
    /**
     * Answers a string containing the AGLS creator metadata.
     *
     * @return string
     */
    public function getAGLSCreator()
    {
        $creator = [];
        
        if ($this->owner->AGLSCreatorName) {
            $creator['corporateName'] = $this->owner->AGLSCreatorName;
        }
        
        if ($this->owner->AGLSCreatorAddress) {
            $creator['address'] = $this->owner->AGLSCreatorAddress;
        }
        
        if ($this->owner->AGLSCreatorContact) {
            $creator['contact'] = $this->owner->AGLSCreatorContact;
        }
        
        return $this->getAGLSArrayAsString($creator);
    }
    
    /**
     * Answers a string containing the AGLS publisher metadata.
     *
     * @return string
     */
    public function getAGLSPublisher()
    {
        if ($this->owner->AGLSPublisherSame) {
            return $this->owner->AGLSCreator;
        }
        
        $publisher = [];
        
        if ($this->owner->AGLSPublisherName) {
            $publisher['corporateName'] = $this->owner->AGLSPublisherName;
        }
        
        if ($this->owner->AGLSPublisherAddress) {
            $publisher['address'] = $this->owner->AGLSPublisherAddress;
        }
        
        if ($this->owner->AGLSPublisherContact) {
            $publisher['contact'] = $this->owner->AGLSPublisherContact;
        }
        
        return $this->getAGLSArrayAsString($publisher);
    }
    
    /**
     * Converts the given associative array of AGLS metadata to a string.
     *
     * @param array $array
     *
     * @return string
     */
    protected function getAGLSArrayAsString($array)
    {
        $parts = [];
        
        foreach ($array as $key => $value) {
            
            $parts[] = sprintf(
                '%s=%s',
                trim($key),
                trim($value)
            );
            
        }
        
        return implode('; ', $parts);
    }
}
