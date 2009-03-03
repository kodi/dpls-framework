<?php
/**
 * Debug class
 * @version 0.5b
 *@package DBG
 */

class DPLS_DBG{
    /**
     * Object that we want to debug
     *
     * @var object
     */
    /**
     * Prints $this->obj
     *
     */
    function pr($obj){
        echo "<div style=\"position:absolute;right:10px;top:10px;background:white;border:solid 1px #313131;padding:10px;text-align:left;\" onclick=\"$(this).hide()\"><pre>\n";
        print_r($obj);
        echo "</pre></div>";
    }
}
?>