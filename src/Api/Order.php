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
namespace Module\Event\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Math\Rand;

/*
 * Pi::api('order', 'event')->getProductDetails($product);
 * Pi::api('order', 'event')->postPaymentUpdate($order, $basket);
 * Pi::api('order', 'event')->getOrder($id);
 * Pi::api('order', 'event')->canonizeOrder($order, $event);
 */

class Order extends AbstractApi
{
    public function checkProduct($id, $type = null)
    {
        $product = Pi::model('extra', 'event')->find($id, 'id');
        if (empty($product) || $product['status'] != 1) {
            return false;
        }
        return true;
    }
    
    public function getProductDetails($product)
    {
        $event = Pi::api('event', 'event')->getEventSingle($product, 'id', 'light');
        $event['productUrl'] = $event['eventUrl'];
        return $event;
    }

    public function postPaymentUpdate($order, $detail)
    {
        // Update time
        foreach ($detail as $product) {
            if ($product['product'] > 0) {
                // Set number
                $number = $product['number'];
                
                // Get event
                $event = Pi::api('event', 'event')->getEventSingle($product['product'], 'id', 'light');
                $setting = json_decode($event['setting'], true);
                // Update sales
                Pi::model('extra', $this->getModule())->update(
                    array('register_sales' => ($event['register_sales'] + $number)),
                    array('id' => $event['id'])
                );
                
                // Save order
                $values = array(
                    'uid' => $order['uid'],
                    'event' => $event['id'],
                    'order_id' => $order['id'],
                    'number' => $number,
                    'price' => $product['product_price'],
                    'vat'=> $product['vat_price'],
                    'total'=> $product['product_price'] + $product['vat_price'] + $product['shipping_price'] + $product['packing_price'] + $product['setup_price'] - $product['discount_price'],
                    'time_order' => time(),
                    'time_start' => $event['time_start'],
                    'time_end' => $event['time_end'],
                    'status' => 1,
                    'extra' => json_encode($setting['action']),
                );
                $row = Pi::model('order', $this->getModule())->createRow();
                $row->assign($values);
                $row->save();
                // Set url
                /* $url = Pi::url(Pi::service('url')->assemble('event', array(
                    'module' => $this->getModule(),
                    'controller' => 'register',
                    'action' => 'detail',
                    'id' => $row->id,
                ))); */
                
                
                 if (Pi::service('authentication')->hasIdentity()) {
                     $url = Pi::url(Pi::service('url')->assemble('order', array(
                        'module' => 'order',
                        'controller' => 'detail',
                        'action' => 'index',
                        'id' => $order['id'],
                    )));
                 } else {
                    $url =  Pi::url(Pi::service('url')->assemble('event', array(
                        'module' => 'event',
                        'controller' => 'detail',
                        'action' => 'index',
                        'slug' => $event['slug']
                    )));
                    
                }

                // Send notification
                //Pi::api('notification', 'order')->newOrder($order, $event);
                // Set back url
                return $url;
            }
        }
    }

    public function getOrder($parameter, $field = 'id', $event = true)
    {
        // Check for order module request
        if ($field == 'order') {
            $field = 'order_id';
            $event = false;
        }
        // Get order
        $order = Pi::model('order', $this->getModule())->find($parameter, $field);
        $order = $this->canonizeOrder($order, $event);
        return $order;
    }

    public function canonizeOrder($order, $event = true)
    {
        // Check
        if (empty($order)) {
            return '';
        }
        // Order to array
        $order = $order->toArray();
        // Set time
        $order['time_order_view'] = _date($order['time_order']);
        $order['time_start_view'] = _date($order['time_start']);
        $order['time_end_view'] = _date($order['time_end']);
        // Set number view
        $order['number_view'] = $order['number'];
        // Set price
        if (Pi::service('module')->isActive('order')) {
            $order['price_view'] = Pi::api('api', 'order')->viewPrice($order['price']);
            $order['vat_view'] = Pi::api('api', 'order')->viewPrice($order['vat']);
            $order['total_view'] = Pi::api('api', 'order')->viewPrice($order['total']);
        } else {
            $order['price_view'] = _currency($order['price']);
            $order['vat_view'] = _currency($order['vat']);
            $order['total_view'] = _currency($order['total']);
        }
        // Set order url
        /* $order['orderUrl'] = Pi::url(Pi::service('url')->assemble('event', array(
            'module' => $this->getModule(),
            'controller' => 'register',
            'action' => 'detail',
            'id' => $order['id'],
        ))); */
        // Set event
        if ($event) {
            $order['eventInfo'] = Pi::api('event', 'event')->getEventSingle($order['event']);
        }
        // return
        return $order;
    }

    public function createExtraDetailForProduct($values) 
    {
        return json_encode(
            array(
               'item' => $values['module_item'],
            )
        );   
    }

    public function getExtraFieldsFormForOrder()
    {
        return array();
    }

}