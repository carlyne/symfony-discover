<?php 

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController{

	private $articleRepository;

	public function __construct(ArticleRepository $articleRepository)
	{
		$this->articleRepository = $articleRepository;
	}

	/** @Route("/article", name="article") */
	public function showAll() : Response
	{	
		// si pas de repository dans construct : $articleRepository = $this->getDoctrine()->getRepository(Article::class);
		$articles = $this->articleRepository->findAll();

        return $this->render('article/index.html.twig', compact('articles'));
    }


	// formulaire auto généré avec make:form
	/** @Route("article/create", name="article_create", methods={"GET","POST"}) */
	public function new(Request $request) : Response
	{
		// cas GET (affichage) : on prépare l'article à créer avec le formulaire
		$article = new Article;
		// on prépare le formulaire avec createForm avec le form et l'obj traité dans le form
		$form = $this->createForm(ArticleType::class, $article);

		

		// cas POST (traitement) : on indique au form de traiter la requête
		$form->handleRequest($request);


		// formulaire envoyé et valide : on le traite 
		if($form->isSubmitted() && $form->isValid()) {
			//enregistrment de la donnée
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($article);
			$entityManager->flush();

			//redirection vers l'index
			return $this->redirectToRoute('article');
		}

		// cas GET ou si formulaire pas valide : on affiche le formulaire
		return $this->render('article/write.html.twig', [
			'article' => $article,
			'form' => $form->createView()
		]);
	}


// formulaire auto généré avec make:form
	/** @Route("article/{article}/update", name="article_update", methods={"GET","POST"}) */
	public function updateArticle(Request $request, Article $article) : Response
	{
		// cas GET (affichage) : on prépare l'article à créer avec le formulaire
		$singleArticle = $this->articleRepository->find($article);
		// on prépare le formulaire avec createForm avec le form et l'obj traité dans le form
		$form = $this->createForm(ArticleType::class, $singleArticle);

		// cas POST (traitement) : on indique au form de traiter la requête
		$form->handleRequest($request);

		// formulaire envoyé et valide : on le traite 
		if($form->isSubmitted() && $form->isValid()) {
			//enregistrment de la donnée
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($singleArticle);
			$entityManager->flush();

			//redirection vers l'index
			return $this->redirectToRoute('article');
		}

		// cas GET ou si formulaire pas valide : on affiche le formulaire
		return $this->render('article/write.html.twig', [
			'article' => $singleArticle,
			'form' => $form->createView()
		]);
	}
	















	// /** @Route("/article/write", name="article_write") */
	// public function write() : Response
	// {
	// 	return $this->render('article/write.html.twig');
	// }

	// /** @Route("/article/create",  methods={"POST"}, name="article_create") */
	// public function create(Request $request) : Response
	// {	
	// 	$article = new Article;

	// 	// on préferera utiliser $request que $_POST
	// 	// $article->setTitle($_POST['title']);
	// 	// $article->setContent($_POST['content']);

	// 	$article->setTitle($request->request->get('title')); // $request->query->get pour une méthode GET au lieu de POST
	// 	$article->setContent($request->request->get('content'));

	// 	// si pas injection de dépendances EntityManager, enregistrer article dans la BDD avec Doctrine + manager
	// 	$entityManager = $this->getDoctrine()->getManager();

	// 	// prepare
	// 	$entityManager->persist($article);
	// 	// execute
	// 	$entityManager->flush();

	// 	return $this->redirectToRoute('article');
	// }


	// /** @Route("/article/search",  name="article_search") */
	// public function search()
	// {
	// 	// $value = '%patat%';
	// 	// $articleRepository = $this->getDoctrine()->getRepository(Article::class);
	// 	// $article = $articleRepository->findByWord($value);
	// 	$article = $this->articleRepository->findByTerms('%content%');
		
		
	// 	dd($article);
	// }


	// /** @Route("/article/{article}/edit", name="article_edit", methods={"GET"}) */
	// public function edit(Request $request, Article $article) {
	// 	$singleArticle = $this->articleRepository->find($article);

	// 	return $this->render('article/write.html.twig', [
	// 		'article' => $singleArticle
	// 	]);
	// }

	// /** @Route("/article/{article}/update", name="article_update", methods={"POST"}) */
	// public function update(Request $request, Article $articleObj) {

	// 	$article = $this->articleRepository->find($articleObj);

	// 	// On met à jour l'article
	// 	$article->setTitle('Nouveau titre mis à jour');
	// 	// On récupère l'EntityManager et on met à jour (sans persister, juste flush)
	// 	$entityManager = $this->getDoctrine()->getManager();
	// 	$entityManager->flush();

	// 	return $this->redirectToRoute('article');

	// }

	/** @Route("/article/{article}/delete", name="article_delete", methods={"POST"}) */
	public function deleteArticle(Request $request, Article $article) {
		$entityManager = $this->getDoctrine()->getManager();

		$entityManager->remove($article);
		$entityManager->flush();

		return $this->redirectToRoute('article');
	}
	

	/** @Route("/article/{id}", methods={"GET", "HEAD"}, requirements={"id"="\d+"}, name="show_one") */
	public function showOne(Article $articleObj) : Response
	{
		$article = $this->articleRepository->find($articleObj);
		return $this->render('article/single.html.twig', [
			'article' => $article
		]);
    }
}

?>