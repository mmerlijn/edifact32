<?php

namespace mmerlijn\msgEdifact32\helpers;

class EdiText
{
    public mixed $data;
    public function __construct(mixed $data){
        $this->data = $data??'';
        return $this;
    }
    public function encode():string
    {
        $this->encodeLineBreaks();
        $this->encodeSpecials();
        return trim($this->data);
    }
    public function decode():string
    {
        $this->decodeLineBreaks();
        $this->decodeSpecials();
        return trim($this->data);
    }

    public function encodeLineBreaks():void
    {
        //alle line breaks omzetten naar \.br\ <=>PHP_EOL
        if (is_array($this->data)) {
            $data = array_map(function ($item) {
                return self::encode($item);
            }, $this->data);
            $this->data = implode("\\.br\\", $this->data);
        }
        $this->data = preg_replace('/\r\n|\r|\n/', '\\.br\\', $this->data);
    }

    public function encodeSpecials():void
    {

        $this->data = preg_replace('/\\\/', '\\E\\', $this->data); //backslash
        //$this->data = preg_replace('/\\\r/', '', $this->data); //carriage return
        $this->data = preg_replace('/\|/', '\\F\\', $this->data); //field separator |
        $this->data = preg_replace('/~/', '\\R\\', $this->data); //repetition separator ~
        $this->data = preg_replace('/\^/', '\\S\\', $this->data); //component separator ^
        $this->data = preg_replace('/&/', '\\T\\', $this->data); //subcomponent separator &

    }


    public function decodeLineBreaks():void
    {
        $this->data = str_replace('\\.br\\', PHP_EOL, $this->data);
    }

    public function decodeSpecials():void
    {
        $this->data = preg_replace( '/\\\E\\\/','/\\\/', $this->data); //backslash
        //$this->data = preg_replace( '/\\\r/','', $this->data); //carriage return
        $this->data = preg_replace( '/\\\F\\\/','|', $this->data); //field separator |
        $this->data = preg_replace( '/\\\R\\\/','~', $this->data); //repetition separator ~
        $this->data = preg_replace( '/\\\S\\\/','^', $this->data); //component separator ^
        $this->data = preg_replace( '/\\\T\\\/','&', $this->data); //subcomponent separator &
        $this->data = preg_replace('/\\\.*\\\/', '', $this->data); //diverse tekens
        $this->data = preg_replace('/\s+/', ' ', $this->data); //multiple spaces to 1

    }
}