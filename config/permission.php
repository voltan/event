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
return array(
    // Front section
    'front' => array(
        'public' => array(
            'title' => _a('Global public resource'),
            'access' => array(
                'guest',
                'member',
            ),
        ),
        'manage' => array(
            'title' => _a('Manage'),
            'access' => array(
                'member',
            ),
        ),
    ),
    // Admin section
    'admin' => array(
        'event' => array(
            'title' => _a('Event'),
            'access' => array(),
        ),
        'order' => array(
            'title' => _a('List of order'),
            'access' => array(),
        ),
        'tools' => array(
            'title' => _a('Tools'),
            'access' => array(),
        ),
    ),
);