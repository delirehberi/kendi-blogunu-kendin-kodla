<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/admin/blog", name="admin_blog")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Blog::class);
        $items = $repository->findAll();

        return $this->render('admin/blog/index.html.twig', [
            'items'=>$items,
        ]);
    }
    /**
     * @Route("/admin/blog/create/{id}",name="admin_blog_create",defaults={"id":null})
     */
    public function create(Request $request, $id=null){
        $em = $this->getDoctrine()->getManager();
        $blog_item=new Blog();

        if(!is_null($id) && ((int)$id)>0 ) {
            $blog_item = $em->find(Blog::class,$id)??$blog_item;
        }

        if($request->isMethod('POST')) {
            $title = $request->get('title');
            $content = $request->get('content');
            try{
                if(!empty($title)) {
                    $slugify = new Slugify();
                    $slug = $slugify->slugify($title);
                    $blog_item->setTitle($title)
                              ->setSlug($slug)
                              ->setContent($content);

                    if(!$blog_item->getId()) {
                        $blog_item->setCreatedAt(new \DateTime());
                    }

                    $em->persist($blog_item);
                    $em->flush();
                    $this->addFlash('success',"İçerik başarıyla kaydedildi!");
                    return $this->redirectToRoute('admin_blog');
                }
                throw new \Exception("Başlık eklemek zorunludur.");
            }catch(\Exception $e) {
                $this->addFlash('danger',$e->getMessage());
            }
        }
        return $this->render('admin/blog/create.html.twig',[]);
    }
    /**
     * @Route("/admin/blog/remove/{id}",name="admin_blog_remove")
     */
    public function remove($id){
        try{
            $em = $this->getDoctrine()->getManager();
            $blog_item = $em->find(Blog::class,$id);
            if(!$blog_item) { throw new NotFoundHttpException("Bu numaraya sahip içerik bulunamadı!") ;}
            $em->remove($blog_item);
            $em->flush();

            $this->addFlash("success","İçerik başarıyla silindi!");

        }catch(\Exception $e){
            $this->addFlash("danger", "İçerik silinemedi!");
        }
        return $this->redirectToRoute('admin_blog');
    }
}
