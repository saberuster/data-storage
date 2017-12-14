<?php

namespace SaberRuster\DataStorage;


use SaberRuster\DataStorage\EqualInterface as Equalable;

/**
 * Class DataStorage
 *
 * @author  liqi created_at 2017/12/14上午11:42
 * @package \SaberRuster\DataStorage
 */
class DataStorage implements DataStorager
{
    protected $p;
    protected $data_list;

    public function current()
    {
        return $this->data_list[ $this->p ];
    }

    public function next()
    {
        ++$this->p;
    }

    public function key()
    {
        return $this->p;
    }

    public function valid()
    {
        return $this->p < count($this->data_list) && $this->p >= 0;
    }

    public function rewind()
    {
        $this->p = 0;
    }

    public function offsetExists( $offset )
    {
        return array_key_exists($offset, $this->data_list);
    }

    public function offsetGet( $offset )
    {
        if ( $this->offsetExists($offset) ) {
            return $this->data_list[ $offset ];
        }
    }

    public function offsetSet( $offset, $value )
    {
        $this->data_list[ $offset ] = $value;
    }

    public function offsetUnset( $offset )
    {
        unset($this->data_list[ $offset ]);
    }

    public function count()
    {
        return count($this->data_list);
    }

    public function addAll( DataStorager $all_data )
    {
        foreach ( $all_data as $datum ) {
            $this->attach($datum);
        }
    }

    public function attach( $data )
    {
        $this->data_list[] = $data;
    }

    public function contains( $data )
    {
        switch ( true ) {
            case  $data instanceof Equalable:
                foreach ( $this as $item ) {
                    if ( $data->equal($item) ) {
                        return true;
                    }
                }

                return false;

            default:
                return in_array($data, $this->data_list);
        }
    }

    public function detach( $detach )
    {
        $p = $this->pos($detach);
        if ( $p === false ) {
            return;
        }
        unset($this->data_list[ $p ]);
    }

    public function removeAll( DataStorager $data )
    {
        foreach ( $data as $datum ) {
            $this->detach($datum);
        }
    }

    public function removeAllExcept( DataStorager $data )
    {
        foreach ( $data as $datum ) {
            if ( !$this->contains($datum) ) {
                $this->detach($datum);
            }
        }
    }


    protected function pos( $data )
    {
        switch ( true ) {
            case  $data instanceof Equalable:
                foreach ( $this as $k => $item ) {
                    if ( $data->equal($item) ) {
                        return $k;
                    }
                }

                return false;

            default:
                foreach ( $this as $k => $item ) {
                    if ( $item === $data ) {
                        return $k;
                    }
                }

                return false;
        }
    }
}
