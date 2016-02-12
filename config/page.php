<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
return array(
    // Front section
    'front' => array(),
    // Admin section
    'admin' => array(
        array(
            'title' => _a('Event'),
            'controller' => 'event',
            'permission' => 'event',
        ),
        array(
            'title' => _a('Order'),
            'controller' => 'order',
            'permission' => 'order',
        ),
    ),
);