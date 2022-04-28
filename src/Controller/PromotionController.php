<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PrommotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
/**
 * @Route("/promotion")
 */
class PromotionController extends AbstractController
{
    /**
     * @Route("/", name="app_promotion_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request): Response
    {
        $allpromotions = $entityManager
            ->getRepository(Promotion::class)
            ->findAll();
        $promotions = $paginator->paginate(
        // Doctrine Query, not results
            $allpromotions,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );

        return $this->render('promotion/index.html.twig', [
            'promotions' => $promotions,
        ]);
    }
    /**
     * @Route("/promoF", name="app_promo_indexF", methods={"GET"})
     */
    public function indexF(EntityManagerInterface $entityManager): Response
    {
        $promotions = $entityManager
            ->getRepository(Promotion::class)
            ->findAll();

        return $this->render('promotion/ProduitPromoF.html.twig', [
            'promotions' => $promotions,
        ]);
    }

    /**
     * @Route("/new", name="app_promotion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promotion);
            $entityManager->flush();
            $flashy->success('Promotion ajoutée');
            return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promotion/new.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idPromotion}", name="app_promotion_show", methods={"GET"})
     */
    public function show(Promotion $promotion,FlashyNotifier $flashy): Response
    {

        $flashy->success('Détails de la promotion');
        return $this->render('promotion/show.html.twig', [
            'promotion' => $promotion,
        ]);
    }

    /**
     * @Route("/{idPromotion}/edit", name="app_promotion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Promotion $promotion, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $flashy->success('Promotion modifiée');
            return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promotion/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idPromotion}", name="app_promotion_delete", methods={"POST"})
     */
    public function delete(Request $request, Promotion $promotion, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotion->getIdPromotion(), $request->request->get('_token'))) {
            $entityManager->remove($promotion);
            $entityManager->flush();
            $flashy->error('Promotion supprimée');
        }

        return $this->redirectToRoute('app_promotion_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/ajax_search/", name="ajax_search")
     */
    public function search(\Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');// ooofkdokfdfdf
        $promotions =  $em->getRepository(promotion::class)->rechercheAvance($requestString);
        if(!$promotions) {
            $result['promotions']['error'] = "promotion non trouvée :( ";
        } else {
            $result['$promotions'] = $this->getRealEntities($promotions);
        }
        return new Response(json_encode($result));
    }


// LES  attributs
    public function getRealEntities($promotions){
        foreach ($promotions as $promotions){
            $realEntities[$promotions->getIdPromotion()] = [$promotions->getLibellePromotion(),$promotions->getPourcentage(),$promotions->getDescription(),$promotions->getPourcentage()];

        }
        return $realEntities;
    }
    /**
     * @Route("/stat", name="note_stat", methods={"GET"})

     */
    public function board( PrommotionRepository $prommotionRepository): Response
    {
        return $this->render('promotion/stat.html.twig', [
            'promotions'=> $prommotionRepository->findAll(),
        ]);
    }
}
