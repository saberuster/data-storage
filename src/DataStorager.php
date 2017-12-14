<?php

namespace SaberRuster\DataStorage;

/**
 * Interface DataStorager
 *
 * @author  liqi created_at 2017/12/14上午11:41
 * @package SaberRuster\DataStorage
 */
interface DataStorager extends \Countable, \Iterator, \ArrayAccess
{
    public function addAll( DataStorager $all_data );

    public function attach( $data );

    public function contains( $data );

    public function detach( $detach );

    public function removeAll( DataStorager $data );

    public function removeAllExcept( DataStorager $data );

}
