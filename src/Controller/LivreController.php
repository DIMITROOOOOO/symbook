<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

final class LivreController extends AbstractController
{
    #[Route('/admin/livres', name: 'app_livres')]
    public function all(LivreRepository $rep ,EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        
        
        $livres = $paginator->paginate(
            $rep->findAll(),
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );


        //dd($livre);
        return $this->render('livre/all.html.twig', ['livres' => $livres],);
    }
    #[Route('/admin/livres/delete/{id}', name: 'app_livres_delete')]
    public function delete(Livre $livr, EntityManagerInterface $em): Response
    {
        $em->remove($livr);
        $em->flush();
        dd($livr); // id est null donc supprimer
    }
    #[Route('/admin/livres/update/{id}', name: 'app_livres_update')]
    public function update(Livre $livre, EntityManagerInterface $em): Response
    {
        //$livre = $rep->find($id);
        $nvPrix = $livre->getPrix() * 1.1;
        $livre->setPrix($nvPrix);
        $em->persist($livre);
        $em->flush();
        dd($livre); // id est null donc supprimer
    }
    #[Route('/admin/livres/show/{id}', name: 'app_livres_show')]
    public function show(Livre $livre): Response
    {
        //paramconvertor je met l'id en url et en parametre l'objet que je vais récupperer il vas chercher et retourner automatiqument l livre de cet id en var livre de parametre
        if (!$livre) {
            throw $this->createNotFoundException('no book found for ');
        }
        return $this->render('livre/details.html.twig', ['livre' => $livre]);
    }
    #[Route('/admin/livres/show2', name: 'app_livres_show2')]
    public function show2(LivreRepository $rep): Response
    {
        $livre = $rep->findOneBy(['titre' => 'titre 1']); //recupération de livre par son titre=titre1

        dd($livre);
    }
    #[Route('/admin/livres/show3', name: 'app_livres_show3')]
    public function show3(LivreRepository $rep): Response
    {
        $livre = $rep->findBy(['titre' => 'titre 1', 'editeur' => 'emi'], ['prix' => 'DESC']); //recupération de tous les livres qui ont titre=titre1 et par ordre décroissant de leurs prix

        dd($livre);
    }
    #[Route('/admin/livres/create', name: 'app_livres_create')]
    public function create(EntityManagerInterface $em): Response
    {
        $livre = new Livre();
        $date = new \DateTime('2023-02-02');
        $livre->setTitre('titre 1')->setSlug('titre-1')->setImage('https://picsum.photos/images/200/?id=1')->setResume('resume 1')->setEditeur('emi')->setDateEdition($date)->setIsbn('111-111-1111-1111')->setPrix(20);
        $em->persist($livre);
        $em->flush();
        return new Response("created new book with id " . $livre->getId());
    }
    
}