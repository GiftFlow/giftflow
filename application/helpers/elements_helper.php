<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('breadcrumbs'))
{
    function breadcrumbs( $crumbs = NULL )
	{
		$r = '<ul class="breadcrumbs">';
		foreach($crumbs as $crumb)
		{
			$r .= "<li>";
			if(!empty($crumb['href']))
				$r .= "<a href='".$crumb['href']."'>".$crumb['title']."</a>&nbsp;/&nbsp;";
			else
				$r .= "<span class='current'>".$crumb['title']."</span>";
			$r .= "</li>";
		}	
		$r .= '</ul>';
		$r .= '<div style="clear: both;"></div>';
		return $r;
	}
}

if ( ! function_exists('heading'))
{
	function heading( $name, $params = NULL )
	{
		$r = "<img src='".base_url()."assets/images/headings/".$name.".png' alt='".ucwords($name)."' ";
		if(is_array($params))
		{
			foreach($params as $key=>$val)
			{
				$r .= $key."='".$val."' ";
			}
		}
		$r .= "/>";
		return $r;
	}
}

if ( ! function_exists('form_errors'))
{
	function form_errors()
	{
		$CI =& get_instance();
		$flashdata_error = $CI->session->flashdata('error');
		if(empty($flashdata_error))
		{
			return false;
		}
		
		if(is_array($flashdata_error))
		{
			$array = $flashdata_error;
			$flashdata_error = '<ul class="alert_error">';
			foreach($array as $val) $flashdata_error .= "<li>".$val."</li>";
			$flashdata_error .= "</ul>";
			return $flashdata_error;
		}
		$r = "<p class='alert_error'>".$flashdata_error."</p>";
		return $r;
	}
}

if ( ! function_exists('time_ago'))
{
	/*
	*	Calculates time ago string
	*	For example, "This post was created 4 minutes ago" instead of displaying
	*	a date
	*
	*	@param timestamp $datefrom
	*	@param timestamp $dateto
	*/
	function time_ago( $datefrom, $dateto=-1 )
	{
		$CI =& get_instance();
		return $CI->util->time_ago($datefrom, $dateto);
	}
}

if ( ! function_exists('user_date'))
{
    function user_date( $timestamp, $format )
	{
		$CI =& get_instance();
		return $CI->util->user_date($timestamp,$format);
	}
}
		
?>
