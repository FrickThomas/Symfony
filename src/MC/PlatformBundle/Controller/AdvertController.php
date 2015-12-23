<?php

namespace MC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MC\PlatformBundle\Entity\Advert;
use MC\PlatformBundle\Entity\Application;
use MC\PlatformBundle\Form\AdvertType;
use MC\PlatformBundle\Form\AdvertEditType;

class AdvertController extends Controller
{
	public function indexAction($page)
	{
		if ($page < 1) {
			throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
		}

		$listAdverts = $this->getDoctrine()
      		->getManager()
      		->getRepository('MCPlatformBundle:Advert')
      		->findAll(array('date' => 'desc'));

		return $this->render('MCPlatformBundle:Advert:index.html.twig',
			array('listAdverts' => $listAdverts));
	}

	public function viewAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$advert = $em
			->getRepository('MCPlatformBundle:Advert')
			->find($id);

		if (null === $advert) {
			throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}

		$listApplications = $em
			->getRepository('MCPlatformBundle:Application')
			->findBy(array('advert' => $advert));

		return $this->render('MCPlatformBundle:Advert:view.html.twig', array(
			'advert'           => $advert,
			'listApplications' => $listApplications));
	}

	public function addAction(Request $request)
	{
    	$advert = new Advert();
      $form = $this->createForm(AdvertType::class, $advert);

      if ($form->handleRequest($request)->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($advert);
        $em->flush();

        $request
				  ->getSession()
				  ->getFlashBag()
				  ->add('notice', 'Annonce bien enregistrée.');

      	return $this->redirect($this->generateUrl('mc_platform_view',
      		array('id' => $advert->getId())));
    	}

    	return $this->render('MCPlatformBundle:Advert:add.html.twig',
        array('form' => $form->CreateView()));
	}

	public function editAction($id, Request $request)
	{
    	$em = $this->getDoctrine()->getManager();

    	$advert = $em->getRepository('MCPlatformBundle:Advert')->find($id);

    	if ($advert == null) {
      		throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    	}

      $form = $this->createForm(AdvertEditType::class, $advert);

      if ($form->handleRequest($request)->isValid()) {
        $em->flush();

        $request
          ->getSession()
          ->getFlashBag()
          ->add('notice', 'Annonce bien modifiée.');

        return $this->redirect($this->generateUrl('mc_platform_view',
          array('id' => $advert->getId())));
      }

      return $this->render('MCPlatformBundle:Advert:edit.html.twig', array(
          'form' => $form->CreateView(),
          'advert' => $advert));
	}

	public function deleteAction($id, Request $request)
	{
		// On récupère l'EntityManager
    	$em = $this->getDoctrine()->getManager();

    	// On récupère l'entité correspondant à l'id $id
    	$advert = $em->getRepository('MCPlatformBundle:Advert')->find($id);

    	// Si l'annonce n'existe pas, on affiche une erreur 404
    	if ($advert == null) {
      		throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    	}

    	$form = $this->createFormBuilder()->getForm();

      if ($form->handleRequest($request)->isValid()) {
        $em->remove($advert);
        $em->flush();
      
        $request
          ->getSession()
          ->getFlashBag()
          ->add('notice', 'Annonce bien supprimée');

        return $this->redirect($this->generateUrl('mc_platform_home'));
      }


    	return $this->render('MCPlatformBundle:Advert:delete.html.twig',array(
      		'advert' => $advert,
          'form'   => $form->createView()));
	}

	public function menuAction($limit = 3)
	{
		$listAdverts = $this->getDoctrine()
      		->getManager()
      		->getRepository('MCPlatformBundle:Advert')
      		->findBy(
        		array(),                 // Pas de critère
        		array('date' => 'desc'), // On trie par date décroissante
        		$limit,                  // On sélectionne $limit annonces
        		0                        // À partir du premier
    		);

    	return $this->render('MCPlatformBundle:Advert:menu.html.twig',
    		array('listAdverts' => $listAdverts));
	}
}