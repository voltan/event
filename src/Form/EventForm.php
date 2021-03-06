<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
namespace Module\Event\Form;

use Pi;
use Pi\Form\Form as BaseForm;

class EventForm extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        $this->option = $option;
        $this->thumbUrl = (isset($option['thumbUrl'])) ? $option['thumbUrl'] : '';
        $this->removeUrl = (isset($option['removeUrl'])) ? $option['removeUrl'] : '';
        $this->module = Pi::service('module')->current();
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new EventFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => __('Title'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'required' => true,
            )
        ));
        // subtitle
        $this->add(array(
            'name' => 'subtitle',
            'options' => array(
                'label' => __('Subtitle'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // slug
        if ($this->option['side'] == 'admin') {
            $this->add(array(
                'name' => 'slug',
                'options' => array(
                    'label' => __('slug'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                )
            ));
        } else {
            $this->add(array(
                'name' => 'slug',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // extra
        $this->add(array(
            'name' => 'extra_time',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Time options'),
            ),
        ));
        // Check local
        $local = Pi::service('i18n')->getLocale();
        if ($local == 'fa') {
            // time_start_view
            $this->add(array(
                'name' => 'time_start_moment',
                'options' => array(
                    'label' => __('Time start'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                    'required' => true,
                    'class' => 'event-time-start-view',
                )
            ));
            // time_end_view
            $this->add(array(
                'name' => 'time_end_moment',
                'options' => array(
                    'label' => __('Time end'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                    'required' => false,
                    'class' => 'event-time-end-view',
                )
            ));
            // time_start
            $this->add(array(
                'name' => 'time_start',
                'attributes' => array(
                    'type' => 'hidden',
                    'class' => 'event-time-start',
                ),
            ));
            // time_end
            $this->add(array(
                'name' => 'time_end',
                'attributes' => array(
                    'type' => 'hidden',
                    'class' => 'event-time-end',
                ),
            ));
        } else {
            // time_start
            $this->add(array(
                'name' => 'time_start',
                'type' => 'datepicker',
                'options' => array(
                    'label' => __('Time start'),
                    'datepicker' => array(
                        'format' => 'yyyy/mm/dd',
                        'autoclose' => true,
                        'todayBtn' => true,
                        'todayHighlight' => true,
                        'weekStart' => 1,
                        'zIndexOffset' => 10000

                    ),
                ),
                'attributes' => array(
                    'required' => true,
                    'value' => date('Y-m-d'),
                    'class' => 'event-time-start',
                )
            ));
            // time_end
            $this->add(array(
                'name' => 'time_end',
                'type' => 'datepicker',
                'options' => array(
                    'label' => __('Time end'),
                    'datepicker' => array(
                        'format' => 'yyyy/mm/dd',
                        'autoclose' => true,
                        'todayBtn' => true,
                        'todayHighlight' => true,
                        'weekStart' => 1,
                        'zIndexOffset' => 10000

                    ),
                ),
                'attributes' => array(
                    'required' => false,
                    'class' => 'event-time-end',
                )
            ));
        }
        // extra
        $this->add(array(
            'name' => 'extra_text',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Description options'),
            ),
        ));
        // text_summary
        $this->add(array(
            'name' => 'text_summary',
            'options' => array(
                'label' => __('Summary'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '3',
                'cols' => '40',
                'description' => '',
                'required' => $this->option['side'] == 'admin' ? false : true,
                
            )
        ));
        // text_description
        $this->add(array(
            'name' => 'text_description',
            'options' => array(
                'label' => __('Description'),
                'editor' => 'html',
            ),
            'attributes' => array(
                'type' => 'editor',
                'description' => '',
                'required' => $this->option['side'] == 'admin' ? false : true,
                
            )
        ));
        // extra_main
        $this->add(array(
            'name' => 'extra_main',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Main options'),
            ),
        ));
        // status
        if ($this->option['side'] == 'admin') {
            $this->add(array(
                'name' => 'status',
                'type' => 'select',
                'options' => array(
                    'label' => __('Status'),
                    'value_options' => array(
                        1 => __('Published'),
                        2 => __('Pending review'),
                        3 => __('Draft'),
                        4 => __('Private'),
                        5 => __('Delete'),
                    ),
                ),
                'attributes' => array(
                    'required' => true,
                )
            ));
        }
        // Image
        $this->add(array(
            'name' => 'main_image',
            'type' => 'Module\Media\Form\Element\Media',
            'options' => array(
                'label' => __('Main image'),
                'module' => 'news',
            ),
        ));
        // address
        if ($this->option['side'] == 'admin' || ($this->option['side'] == 'front' && $this->option['manage_register'])) {
            $this->add(array(
                'name' => 'address',
                'options' => array(
                    'label' => __('Address'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                )
            ));
        }
        // organizer_name
        $this->add(array(
            'name' => 'organizer_name',
            'options' => array(
                'label' => __('Organizer name'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // source_url
        $this->add(array(
            'name' => 'source_url',
            'options' => array(
                'label' => __('Organizer Website'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // offer_url
        $this->add(array(
            'name' => 'offer_url',
            'options' => array(
                'label' => __('Offer url'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // register
        $this->add(array(
            'name' => 'extra_register',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Registration options'),
            ),
        ));
        // register_details
        if ($this->option['side'] == 'admin' || ($this->option['side'] == 'front' && $this->option['manage_register'])) {
            $this->add(array(
                'name' => 'register_details',
                'options' => array(
                    'label' => __('Registration details/requirements'),
                    'editor' => 'html',
                ),
                'attributes' => array(
                    'type' => 'editor',
                    'description' => '',
                )
            ));
        }
        // Check order active
        if ($this->option['order_active']) {
            // register_can
            $this->add(array(
                'name' => 'register_can',
                'type' => 'checkbox',
                'options' => array(
                    'label' => __('Register online?'),
                ),
                'attributes' => array(
                    'description' => __('User can register directly on the website and proceed to checkout, real availability is displayed'),
                )
            ));
        }
        if ($this->option['order_active']) {
            // register_stock
            $this->add(array(
                'name' => 'register_stock',
                'options' => array(
                    'label' => __('Capacity'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'required' => true,
                    'description' => __('0 for unlimited'),
                )
            ));
        }    
            
        // register_price
        $this->add(array(
            'name' => 'register_price',
            'options' => array(
                'label' => __('Minimum Price'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        
           
        // order_discount
        if ($this->option['order_active'] && isset($this->option['order_discount']) && $this->option['order_discount']) {
            
            // extra_product
            $this->add(array(
                'name' => 'extra_discount',
                'type' => 'fieldset',
                'options' => array(
                    'label' => __('Discount options'),
                ),
            ));
            // Get role list
            $roles = Pi::service('registry')->Role->read('front');
            unset($roles['webmaster']);
            unset($roles['guest']);
            foreach ($roles as $name => $role) {
                $this->add(array(
                    'name' => $name,
                    'options' => array(
                        'label' => __('Rebate rate for') . ' ' . $role['title'],
                    ),
                    'attributes' => array(
                        'type' => 'text',
                        'description' => __('Number and between 1 to 99'),
                    )
                ));
            }
        }
        // Check topic
        if ($this->option['use_news_topic']) {
            $this->add(array(
                'name' => 'extra_topic',
                'type' => 'fieldset',
                'options' => array(
                    'label' => __('Topic options'),
                ),
            ));
            // topic
            $this->add(array(
                'name' => 'topic',
                'type' => 'Module\News\Form\Element\Topic',
                'options' => array(
                    'label' => __('Topic'),
                    'required' => true,
                    'topic' => 'full',
                    'type' => 'event',
                ),
            ));
            // topic_main
            $this->add(array(
                'name' => 'topic_main',
                'type' => 'Module\News\Form\Element\Topic',
                'options' => array(
                    'label' => __('Main topic'),
                    'required' => true,
                    'topic' => '',
                    'type' => 'event',
                ),
                'attributes' => array(
                    'required' => true,
                    'size' => 1,
                    'multiple' => 0,
                    'description' => __('Just use for breadcrumbs and mobile apps'),
                ),
            ));
        }
        // extra guide
        if (Pi::service('module')->isActive('guide') && $this->option['side'] == 'admin') {
            $this->add(array(
                'name' => 'extra_guide',
                'type' => 'fieldset',
                'options' => array(
                    'label' => __('Guide options'),
                ),
            ));
            // guide_owner
            if ($this->option['side'] == 'admin') {
                $this->add(array(
                    'name' => 'guide_owner',
                    'type' => 'Module\Guide\Form\Element\Owner',
                    'options' => array(
                        'label' => __('Owner'),
                        'module' => 'guide',
                        'owner' => true
                    ),
                ));
            }
            // guide_category
            if ($this->option['use_guide_category']) {
                $this->add(array(
                    'name' => 'guide_category',
                    'type' => 'Module\Guide\Form\Element\Category',
                    'options' => array(
                        'label' => __('Category'),
                        'category' => array(0 => ''),
                        'module' => 'guide',
                        'type' => 'external',
                        'status' => 1,
                    ),
                    'attributes' => array(
                        'size' => 5,
                        'multiple' => 1,
                    )
                ));
            }
            // guide_location
            if ($this->option['use_guide_location']) {
                $this->add(array(
                    'name' => 'guide_location',
                    'type' => 'Module\Guide\Form\Element\Location',
                    'options' => array(
                        'label' => __('Location'),
                        'module' => 'guide',
                        'type' => 'external',
                        'status' => 1,
                    ),
                    'attributes' => array(
                        'size' => 5,
                        'multiple' => 1,
                    )
                ));
            }
            // guide_item
            if (!isset($this->option['item']) || !$this->option['item']) {
                $this->add(
                    array(
                        'name' => 'guide_item',
                        'type' => 'Module\Guide\Form\Element\Item',
                        'options' => array(
                            'label' => __('Item'),
                            'module' => 'guide',
                            'owner' => isset($this->option['owner']) ? $this->option['owner'] : '',
                            'status' => 1,
                        ),
                        'attributes' => array(
                            'size' => 5,
                            'multiple' => 1,
                        )
                    )
                );
            }
        }
        // Check order active
        if ($this->option['map_use']) {
            $this->add(array(
                'name' => 'extra_map',
                'type' => 'fieldset',
                'options' => array(
                    'label' => __('Map options'),
                ),
            ));
            // map_latitude
            $this->add(array(
                'name' => 'map_latitude',
                'options' => array(
                    'label' => __('Latitude'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                )
            ));
            // map_longitude
            $this->add(array(
                'name' => 'map_longitude',
                'options' => array(
                    'label' => __('Longitude'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                )
            ));
            // map_zoom
            $this->add(array(
                'name' => 'map_zoom',
                'options' => array(
                    'label' => __('Zoom'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                )
            ));
        }
        // extra
        if ($this->option['side'] == 'admin') {
            $this->add(array(
                'name' => 'extra_seo',
                'type' => 'fieldset',
                'options' => array(
                    'label' => __('SEO options'),
                ),
            ));
            // seo_title
            $this->add(array(
                'name' => 'seo_title',
                'options' => array(
                    'label' => __('SEO Title'),
                ),
                'attributes' => array(
                    'type' => 'textarea',
                    'rows' => '2',
                    'cols' => '40',
                    'description' => '',
                )
            ));
            // seo_keywords
            $this->add(array(
                'name' => 'seo_keywords',
                'options' => array(
                    'label' => __('SEO Keywords'),
                ),
                'attributes' => array(
                    'type' => 'textarea',
                    'rows' => '2',
                    'cols' => '40',
                    'description' => '',
                )
            ));
            // seo_description
            $this->add(array(
                'name' => 'seo_description',
                'options' => array(
                    'label' => __('SEO Description'),
                ),
                'attributes' => array(
                    'type' => 'textarea',
                    'rows' => '3',
                    'cols' => '40',
                    'description' => '',
                )
            ));
        }
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }
}
