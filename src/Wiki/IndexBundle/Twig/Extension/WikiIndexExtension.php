<?php 
// src/Acme/PageBundle/Twig/Extension/AcmePageExtension.php

namespace Wiki\IndexBundle\Twig\Extension;

use Symfony\Component\HttpFoundation\Request;
use Wiki\IndexBundle\Twig\Extension\DateFormatter;


class WikiIndexExtension extends \Twig_Extension
{
	protected $request;
	protected $em;
	protected $conn;
	protected $companies;
	protected $users;

	/**
	 *
	 * @var \Twig_Environment
	 */
	protected $environment;
	
	public function __construct(Request $request , \Doctrine\ORM\EntityManager $em)
	{
		$this->request = $request;
		$this->em = $em;
		$this->conn = $em->getConnection();
	}
	
	public function initRuntime(\Twig_Environment $environment)
	{
		$this->environment = $environment;
	}
	
	public function getFunctions()
	{
		return array(
			'get_controller_name' => new \Twig_Function_Method($this, 'getControllerName'),
			'get_action_name' => new \Twig_Function_Method($this, 'getActionName'),
			'getCompanies' => new \Twig_Function_Method($this, 'getCompanies'),
			'getUsers' => new \Twig_Function_Method($this, 'getUsers'),
			'getProviders' => new \Twig_Function_Method($this, 'getProviders'),
			'getMakes' => new \Twig_Function_Method($this, 'getMakes'),
		);
	}
	public function getFilters()
	{
		return array(
			'i18n' => new \Twig_Filter_Method($this, 'i18n'),
			'slice' => new \Twig_Filter_Method($this, 'slice' , array('is_safe' => array('html'))),
			'truncate' => new \Twig_Filter_Method($this, 'truncate'),
			'arrondi' => new \Twig_Filter_Method($this, 'arrondi'),
			'ttc' => new \Twig_Filter_Method($this, 'ttc'),
			'gray' => new \Twig_Filter_Method($this, 'gray'),
			'highlight' => new \Twig_Filter_Method($this, 'highlight'),
			'chatInterval' => new \Twig_Filter_Method($this, 'chatInterval'),
			'localeDate'  => new \Twig_Filter_Function('\Wiki\IndexBundle\Twig\Extension\WikiIndexExtension::localeDateFilter'),
		);
	}
	
	/**
	 * Return grayed string
	 */
	public function slice($string , $length)
	{
		if (empty($string))
			return '';
		else if (strlen($string) < $length)
			return $string;
		else
			return '<span data-toggle="tooltip" title="' . $string . '">' . substr($string, 0 , $length) . '...' . '</span>';
	}
	public function truncate($integer)
	{
		return number_format($integer, 0 , '.' , ',');
	}
	public function arrondi($integer)
	{
		return number_format($integer, 2 , '.' , '');
	}
	public function ttc($integer)
	{
		return number_format($integer * 1.196, 2 , '.' , '');
	}
	public function gray($string)
	{
		return '<span class="gray">' . $string . '</span>';
	}
	public function highlight($string, $highlight)
	{
		return preg_replace("/(" . $highlight . ")/i", '<span class="highlight">$1</span>' , $string);
	}
	public function i18n($string)
	{
		if (empty($string))
			return null;

		// normalisation
		$string = trim($string);
		$string = str_replace(' ' , '' , $string);
		$string = str_replace('.' , '' , $string);
		$string = str_replace('-' , '' , $string);

		// supprimer le premier zéro s'il existe
		if (substr($string, 0, 1) == 0)
			$string = substr($string, 1);

		$array = str_split($string);
		$string = ''; $i = 0;
		foreach ($array as $char)
		{
			if ($i == 1 OR ($i > 1 && $i%2))
				$string .= ' ';

			$string .= $char;
			$i++;
		}
		
		return '+(33) ' . $string;
	}
	public function chatInterval($date)
	{
		$now = new \Datetime;

		$diff = $date->diff($now);
		if ( $diff->d == 0 )
			return $date->format('H:i');
		else
			return $date->format('l H:i');
	}
	
	/**
	 * Get current controller name
	 */
	public function getControllerName()
	{
		$pattern = "#Controller\\\([a-zA-Z]*)Controller#";
		$matches = array();
		preg_match($pattern, $this->request->get('_controller'), $matches);
		
		return strtolower($matches[1]);
	}
	
	/**
	 * Get current action name 
	 */
	public function getActionName()
	{
		$pattern = "#::([a-zA-Z]*)Action#";
		$matches = array();
		preg_match($pattern, $this->request->get('_controller'), $matches);
	
		return $matches[1];
	}
	/**
	 * Get companies 
	 */
	public function getCompanies()
	{
		if (empty($this->companies))
		{
			$sql = "SELECT * FROM Company ORDER BY id ASC";
			$this->companies = $this->conn->fetchAll($sql);
		}
		return $this->companies;
	}
	/**
	 * Get users 
	 */
	public function getUsers()
	{
		if (empty($this->users))
		{
			$sql = "SELECT * FROM User ORDER BY lastname ASC";
			$this->users = $this->conn->fetchAll($sql);
		}
		return $this->users;
	}
	public function getProviders()
	{
		$sql = "SELECT * FROM Customer 
		LEFT JOIN customer_customerstatus ON Customer.id = customer_customerstatus.customer_id
		WHERE customer_customerstatus.customerstatus_id = 6 AND Customer.type = 'company' ORDER BY Customer.lastname ASC";
		return $this->conn->fetchAll($sql);
	}
	public function getMakes()
	{
		$sql = "SELECT * FROM Make ORDER BY name ASC";
		return $this->conn->fetchAll($sql);
	}
	
	public function getName()
	{
		return 'acme_page';
	}
	public static function localeDateFilter($date, $dateType = 'long', $timeType = 'none', $locale = null, $pattern = null)
	{
		$formatter = new DateFormatter();
		
		return $formatter->format($date, $dateType, $timeType, $locale, $pattern);
	}
}
