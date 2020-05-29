<?php 

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AppController extends AbstractController {
	/** @Route("/", methods={"GET"}, name="accueil") */
	public function index(ArticleRepository $articleRepository) : Response
	{
		$articles = $articleRepository->findLastArticles(3);

		return $this->render('home.html.twig', [
			'articles' => $articles
		]);
    }
}

?>