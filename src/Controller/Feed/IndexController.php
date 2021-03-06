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
namespace Module\Event\Controller\Feed;

use Pi;
use Pi\Mvc\Controller\FeedController;
use Pi\Feed\Model as DataModel;

class IndexController extends FeedController
{
    public function indexAction()
    {
        $feed = $this->getDataModel(array(
            'title' => __('Event feed'),
            'description' => __('Recent events'),
            'date_created' => time(),
        ));
        // Set info
        $where = array(
            'status' => 1,
            'type' => 'event'
        );
        $order = array('time_publish DESC', 'id DESC');
        $limit = 10;
        // Get list of event
        $listEvent = Pi::api('event', 'event')->getEventList($where, $order, '', $limit, 'full', 'story');
        foreach ($listEvent as $event) {
            $description = (empty($event['text_summary'])) ? $event['text_description'] : $event['text_summary'];
            $entry = array();
            $entry['title'] = $event['title'];
            $entry['description'] = strtolower(trim($description));
            $entry['date_modified'] = (int)$event['time_publish'];
            $entry['link'] = $event['eventUrl'];
            $feed->entry = $entry;
        }
        return $feed;
    }
}