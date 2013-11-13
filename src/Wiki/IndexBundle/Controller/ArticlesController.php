<?php

namespace Wiki\IndexBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Doctrine\Common\Collections\ArrayCollection;
use Wiki\IndexBundle\Entity\Article;
use Wiki\IndexBundle\Entity\Tag;
use Wiki\IndexBundle\Form\Type\ArticleType;

class ArticlesController extends Controller
{
	public function previewAction()
	{
		$markdown = $this->getRequest()->request->get('markdown');

		return new Response($this->container->get('markdown.parser')->transformMarkdown($markdown));
	}
	public function tagsRemoteAction()
	{
		$request = $this->getRequest();
		$tags = $this->getDoctrine()->getManager()->getRepository('WikiIndexBundle:Tag')->tagsRemote($request->query->get('searchQuery'));

		$results = array();
		$i = 0;
		foreach ($tags as $tag)
		{
			$results[$i]['id'] = $tag->getId();
			$results[$i]['name'] = $tag->getName();
			$results[$i]['value'] = $tag->getName();
			$results[$i]['tokens'] = array($tag->getName());
			$i++;
		}

		$results[$i]['id'] = '0';
		$results[$i]['name'] = $request->query->get('searchQuery');
		$results[$i]['value'] = 'Ajouter un tag : ' . $request->query->get('searchQuery');
		$results[$i]['tokens'] = array($request->query->get('searchQuery'));

		return new JsonResponse($results);
	}

 	/** 
 		@PreAuthorize("hasRole('ROLE_SUPER_ADMIN')") 
 	*/
	public function createAction()
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$user = $this->get('security.context')->getToken()->getUser();

		$article = new Article;
		$article->setCreator($user);
		$form = $this->createForm ( new ArticleType() , $article );

		if( $request->getMethod() == 'POST' )
		{
			$form->bind($request);
			
			if ( $form->isValid() )
			{
				if (null !== $request->request->get('tags_id'))
				{
					$tags_name = $request->request->get('tags_name');
					foreach($request->request->get('tags_id') as $key => $tag_id)
					{
						if (empty($tag_id))
						{
							$tag = new Tag;
							$tag->setName($tags_name[$key]);
						}
						else
						{
							$tag = $em->getReference('WikiIndexBundle:Tag', $tag_id);
						}

						$article->addTag($tag);
					}
				}
				$em = $this->getDoctrine()->getManager();
				$em->persist($article);
				$em->flush();

				// add notice to session
				$notice = $this->get('translator')->trans('flashbag.users.add.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);

				// redirect to list
				return $this->redirect($this->generateUrl('wiki_articles_read' , array('slug' => $article->getSlug())));
			}
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("IT Wiki", $this->get("router")->generate("wiki_homepage"));
		$breadcrumbs->addItem("Ajouter un article");
		return $this->render('WikiIndexBundle:Articles:create.html.twig' , array( 'form' => $form->createView() ));
	}
	public function readAction($slug)
	{
		$em = $this->getDoctrine()->getManager();
		$article = $em->getRepository('WikiIndexBundle:Article')->findOneBySlug($slug);


		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("IT Wiki", $this->get("router")->generate("wiki_homepage"));
		$breadcrumbs->addItem($article->getTitle(), $this->get("router")->generate("wiki_articles_read", array('slug' => $article->getSlug())));
		return $this->render('WikiIndexBundle:Articles:read.html.twig' , array( 'article' => $article ));
	}

 	/** 
 		@PreAuthorize("hasRole('ROLE_SUPER_ADMIN')") 
 	*/
	public function updateAction(Article $article)
	{
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$user = $this->get('security.context')->getToken()->getUser();

		$form = $this->createForm ( new ArticleType() , $article );

		if( $request->getMethod() == 'POST' )
		{
			$form->bind($request);
			
			if ( $form->isValid() )
			{
				if (null !== $request->request->get('tags_id'))
				{
					$article->resetTags();
					$em->flush();
					$tags_name = $request->request->get('tags_name');
					foreach($request->request->get('tags_id') as $key => $tag_id)
					{
						if ($tag_id == '0')
						{
							$tag = new Tag;
							$tag->setName($tags_name[$key]);
						}
						else
						{
							$tag = $em->getReference('WikiIndexBundle:Tag', $tag_id);
						}

						$article->addTag($tag);
					}
				}
				$em = $this->getDoctrine()->getManager();
				$em->flush();

				// add notice to session
				$notice = $this->get('translator')->trans('flashbag.users.edit.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);

				// redirect to list
				return $this->redirect($this->generateUrl('wiki_articles_update' , array('id' => $article->getId())));
			}
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("IT Wiki", $this->get("router")->generate("wiki_homepage"));
		$breadcrumbs->addItem("Éditer un article");
		return $this->render('WikiIndexBundle:Articles:update.html.twig' , array( 'article' => $article , 'form' => $form->createView() ));
	}
	
 	/** 
 		@PreAuthorize("hasRole('ROLE_SUPER_ADMIN')") 
 	*/
	public function deleteAction(Article $article)
	{
		$em = $this->getDoctrine()->getManager();
		$form = $this->createFormBuilder()->getForm();
 
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bind($request);
 
			if ($form->isValid()) {
				$em->remove($article);
				$em->flush();
 
				// On définit un message flash
				$notice = $this->get('translator')->trans('flashbag.users.delete.notice.done');
				$this->get('session')->getFlashBag()->add('notice', $notice);
 
				// Puis on redirige vers l'accueil
				return $this->redirect($this->generateUrl('wiki_homepage'));
			}
		}

		$breadcrumbs = $this->get("white_october_breadcrumbs");
		$breadcrumbs->addItem("IT Wiki", $this->get("router")->generate("wiki_homepage"));
		$breadcrumbs->addItem("Supprimer un article");
		return $this->render('WikiIndexBundle:Articles:delete.html.twig' , array( 'article' => $article , 'form' => $form->createView()));
	}
}
