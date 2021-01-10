<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController{
    /**
     * @Route("/admin/category", name="admin_category")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Category::class);
        $items = $repository->findAll();

        return $this->render('admin/category/index.html.twig', [
            'items'=>$items,
        ]);
    }
    /**
     * @Route("/admin/category/create/{id}",name="admin_category_create",defaults={"id":null})
     */
    public function create(Request $request, $id=null){
        $em = $this->getDoctrine()->getManager();
        $category_item = new Category();
        if(!is_null($id) && ((int)$id)>0 ) {
            $category_item = $em->find(Category::class,$id)??$category_item;
        }

        $form = $this->createForm(CategoryType::class,$category_item);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $slug = $slugify->slugify($category_item->getName());
            $category_item->setSlug($slug);
            $em->persist($category_item);
            $em->flush();
            $this->addFlash('success',"Kategori başarıyla kaydedildi!");
            return $this->redirectToRoute('admin_category');
        }

        return $this->render('admin/category/create.html.twig',[
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/admin/category/remove/{id}",name="admin_category_remove")
     */
    public function remove($id){
        try{
            $em = $this->getDoctrine()->getManager();
            $category_item = $em->find(Category::class,$id);
            if(!$category_item) { throw new NotFoundHttpException("Bu numaraya sahip kategory bulunamadı!") ;}
            $em->remove($category_item);
            $em->flush();

            $this->addFlash("success","Kategori başarıyla silindi!");

        }catch(\Exception $e){
            $this->addFlash("danger", "Kategori silinemedi!");
        }
        return $this->redirectToRoute('admin_category');
    }
}
