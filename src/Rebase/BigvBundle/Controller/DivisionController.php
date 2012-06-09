<?php

namespace Rebase\BigvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Rebase\BigvBundle\Entity\Division;


use Rebase\BigvBundle\Common\BasicFunctions;

class DivisionController extends _Season
{
    
    public function indexAction()
    {
		  $repository = $this->getDoctrine()
			  ->getRepository('RebaseBigvBundle:Division');
      
		  $divisions = $repository->findAll();
      return $this->render('RebaseBigvBundle:Division:divisionIndex.html.twig', array('divisions'=>$divisions));
    }

    public function createAction(Request $request)
    {
      //CreateVenue
      $division = new Division();


	  $form = $this->createFormBuilder($division)
            ->add('name')
			->add('GamesPerTeam')
			->add('FullName', 'text' , array('required' => false))
            ->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
      			$em->persist($division);
      			$em->flush();
      			//ViewVenue
      			return $this->redirect($this->generateUrl('_RBV_division_index'));
			}
		}
        
		return $this->render('RebaseBigvBundle:Division:divisionCreate.html.twig', array(
          	'form' => $form->createView()));
    }
    public function deleteAction(request $request, $divisionID)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$division = $em->getRepository('RebaseBigvBundle:Division')->findOneById($divisionID);
      	$em->remove($division);
      	$em->flush();
		return $this->redirect($this->generateUrl('_RBV_division_index'));
	}

    public function editAction(request $request, $divisionID)
    {
      $em = $this->getDoctrine()->getEntityManager();
      
      if ($divisionID == 0)
      {
        $division = new Division();
        $division->setSeason($this->season);
      }else{
        $query = $em->createQuery("SELECT d FROM RebaseBigvBundle:Division d WHERE d.id=?1 AND d.season=?2")
                ->setParameter(1, $divisionID)
                ->setParameter(2, $this->season->getId());
        $division = $this->singleResultOr404($query);
      }
      
      $form = $this->createFormBuilder($division)
          ->add('name')
          ->add('FullName')
          ->add('GamesPerTeam')
          ->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
      			$em->persist($division);
      			$em->flush();
      			return $this->redirect($this->generateUrl('_RBV_division_index'));
			}
		}
        
		return $this->render('RebaseBigvBundle:Division:divisionForm.html.twig', array(
          	'form' => $form->createView(),
		  	'division' => $division));
	}
}
