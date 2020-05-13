<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PDO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\VarDumper\Caster\PdoCaster;
use Twig\Environment;

class HomeController extends AbstractController
{



    /**
     * @Route("/category/{id}", name="category")
     */
    public function category(EntityManagerInterface $em, PostRepository $repository, CategoryRepository $categoryRepository, $id)
    {

        $category = $categoryRepository->find($id);
        if (!$category) {
            // throw new NotFoundHttpException('la categorie  n\'existe pas');
            throw $this->createNotFoundException('la categorie  n\'existe pas');
        }

        return $this->render('category.html.twig', ['category' => $category]);
    }






    /**
     * @Route("/test", name="test")
     */
    public function test(EntityManagerInterface $em, PostRepository $repository, CategoryRepository $categoryRepository)
    {
        $post = $repository->find(43);

        // supprime un article
        //$em->remove($post);
        // $posts = $repository->findAll();
        // $postsp = $repository->findOneBy([
        //     'title' => 'mon premier article',
        //     'content' => 'contenu de mon premier article'
        // ]);
        // $post = new Post;
        // $post->setTitle('mon premier article')
        //     ->setContent('contenu de mon premier article')
        //     ->setCreatedAt(new DateTime());

        //pour creer
        // $em->persist($post);
        //pour env les changements creation supression update
        //$em->flush();
        dd($post);
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {

        return $this->render('home.html.twig');
    }

    /**
     * @Route("/hello/{name?World}", name="hello")
     */
    public function hello(string $name): Response
    {
        $dsn = "mysql:host=localhost;dbname=monair";

        $login = "root";
        $password = "";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        $pdo = new PDO($dsn, $login, $password, $options);
        $pdo->exec("set names utf8");

        $sql = "SELECT * FROM states ";

        $query = $pdo->query($sql);

        $states = $query->fetchAll();


        $prenoms = ['robert', 'pierrot', 'jean-mimi'];
        $formateur = ['prenom' => 'lior', 'nom' => 'chamla'];
        $eleves = [
            ['prenom' => 'robert', 'nom' => 'nono'],
            ['prenom' => 'jean', 'nom' => 'michmic'],
            ['prenom' => 'riri', 'nom' => 'gege'],
        ];

        $html = $this->render('hello.html.twig', [
            'prenom' => $name,
            'prenoms' => $prenoms,
            'formateur' => $formateur,
            'eleves' => $eleves,
            'states' => $states,
        ]);
        return new Response($html);
    }
}
