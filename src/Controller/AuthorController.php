<?php
//namespace App\Entity;
namespace App\Controller;
use App\Repository\AuthorRepository;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public $authors = [
        [
            'id' => 1,
            'picture' => '/images/Victor-Hugo.jpg',
            'username' => 'Victor Hugo',
            'email' => 'victor.hugo@gmail.com',
            'nb_books' => 100,
        ],
        [
            'id' => 2,
            'picture' => '/images/william-shakespeare.jpg',
            'username' => 'William Shakespeare',
            'email' => 'william.shakespeare@gmail.com',
            'nb_books' => 200,
        ],
        [
            'id' => 3,
            'picture' => '/images/Taha_Hussein.jpg',
            'username' => 'Taha Hussein',
            'email' => 'taha.hussein@gmail.com',
            'nb_books' => 300,
        ],
    ];
    public function list(){
        $sumOfBooks = 0;
    foreach ($this->authors as $author) {
        $sumOfBooks += $author['nb_books'];
    }
    return $this->render('author/list.html.twig', [
        'authors' => $this->authors,
        'sumOfBooks' => $sumOfBooks,

    ]);
}

public function authorDetails(int $id)
{
    $id--;
    $author = $this->authors[$id];

    if (!$author) {
        throw new NotFoundHttpException('Auteur non trouvé');
    }
    
    return $this->render('author/show.html.twig', [
        'author' => $author,
        
    ]);
}
#[Route('/author/get/all',name:'app_get_all')]
public function getAll(AuthorRepository $repo){
   $repo->findAll();
   return $this->render('author/liste.html.twig',['author'=>$author]);
}

#[Route('/author/add',name:'app_add_author')]
public function add(ManagerRegistry $manager){
    $author=new Author();
    $author->setusername('author1');
    $author->setEmail('author1@esprit.tn');
   $manager->getManager()->persist($author);
   $manager->getManager()->flush();
   return $this->redirectTORoute('app_get_all');
}

#[Route('/author/delete/{id}',name:'app_delete_author')]
public function delete ($id,ManagerRegistry $manager,AuthorRepository $repo){
$author = $repo->find($id);
$manager->getManager()->remove($author);
$manager->getManager()->flush();
return $this->redirectToRoute('app_get_all');
}

}