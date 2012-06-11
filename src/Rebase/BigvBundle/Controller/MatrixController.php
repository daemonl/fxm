<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Setting;
use Rebase\BigvBundle\Entity\Round;
use Rebase\BigvBundle\Entity\Calendar;
use Rebase\BigvBundle\AltObj\Matrix\Matrix;
use Rebase\BigvBundle\Common\BasicFunctions;

class MatrixController extends _Season
{
 
  public function indexAction()
  {
	  $em = $this->getDoctrine()->getEntityManager();
	   $divisions = $em->getRepository('RebaseBigvBundle:Division')->findAll();
	   return $this->render('RebaseBigvBundle:Matrix:index.html.twig', array('divisions'=>$divisions));
  }
  
  
  public function viewAction($divisionName)
  {
	 $em = $this->getDoctrine()->getEntityManager();
	 $division = $em->getRepository('RebaseBigvBundle:Division')->findOneByName($divisionName);
		 
	 $matrix = new Matrix($em, $division);
	 
	 return $this->render('RebaseBigvBundle:Matrix:matrix.html.twig', array('division'=>$division, 'matrix'=>$matrix));
  }
 
}
?>