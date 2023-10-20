<?php

namespace App\Controller;
use App\Entity\Book;
use App\Entity\Author;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BookRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;
//src/Controller/BookController.php

class BookController extends AbstractController
{
    #[Route('/showBook', name: 'book_show')]
    public function show(BookRepository $repo): Response
    {
        $books = $repo->findAll();
        return $this->render('book/show.html.twig', ['books'=>$books]);
    }
   // #[Route('/book', name: 'app_book')]
   /*  public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }*/
    // ...

    /** 
     * @Route("/add-book", name="add_book")
     **/
    #[Route('/addBook', name: 'app_book_add')]
    public function addBook(Request $request,EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $author = $book->getAuthor();
            $author->setNb_book($author->getNb_book()+ 1); // Incrémentation de nb_books
            $book->setPublished(true);
           // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book_show');
        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/updateBook/{id}', name: 'app_book_update')]
    public function updateBook($id,BookRepository $repo,Request $req,ManagerRegistry $manager){
        $book =$repo->find($id);
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($req);
        if($form->isSubmitted()){
        $manager->getManager()->flush();
        return $this->redirectToRoute('book_show');
        }
        return $this->render('book/edit.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/removeBook/{id}', name: 'app_book_remove')]
    public function deleteBook($id,BookRepository $repo,ManagerRegistry $manager){
        $author = $repo->find($id);
        $manager = $this->getDoctrine()->getManager();
        //$author = $manager->getRepository(Author::class)->find($id);
        if ($author !== null) {
           $manager->remove($author);
           // $manager->getManager()->remove($book);
       // $manager->getManager()->flush();
         $manager->flush();
        }
                return $this->redirectToRoute('book_show');

    }


    #[Route('/list-books', name: 'list_books')]
   public function listBooks(): Response
   {
       $publishedBooks = $this->getDoctrine()->getRepository(Book::class)->findBy(['published' => true]);
       $unpublishedBooks = $this->getDoctrine()->getRepository(Book::class)->findBy(['published' => false]);

       return $this->render('book/list.html.twig', [
           'publishedBooks' => $publishedBooks,
           'unpublishedBooks' => $unpublishedBooks,
       ]);
   }

}

    // ...
