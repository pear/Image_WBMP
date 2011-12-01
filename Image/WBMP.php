<?php

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Evgeny Stepanischev <bolk@lixil.ru>                         |
// +----------------------------------------------------------------------+
// Project home page (Russian): http://bolk.exler.ru/files/monobmp/
//
// $Id$


require_once 'Image/XBM.php';

class Image_WBMP extends Image_XBM
{
    function Image_WBMP()
    {
        parent::Image_XBM();
    }

    /**
     * Output image to browser or file
     *
     * @param string $filename (optional) filename for output
     * @return bool PEAR_Error or true
     * @access public
     */
    function output($filename = false)
    {

    	// Header
        $s = "\0\0";

        // width, height
        $s.= chr($this->_sx).chr($this->_sy);

        $wx = ceil($this->_sx / 8);

        // Prepare image data
        for ($y = $this->_sy - 1; $y >= 0; --$y) {
            for ($x = 0; $x < $wx; ++$x) {
                $s .= chr($this->_image[$x][$y]);
            }
        }


        if ($filename === false) {
            echo $s;
        } else {
            if ($fp = fopen($filename, 'w')) {
                flock($fp, LOCK_EX);

                fwrite($fp, $s);
                fclose($fp);
            } else {
                return PEAR::raiseError('Cannot open file for writing.', 5);
            }
        }
        return true;
    }

    /**
     * Create a new image from XBM file or URL
     *
     * @param string $filename XBM file name or URL
     * @return mixed PEAR_error or true for success
     * @access public
     */
    function createFromFile($filename)
    {
        $fp = fopen($filename, 'r');
        @flock ($fp, LOCK_SH);

        if (!is_resource($fp)) {
            return PEAR::raiseError('Cannot open file.', 4);
        }

        // WBMP header
        $sign = fread($fp, 2);
        if ($sign <> "\0\0") {
            return PEAR::raiseError('Invalid WBMP file type.', 5);
        }

		// width, height
		$width  = ord(fread($fp, 1));
		$height = ord(fread($fp, 1));

        // Read the picture
        $sx = ceil($width / 8);

        // Fill image by bitmap data
        for ($y = $height - 1; $y >= 0; --$y) {
            for ($x = 0; $x < $sx; ++$x) {
                $this->_image[$x][$y] = ord(fread($fp, 1));
            }
        }


        fclose($fp);
    }
}
?>