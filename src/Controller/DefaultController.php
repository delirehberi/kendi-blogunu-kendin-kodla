<?php

namespace App\Controller;

use App\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Blog::class);

        $items = $repository->findAll();
        return $this->render('default/index.html.twig', [
            'items'=>$items
        ]);
    }

    /**
     * @Route("/{slug}",name="blog_detail")
     */
    public function detail($slug){
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Blog::class);
        $item = $repository->findOneBy(['slug'=>$slug]);
        if(!$item) {
            throw new NotFoundHttpException();
        }

        return $this->render('default/blog_detail.html.twig',[
            'item'=>$item,
        ]);
    }
}
