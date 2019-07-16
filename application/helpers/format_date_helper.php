<?php

if(!function_exists('dateFormat'))
{
    function dateFormat($format='d-m-Y', $givenDate=null)
    {
        return date($format, strtotime($givenDate));
    }
}

if(!function_exists('dateFormatTime'))
{
    function dateFormatTime($format='d-m-Y H:i:s', $givenDate=null)
    {
        return date($format, strtotime($givenDate));
    }
}

if(!function_exists('dateEnFrancais'))
{
    function dateEnFrancais($format='%A %e %B %Y', $givenDate=null) {
        
        return strftime($format, strtotime($givenDate));
    }
}
