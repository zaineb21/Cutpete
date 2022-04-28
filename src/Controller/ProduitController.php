<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ProduitType;
/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="app_produit_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request): Response
    {
        $allproduits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();

        $produits = $paginator->paginate(
        // Doctrine Query, not results
            $allproduits,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }
    /**
     * @Route("/FrontProd", name="app_p_indexF", methods={"GET"})
     */
    public function indexF(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();

        return $this->render('produit/produitFront.html.twig', [
            'produits' => $produits,
        ]);
    }
    /**
     * @Route("/pdf", name="app_p_indexpdf", methods={"GET"})
     */
    public function indexpdf(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();


        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('produit/listepdf.html.twig', [
            'produits' => $produits,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/new", name="app_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file=$form->get('image')->getData();

            $file_name = md5(uniqid()) . '.' . $file->guessExtension();

            $flashy->success('Produit créé');
            try {

                $file->move(
                    $this->getParameter('images_directory'),
                    $file_name
                );

            } catch (FileException $e) {
            }
            $produit->setImage($file_name);
            $entityManager->persist($produit);

            $entityManager->flush();


            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idProduit}", name="app_produit_show", methods={"GET"})
     */
    public function show(Produit $produit,FlashyNotifier $flashy): Response
    {
        $flashy->success('voici le produit');
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{idProduit}/edit", name="app_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idProduit}", name="app_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager ,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getIdProduit(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }
        $flashy->success('Produit supprimé');
        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/search/" ,name="ajax_search1")

     */
    public function searchAction(ProduitRepository $ProduitRepository, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $produit = $ProduitRepository->findEntitiesByString($requestString);

        if (!$produit) {
            $result['produit']['error'] = "Post Not found :( ";
        } else {
            $result['produit'] = $this->getRealEntities($produit);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($produit)
    {
        foreach ($produit as $produit) {
            $realEntities[$produit->getIdProduit()] = [$produit->getImage(), $produit->getLibelle()];
        }
        return $realEntities;
    }

        /**
         * @Route("/stats", name="note_stat", methods={"GET"})

         */
        public function board( ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/stat.html.twig', [
            'produits'=> $produitRepository->findAll(),
        ]);
    }
}
