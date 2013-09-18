<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Number Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/number_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Formats a numbers as bytes, based on size, and adds the appropriate suffix
 *
 * @access	public
 * @param	mixed	// will be cast as int
 * @return	string
 */
if ( ! function_exists('byte_format'))
{
	function byte_format($num, $precision = 2)
	{
		$CI =& get_instance();
		$CI->lang->load('number');
		
		$kb = 1024;			// Kilobyte
		$mb = 1024 * $kb;	// Megabyte
		$gb = 1024 * $mb;	// Gigabyte
		$tb = 1024 * $gb;	// Terabyte
		$pb = 1024 * $tb;	// Pettabyte
		$eb = 1024 * $pb;	// Exabyte
		$zb = 1024 * $eb;	// Zettabyte
		$yb = 1024 * $zb;	// Yottabyte
		$bb = 1024 * $yb;	// Brontobyte

		if ($num >= $bb)
		{
			$num = round($num / $bb, $precision);
			$unit = $CI->lang->line('brontobyte_abbr');
		}
		elseif ($num >= $yb)
		{
			$num = round($num / $yb, $precision);
			$unit = $CI->lang->line('yottabyte_abbr');
		}
		elseif ($num >= $zb)
		{
			$num = round($num / $zb, $precision);
			$unit = $CI->lang->line('zettabyte_abbr');
		}
		elseif ($num >= $eb)
		{
			$num = round($num / $eb, $precision);
			$unit = $CI->lang->line('exabyte_abbr');
		}
		elseif ($num >= $pb)
		{
			$num = round($num / $pb, $precision);
			$unit = $CI->lang->line('pettabyte_abbr');
		}
		elseif ($num >= $tb)
		{
			$num = round($num / $tb, $precision);
			$unit = $CI->lang->line('terabyte_abbr');
		}
		elseif ($num >= $gb)
		{
			$num = round($num / $gb, $precision);
			$unit = $CI->lang->line('gigabyte_abbr');
		}
		elseif ($num >= $mb)
		{
			$num = round($num / $mb, $precision);
			$unit = $CI->lang->line('megabyte_abbr');
		}
		elseif ($num >= $kb)
		{
			$num = round($num / $kb, $precision);
			$unit = $CI->lang->line('kilobyte_abbr');
		}
		else
		{
			$unit = $CI->lang->line('bytes');
			return number_format($num).' '.$unit;
		}

		return number_format($num, $precision).' '.$unit;
	}
}


/* End of file number_helper.php */
/* Location: ./system/helpers/number_helper.php */