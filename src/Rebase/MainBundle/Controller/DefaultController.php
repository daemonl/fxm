<?php

namespace Rebase\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Rebase\MainBundle\Entity\Test;

class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('RebaseMainBundle:Default:index.html.twig');
    }
    public function createAction()
    {
	    $product = new Test();
    	$product->setName('A Foo Bar');
    	$product->setPrice('19.99');
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	$em->persist($product);
    	$em->flush();
    	
    	return new Response('Created product id '.$product->getId());
    }
    public function pageAction($page)
    {
	    $template = sprintf('RebaseMainBundle:Default:%s.html.twig', $page);
 
        if (!$this->get('templating')->exists($template)) {
            //throw new NotFoundHttpException("The specified page could not be found.");
            return $this->render('RebaseMainBundle:Default:index.html.twig');
        }
        return $this->render($template);
        
    }
}
